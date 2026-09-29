<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionViolationLog;

use Illuminate\Http\JsonResponse;


class ProtectionAnalyticsController extends Controller
{


    public function analytics(
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



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
        | Today
        |--------------------------------------------------------------------------
        */


        $today = ProtectionViolationLog::where(
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
        | Last 7 Days
        |--------------------------------------------------------------------------
        */


        $last7Days = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->where(
                'created_at',
                '>=',
                now()->subDays(7)
            )
            ->count();



        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */


        $total = ProtectionViolationLog::where(
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
        | Category Analytics
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
            ->orderByDesc(
                'count'
            )
            ->get();



        /*
        |--------------------------------------------------------------------------
        | Top Domains
        |--------------------------------------------------------------------------
        */


        $domains = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->where(
                'action',
                'blocked'
            )
            ->selectRaw(
                'domain, COUNT(*) as count'
            )
            ->groupBy(
                'domain'
            )
            ->orderByDesc(
                'count'
            )
            ->limit(10)
            ->get();




        return response()->json([


            'success'=>true,


            'data'=>[


                'device_id'=>$device->id,


                'today'=>[

                    'blocked'=>$today

                ],


                'last_7_days'=>[

                    'blocked'=>$last7Days

                ],


                'total'=>[

                    'blocked'=>$total

                ],



                'categories'=>$categories,


                'top_domains'=>$domains,


            ]

        ]);



    }


}