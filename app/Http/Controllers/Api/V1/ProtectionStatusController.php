<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;
use App\Models\ProtectionViolationLog;
use App\Models\Subscription;
use Illuminate\Http\JsonResponse;



class ProtectionStatusController extends Controller
{


    public function status(
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
        | Protection Setting
        |--------------------------------------------------------------------------
        */


        $setting =
            $device->protectionSetting;






        /*
        |--------------------------------------------------------------------------
        | Subscription
        |--------------------------------------------------------------------------
        */


        $subscription =
            $device->subscriptions()
            ->latest('id')
            ->first();






        /*
        |--------------------------------------------------------------------------
        | Latest Sync
        |--------------------------------------------------------------------------
        */


        $lastSync =
            ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->orderByRaw("
                CASE apply_status
                    WHEN 'pending' THEN 1
                    WHEN 'failed' THEN 2
                    WHEN 'applied' THEN 3
                    ELSE 4
                END
            ")
            ->latest('sync_version')
            ->first();








        /*
        |--------------------------------------------------------------------------
        | Blocked Count Today
        |--------------------------------------------------------------------------
        */


        $blockedCount =
            ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->whereDate(
                'created_at',
                today()
            )
            ->count();








        /*
        |--------------------------------------------------------------------------
        | Last Violation
        |--------------------------------------------------------------------------
        */


        $lastViolation =
            ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->first();









        return response()->json([


            'success'=>true,


            'data'=>[



                'device_id'=>$device->id,





            'subscription'=>[


                'status'=> 
                    $subscription?->status
                    ??
                    null,


                'is_active'=>
                    $subscription?->status === 'active',


                'expires_at'=> 
                    $subscription?->expires_at
                    ??
                    null,


            ],








            'protection'=>[

                'status'=> 
                    $setting?->protection_status
                    ??
                    'inactive',

            'disabled_reason'=>
                $setting?->disabled_reason,
                

                'dns_protection'=> 
                    (bool)(
                        $setting?->dns_protection
                        ??
                        false
                    ),

                'safe_search'=> 
                    (bool)(
                        $setting?->safe_search
                        ??
                        false
                    ),

                'betting_block'=> 
                    (bool)(
                        $setting?->betting_block
                        ??
                        false
                    ),

            ],







                'sync'=>[



                    'status'=>
                        $lastSync?->apply_status
                        ??
                        null,




                    'sync_version'=>
                        $lastSync?->sync_version
                        ??
                        null,




                    'synced_at'=>
                        $lastSync?->synced_at
                        ??
                        null,




                    'needs_sync'=>

                        $lastSync
                        &&
                        $lastSync->apply_status !== 'applied',



                ],







                'violations'=>[



                    'blocked_count'=>
                        $blockedCount,





                    'last'=>

                        $lastViolation

                        ?

                        [


                            'domain'=>
                                $lastViolation->domain,



                            'category'=>
                                $lastViolation->category,



                            'action'=>
                                $lastViolation->action,



                            'created_at'=>
                                $lastViolation->created_at,


                        ]

                        :

                        null,



                ],





            ]



        ]);



    }



}