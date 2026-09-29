<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionNotification;

use Illuminate\Http\JsonResponse;



class ProtectionNotificationController extends Controller
{


    /**
     * Get protection notifications
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
        | Notifications
        |--------------------------------------------------------------------------
        */


        $notifications = ProtectionNotification::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->paginate(50);





        $unreadCount = ProtectionNotification::where(
                'device_id',
                $device->id
            )
            ->whereNull(
                'read_at'
            )
            ->count();






        return response()->json([


            'success'=>true,


            'message'=>'Protection notifications retrieved.',


            'data'=>[


                'device_id'=>$device->id,


                'unread_count'=>$unreadCount,


                'notifications'=>$notifications


            ]


        ]);



    }



}