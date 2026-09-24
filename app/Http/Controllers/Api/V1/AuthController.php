<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceSession;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Refresh token lifetime in days.
     *
     * Later this can be moved to configuration.
     */
    private const REFRESH_TOKEN_DAYS = 30;

    /**
     * Register a new user.
     *
     * Registration does not create a device session yet.
     * Device enrollment happens separately.
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:120',
            ],
            'email' => [
                'nullable',
                'email',
                'max:190',
                'unique:users,email',
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
                'unique:users,phone',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'status' => 'active',
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful.',
            'data' => [
                'user' => $this->userData($user),
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 201);
    }

    /**
     * Login user and create a device session.
     *
     * Required:
     * - login
     * - password
     * - device_id
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'login' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
            'device_id' => [
                'required',
                'integer',
                'exists:devices,id',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

        $credentials = [
            $field => $login,
            'password' => $request->input('password'),
        ];

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid login credentials.',
            ], 401);
        }

        $user = auth('api')->user();

        /**
         * Check account status.
         */
        if ($user->status !== 'active') {
            auth('api')->logout();

            return response()->json([
                'success' => false,
                'message' => 'Your account is not active.',
            ], 403);
        }

        /**
         * Verify that the device belongs to this user.
         */
        $device = Device::where('id', $request->input('device_id'))
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$device) {
            auth('api')->logout();

            return response()->json([
                'success' => false,
                'message' => 'Device is not registered for this account.',
            ], 403);
        }

        /**
         * Create an access token containing device_id.
         *
         * We create a fresh JWT here so the token is explicitly
         * associated with the enrolled device.
         */
        auth('api')->logout();

        $token = JWTAuth::claims([
            'type' => 'access',
            'device_id' => $device->id,
        ])->fromUser($user);

        /**
         * Update last login.
         */
        $user->update([
            'last_login_at' => now(),
        ]);

        /**
         * Generate opaque refresh token.
         *
         * Raw token is returned only to the mobile client.
         * Database stores only its SHA-256 hash.
         */
        $refreshToken = $this->generateRefreshToken();

        $refreshTokenHash = $this->hashRefreshToken($refreshToken);

        $expiresAt = now()->addDays(self::REFRESH_TOKEN_DAYS);

        /**
         * Create device session.
         */
        $session = DeviceSession::create([
            'user_id' => $user->id,
            'device_id' => $device->id,
            'refresh_token_hash' => $refreshTokenHash,
            'status' => 'active',
            'expires_at' => $expiresAt,
            'last_used_at' => now(),
            'revoked_at' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data' => [
                'user' => $this->userData($user),
                'device_id' => $device->id,
                'access_token' => $token,
                'refresh_token' => $refreshToken,
                'token_type' => 'Bearer',
                'refresh_expires_at' => $session->expires_at,
            ],
        ]);
    }

    /**
     * Get authenticated user.
     */
    public function me(): JsonResponse
    {
        $user = auth('api')->user();

        return response()->json([
            'success' => true,
            'message' => 'Authenticated user.',
            'data' => [
                'user' => $this->userData($user),
            ],
        ]);
    }

    /**
     * Logout current device session.
     *
     * Access JWT must be valid.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $deviceId = $this->getDeviceIdFromToken();

        if ($deviceId) {
            DeviceSession::where('user_id', $user->id)
                ->where('device_id', $deviceId)
                ->where('status', 'active')
                ->update([
                    'status' => 'revoked',
                    'revoked_at' => now(),
                ]);
        }

        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful.',
        ]);
    }

    /**
     * Refresh access token using an opaque refresh token.
     *
     * This endpoint should NOT depend on a valid access JWT.
     */
    public function refresh(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'refresh_token' => [
                'required',
                'string',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $refreshToken = $request->input('refresh_token');

        $refreshTokenHash = $this->hashRefreshToken($refreshToken);

        /**
         * Lock the session row during rotation.
         */
        $session = DeviceSession::where(
            'refresh_token_hash',
            $refreshTokenHash
        )
            ->lockForUpdate()
            ->first();

        if (!$session) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid refresh token.',
            ], 401);
        }

        /**
         * Session must be active.
         */
        if ($session->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Refresh session is no longer active.',
            ], 401);
        }

        /**
         * Session must not be expired.
         */
        if (
            $session->expires_at &&
            $session->expires_at->isPast()
        ) {
            $session->update([
                'status' => 'expired',
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Refresh session has expired.',
            ], 401);
        }

        /**
         * Load user.
         */
        $user = User::find($session->user_id);

        if (!$user) {
            $session->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'User account not found.',
            ], 401);
        }

        /**
         * Account must still be active.
         */
        if ($user->status !== 'active') {
            $session->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Your account is not active.',
            ], 403);
        }

        /**
         * Verify device still exists and is active.
         */
        $device = Device::where('id', $session->device_id)
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$device) {
            $session->update([
                'status' => 'revoked',
                'revoked_at' => now(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Device is no longer active.',
            ], 403);
        }

        /**
         * Everything is valid.
         *
         * Rotate refresh token.
         */
        [$newAccessToken, $newRefreshToken, $newExpiresAt] =
            DB::transaction(function () use (
                $session,
                $user,
                $device,
                $request
            ) {
                /**
                 * Generate new access token.
                 */
                $newAccessToken = JWTAuth::claims([
                    'type' => 'access',
                    'device_id' => $device->id,
                ])->fromUser($user);

                /**
                 * Generate new opaque refresh token.
                 */
                $newRefreshToken = $this->generateRefreshToken();

                $newRefreshTokenHash = $this->hashRefreshToken(
                    $newRefreshToken
                );

                /**
                 * Rotate the existing session token.
                 *
                 * The old refresh token immediately becomes invalid.
                 */
                $newExpiresAt = now()->addDays(
                    self::REFRESH_TOKEN_DAYS
                );

                $session->update([
                    'refresh_token_hash' => $newRefreshTokenHash,
                    'expires_at' => $newExpiresAt,
                    'last_used_at' => now(),
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return [
                    $newAccessToken,
                    $newRefreshToken,
                    $newExpiresAt,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Token refreshed successfully.',
            'data' => [
                'access_token' => $newAccessToken,
                'refresh_token' => $newRefreshToken,
                'token_type' => 'Bearer',
                'refresh_expires_at' => $newExpiresAt,
                'device_id' => $device->id,
            ],
        ]);
    }

    /**
     * Generate a cryptographically secure opaque refresh token.
     */
    private function generateRefreshToken(): string
    {
        return Str::random(96);
    }

    /**
     * Hash refresh token before storing/searching.
     */
    private function hashRefreshToken(string $refreshToken): string
    {
        return hash('sha256', $refreshToken);
    }

    /**
     * Get device_id from current JWT.
     */
    private function getDeviceIdFromToken(): ?int
    {
        $payload = auth('api')->payload();

        $deviceId = $payload->get('device_id');

        return $deviceId ? (int) $deviceId : null;
    }

    /**
     * Format user response.
     */
    private function userData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'status' => $user->status,
        ];
    }
}