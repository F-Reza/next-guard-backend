<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\ProtectionEngineService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;



class ProtectionCheckController extends Controller
{


    public function check(
        Request $request,
        int $id
    ): JsonResponse
    {


        $request->validate([

            'domain'=>[
                'required',
                'string'
            ]

        ]);



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





        $result =
            ProtectionEngineService::checkDomain(

                $device,

                $request->domain

            );





        return response()->json([


            'success'=>true,


            'data'=>$result


        ]);



    }


}