<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Services\DeviceLimitService;



class DeviceLimitController extends Controller
{


    /**
     * Device limit information
     */
    public function status()
    {


        $user = auth('api')->user();



        return response()->json([


            'success'=>true,


            'message'=>'Device limit information retrieved.',


            'data'=>DeviceLimitService::info(
                $user
            )



        ]);



    }



}