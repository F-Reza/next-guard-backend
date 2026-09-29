<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionViolationLog;

use App\Models\ProtectionSyncLog;

use Illuminate\Http\JsonResponse;



class ProtectionSummaryController extends Controller
{


    /**
     * Protection dashboard summary
     */
    public function summary(
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
        | Today Block Count
        |--------------------------------------------------------------------------
        */


        $todayBlocked = ProtectionViolationLog::where(
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
        | Total Block Count
        |--------------------------------------------------------------------------
        */


        $totalBlocked = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->count();







        /*
        |--------------------------------------------------------------------------
        | Category Summary
        |--------------------------------------------------------------------------
        */


        $categories = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->selectRaw(
                'category, COUNT(*) as count'
            )
            ->groupBy(
                'category'
            )
            ->get();







        /*
        |--------------------------------------------------------------------------
        | Latest Violation
        |--------------------------------------------------------------------------
        */


        $latestViolation = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->first();








        /*
        |--------------------------------------------------------------------------
        | Latest Sync
        |--------------------------------------------------------------------------
        */


        $sync = ProtectionSyncLog::where(
                'device_id',
                $device->id
            )
            ->latest('sync_version')
            ->first();








        return response()->json([


            'success'=>true,


            'data'=>[


                'device_id'=>$device->id,



                'today'=>[

                    'blocked_count'=>$todayBlocked

                ],




                'total'=>[

                    'blocked_count'=>$totalBlocked

                ],





                'categories'=>$categories,






                'latest_violation'=>

                    $latestViolation

                    ?

                    [

                        'domain'=>$latestViolation->domain,

                        'category'=>$latestViolation->category,

                        'action'=>$latestViolation->action,

                        'created_at'=>$latestViolation->created_at,

                    ]

                    :

                    null,






                'sync'=>[

                    'status'=>$sync?->apply_status,

                    'sync_version'=>$sync?->sync_version,

                    'synced_at'=>$sync?->synced_at,

                ]



            ]



        ]);


    }


}