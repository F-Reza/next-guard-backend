<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;
use App\Models\DeviceEvent;
use App\Models\Subscription;

use Illuminate\Http\JsonResponse;



class DeviceSummaryController extends Controller
{


    public function summary(
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



        $device = $user
            ->devices()
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




        $setting =
            $device->protectionSetting;




        $subscription =
            Subscription::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->first();





        $events =
            DeviceEvent::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->limit(10)
            ->get([
                'id',
                'event',
                'created_at'
            ]);






        $lastEvent =
            $events->first();






        return response()->json([


            'success'=>true,


            'data'=>[



                'device_id'=>$device->id,



                'status'=>[


                    'current'=>$device->status,


                    'last_seen_at'=>$device->last_seen_at,


                ],




                'application'=>[


                    'version'=>$device->app_version,


                    'platform'=>$device->platform,


                ],





                'subscription'=>[


                    'status'=>
                        $subscription?->status
                        ??
                        null,


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


                    'dns_protection'=>
                        (bool)(
                            $setting?->dns_protection
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






                'events'=>[


                    'total'=>
                        DeviceEvent::where(
                            'device_id',
                            $device->id
                        )
                        ->count(),



                    'last'=>
                        $lastEvent,


                    'recent'=>
                        $events,


                ],



            ]



        ]);



    }



}