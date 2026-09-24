<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\DeviceProtectionSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeviceProtectionController extends Controller
{

    /**
     * Get device protection settings.
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


        $settings = DeviceProtectionSetting::firstOrCreate(
            [
                'device_id' => $device->id,
            ],
            [
                'protection_status' => 'inactive',
            ]
        );


        return response()->json([
            'success' => true,
            'message' => 'Protection settings retrieved.',
            'data' => [
                'device_id' => $device->id,
                'settings' => $settings,
            ],
        ]);
    }



    /**
     * Update protection settings.
     */
    public function update(Request $request, int $id): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'betting_block' => [
                'boolean',
            ],

            'adult_content_block' => [
                'boolean',
            ],

            'facebook_ad_block' => [
                'boolean',
            ],

            'youtube_ad_block' => [
                'boolean',
            ],

            'safe_search' => [
                'boolean',
            ],

            'dns_protection' => [
                'boolean',
            ],

        ]);


        if ($validator->fails()) {

            return response()->json([
                'success'=>false,
                'message'=>'Validation failed.',
                'errors'=>$validator->errors(),
            ],422);

        }



        $user = auth('api')->user();


        $device = $user->devices()
            ->where('id',$id)
            ->first();


        if(!$device){

            return response()->json([
                'success'=>false,
                'message'=>'Device not found.',
            ],404);

        }



        $settings = DeviceProtectionSetting::updateOrCreate(

            [
                'device_id'=>$device->id,
            ],

            array_merge(
                $request->only([
                    'betting_block',
                    'adult_content_block',
                    'facebook_ad_block',
                    'youtube_ad_block',
                    'safe_search',
                    'dns_protection',
                ]),
                [
                    'protection_status'=>'active',
                    'last_sync_at'=>now(),
                ]
            )

        );


        return response()->json([

            'success'=>true,

            'message'=>'Protection settings updated.',

            'data'=>[
                'settings'=>$settings,
            ],

        ]);

    }




    /**
     * Android app sync status.
     */
    public function sync(Request $request, int $id): JsonResponse
    {

        $user = auth('api')->user();


        $device = $user->devices()
            ->where('id',$id)
            ->first();


        if(!$device){

            return response()->json([
                'success'=>false,
                'message'=>'Device not found.',
            ],404);

        }


        $settings = DeviceProtectionSetting::where(
            'device_id',
            $device->id
        )->first();


        if(!$settings){

            return response()->json([
                'success'=>false,
                'message'=>'Protection settings not found.',
            ],404);

        }



        $settings->update([

            'last_sync_at'=>now(),

            'protection_status'=>'active',

        ]);



        return response()->json([

            'success'=>true,

            'message'=>'Protection sync completed.',

            'data'=>[
                'device_id'=>$device->id,
                'last_sync_at'=>$settings->last_sync_at,
                'status'=>$settings->protection_status,
            ],

        ]);

    }

}