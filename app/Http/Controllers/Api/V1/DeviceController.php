<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceController extends Controller
{
    /**
     * Enroll or register a device for the authenticated user.
     */
    public function enroll(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'device_uuid' => [
                'required',
                'string',
                'max:255',
            ],
            'platform' => [
                'required',
                'string',
                'in:android',
            ],
            'model' => [
                'nullable',
                'string',
                'max:120',
            ],
            'manufacturer' => [
                'nullable',
                'string',
                'max:120',
            ],
            'android_version' => [
                'nullable',
                'string',
                'max:50',
            ],
            'app_version' => [
                'nullable',
                'string',
                'max:50',
            ],
            'management_mode' => [
                'nullable',
                'string',
                'in:standard,managed',
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = auth('api')->user();

        /*
        |--------------------------------------------------------------------------
        | Hash device UUID
        |--------------------------------------------------------------------------
        */

        $deviceUuidHash = hash(
            'sha256',
            $request->input('device_uuid')
        );

        /*
        |--------------------------------------------------------------------------
        | Find existing device
        |--------------------------------------------------------------------------
        */

        $device = Device::where('user_id', $user->id)
            ->where('device_uuid_hash', $deviceUuidHash)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Existing device
        |--------------------------------------------------------------------------
        */

        if ($device) {
            $device->update([
                'platform' => $request->input('platform'),
                'model' => $request->input('model'),
                'manufacturer' => $request->input('manufacturer'),
                'android_version' => $request->input('android_version'),
                'app_version' => $request->input('app_version'),
                'management_mode' => $request->input('management_mode', 'standard'),
                'status' => 'active',
                'last_seen_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Device already enrolled. Device information updated.',
                'data' => [
                    'device' => $this->deviceData($device),
                    'is_new' => false,
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Create new device
        |--------------------------------------------------------------------------
        */

        $device = Device::create([
            'user_id' => $user->id,
            'device_uuid_hash' => $deviceUuidHash,
            'platform' => $request->input('platform'),
            'model' => $request->input('model'),
            'manufacturer' => $request->input('manufacturer'),
            'android_version' => $request->input('android_version'),
            'app_version' => $request->input('app_version'),
            'management_mode' => $request->input('management_mode', 'standard'),
            'status' => 'active',
            'last_seen_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device enrolled successfully.',
            'data' => [
                'device' => $this->deviceData($device),
                'is_new' => true,
            ],
        ], 201);
    }

    /**
     * Get authenticated user's devices.
     */
    public function index(): JsonResponse
    {
        $user = auth('api')->user();

        $devices = $user->devices()
            ->latest()
            ->get()
            ->map(function (Device $device) {
                return $this->deviceData($device);
            });

        return response()->json([
            'success' => true,
            'message' => 'Devices retrieved successfully.',
            'data' => [
                'devices' => $devices,
            ],
        ]);
    }

    /**
     * Get a specific device.
     */
    public function show(int $id): JsonResponse
    {
        $user = auth('api')->user();

        $device = $user->devices()
            ->where('id', $id)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device retrieved successfully.',
            'data' => [
                'device' => $this->deviceData($device),
            ],
        ]);
    }

    /**
     * Update device heartbeat.
     */
    public function heartbeat(int $id): JsonResponse
    {
        $user = auth('api')->user();

        $device = $user->devices()
            ->where('id', $id)
            ->first();

        if (!$device) {
            return response()->json([
                'success' => false,
                'message' => 'Device not found.',
            ], 404);
        }

        $device->update([
            'last_seen_at' => now(),
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Device heartbeat updated.',
            'data' => [
                'device_id' => $device->id,
                'last_seen_at' => $device->last_seen_at,
            ],
        ]);
    }

    /**
     * Format device response.
     */
    private function deviceData(Device $device): array
    {
        return [
            'id' => $device->id,
            'platform' => $device->platform,
            'model' => $device->model,
            'manufacturer' => $device->manufacturer,
            'android_version' => $device->android_version,
            'app_version' => $device->app_version,
            'management_mode' => $device->management_mode,
            'status' => $device->status,
            'last_seen_at' => $device->last_seen_at,
            'created_at' => $device->created_at,
            'updated_at' => $device->updated_at,
        ];
    }
}