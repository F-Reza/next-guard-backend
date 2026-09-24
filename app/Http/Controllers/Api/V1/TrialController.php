<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\TrialEntitlement;
use App\Models\TrialEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrialController extends Controller
{
    /**
     * Check whether a user/device can start the base trial.
     */
    public function eligibility(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $device = $this->getDevice(
            $user->id,
            $request->input('device_id')
        );

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        $trial = TrialEntitlement::where('user_id', $user->id)
            ->where('device_id', $device->id)
            ->first();

        /**
         * No trial record means the user/device
         * is eligible for the base trial.
         */
        if (!$trial) {
            return response()->json([
                'success' => true,
                'message' => 'Trial is available.',
                'data' => [
                    'eligible' => true,
                    'device_id' => $device->id,
                    'status' => 'eligible',
                ],
            ]);
        }

        /**
         * Automatically mark the trial as expired
         * when the expiration time has passed.
         */
        $this->syncExpiredStatus($trial);

        return response()->json([
            'success' => true,
            'message' => 'Trial eligibility checked.',
            'data' => [
                'eligible' => false,
                'device_id' => $device->id,
                'status' => $trial->status,
                'started_at' => $trial->started_at,
                'expires_at' => $trial->expires_at,
            ],
        ]);
    }

    /**
     * Start the base trial.
     */
    public function start(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $device = $this->getDevice(
            $user->id,
            $request->input('device_id')
        );

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        /**
         * Create the trial and its event inside
         * the same database transaction.
         */
        [$trial, $created] = DB::transaction(function () use ($user, $device) {

            /**
             * Lock the existing record to prevent
             * duplicate trial creation during
             * concurrent requests.
             */
            $existing = TrialEntitlement::where('user_id', $user->id)
                ->where('device_id', $device->id)
                ->lockForUpdate()
                ->first();

            /**
             * Trial already exists.
             */
            if ($existing) {
                return [$existing, false];
            }

            $startedAt = now();

            /**
             * Initial V1 trial duration:
             * 3 days.
             *
             * This will later be moved to an
             * admin-configurable setting.
             */
            $expiresAt = $startedAt->copy()->addDays(3);

            /**
             * Create trial entitlement.
             */
            $trial = TrialEntitlement::create([
                'user_id' => $user->id,
                'device_id' => $device->id,
                'started_at' => $startedAt,
                'expires_at' => $expiresAt,
                'status' => 'active',
                'base_trial' => true,
            ]);

            /**
             * Create audit/event record.
             */
            TrialEvent::create([
                'user_id' => $user->id,
                'device_id' => $device->id,
                'event_type' => 'TRIAL_STARTED',
                'old_expires_at' => null,
                'new_expires_at' => $expiresAt,
                'reason' => 'Base trial started.',
                'admin_id' => null,
            ]);

            return [$trial, true];
        });

        /**
         * Existing trial.
         */
        if (!$created) {
            /**
             * Make sure returned status is up to date.
             */
            $this->syncExpiredStatus($trial);

            return response()->json([
                'success' => false,
                'message' => 'Trial has already been started for this device.',
                'data' => [
                    'trial' => $this->trialData($trial),
                ],
            ], 409);
        }

        return response()->json([
            'success' => true,
            'message' => 'Trial started successfully.',
            'data' => [
                'trial' => $this->trialData($trial),
            ],
        ], 201);
    }

    /**
     * Get current trial.
     */
    public function show(Request $request): JsonResponse
    {
        $user = auth('api')->user();

        $device = $this->getDevice(
            $user->id,
            $request->input('device_id')
        );

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        $trial = TrialEntitlement::where('user_id', $user->id)
            ->where('device_id', $device->id)
            ->first();

        if (!$trial) {
            return response()->json([
                'success' => true,
                'message' => 'No trial found.',
                'data' => [
                    'trial' => null,
                ],
            ]);
        }

        /**
         * Automatically update expired trial.
         */
        $this->syncExpiredStatus($trial);

        return response()->json([
            'success' => true,
            'message' => 'Trial retrieved successfully.',
            'data' => [
                'trial' => $this->trialData($trial),
            ],
        ]);
    }

    /**
     * Find a device belonging to the authenticated user.
     */
    private function getDevice(
        int $userId,
        $deviceId
    ): ?Device {
        return Device::where('user_id', $userId)
            ->where('id', $deviceId)
            ->first();
    }

    /**
     * Automatically mark an active trial as expired.
     *
     * This method is intentionally idempotent:
     * once status becomes "expired", another
     * TRIAL_EXPIRED event will not be created.
     */
    private function syncExpiredStatus(
        TrialEntitlement $trial
    ): void {
        if (
            $trial->status !== 'active' ||
            !$trial->expires_at ||
            !$trial->expires_at->isPast()
        ) {
            return;
        }

        $oldExpiresAt = $trial->expires_at;

        /**
         * Update trial status.
         */
        $trial->update([
            'status' => 'expired',
        ]);

        /**
         * Record expiration event.
         */
        TrialEvent::create([
            'user_id' => $trial->user_id,
            'device_id' => $trial->device_id,
            'event_type' => 'TRIAL_EXPIRED',
            'old_expires_at' => $oldExpiresAt,
            'new_expires_at' => $oldExpiresAt,
            'reason' => 'Trial expired automatically.',
            'admin_id' => null,
        ]);
    }

    /**
     * Format trial data for API response.
     */
    private function trialData(
        TrialEntitlement $trial
    ): array {
        return [
            'id' => $trial->id,
            'device_id' => $trial->device_id,
            'status' => $trial->status,
            'base_trial' => $trial->base_trial,
            'started_at' => $trial->started_at,
            'expires_at' => $trial->expires_at,
        ];
    }
}