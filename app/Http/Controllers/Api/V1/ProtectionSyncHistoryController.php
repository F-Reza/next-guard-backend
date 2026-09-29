<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;

use App\Models\ProtectionSyncLog;

use Illuminate\Http\JsonResponse;



class ProtectionSyncHistoryController extends Controller
{


    /**
     * Get device protection sync history
     */
    public function index(
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



        /*
        |--------------------------------------------------------------------------
        | Verify Device Ownership
        |--------------------------------------------------------------------------
        */


        $device = $user->devices()
            ->where(
                'id',
                $id
            )
            ->first();



        if(!$device){


            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);


        }




        /*
        |--------------------------------------------------------------------------
        | Get Sync History
        |--------------------------------------------------------------------------
        */


        $history = ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->latest('sync_version')
            ->get()
            ->map(function($log){

                return [

                    'id'=>$log->id,

                    'sync_version'=>$log->sync_version,

                    'rules_hash'=>$log->rules_hash,

                    'apply_status'=>$log->apply_status,

                    'device_version'=>$log->device_version,

                    'synced_at'=>$log->synced_at,

                    'applied_at'=>$log->applied_at,

                ];

            });



        return response()->json([


            'success'=>true,


            'message'=>'Protection sync history retrieved.',


            'data'=>[

                'device_id'=>$device->id,

                'history'=>$history

            ]



        ]);



    }



}