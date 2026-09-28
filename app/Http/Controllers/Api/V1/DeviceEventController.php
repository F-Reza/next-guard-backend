<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\DeviceEvent;

use Illuminate\Http\JsonResponse;



class DeviceEventController extends Controller
{


    /**
     * Device event history
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
        | Events
        |--------------------------------------------------------------------------
        */


        $events = DeviceEvent::where(

                'device_id',

                $device->id

            )
            ->latest('id')
            ->limit(50)
            ->get([

                'id',
                'event',
                'created_at'

            ]);





        return response()->json([


            'success'=>true,


            'data'=>[


                'device_id'=>$device->id,


                'events'=>$events


            ]


        ]);



    }



}