<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use App\Models\DeviceProtectionSetting;

use Illuminate\Http\JsonResponse;



class DeviceProtectionSyncController extends Controller
{


    /**
     * Device fetch pending protection sync
     */
    public function sync(): JsonResponse
    {

        $user = auth('api')->user();


        $device = $user->devices()
            ->latest('id')
            ->first();

            
        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }




        /*
        |--------------------------------------------------------------------------
        | Find Pending Sync
        |--------------------------------------------------------------------------
        */


        $sync = ProtectionSyncLog::where(

                'device_id',

                $device->id

            )
            ->where(

                'apply_status',

                'pending'

            )
            ->latest('id')
            ->first();






        /*
        |--------------------------------------------------------------------------
        | No Update Available
        |--------------------------------------------------------------------------
        */


        if(!$sync){


            return response()->json([


                'success'=>true,


                'message'=>'No protection update available.',


                'data'=>[

                    'changed'=>false,

                ]


            ]);


        }







        /*
        |--------------------------------------------------------------------------
        | Get Current Protection Payload
        |--------------------------------------------------------------------------
        */


        $setting = DeviceProtectionSetting::where(

                'device_id',

                $device->id

            )
            ->first();






        return response()->json([


            'success'=>true,


            'message'=>'Protection sync available.',



            'data'=>[


                'changed'=>true,


                'sync_version'=>$sync->sync_version,


                'rules_hash'=>$sync->rules_hash,


                'protection'=>[


                    'status'=>
                        $setting?->protection_status,


                    'betting_block'=>
                        (bool) $setting?->betting_block,


                    'adult_content_block'=>
                        (bool) $setting?->adult_content_block,


                    'facebook_ad_block'=>
                        (bool) $setting?->facebook_ad_block,


                    'youtube_ad_block'=>
                        (bool) $setting?->youtube_ad_block,


                    'safe_search'=>
                        (bool) $setting?->safe_search,


                    'dns_protection'=>
                        (bool) $setting?->dns_protection,


                ],


            ]


        ]);


    }


}