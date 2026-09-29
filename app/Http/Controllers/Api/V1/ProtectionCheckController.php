<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Services\ProtectionEngineService;

use App\Models\ProtectionViolationLog;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class ProtectionCheckController extends Controller
{


    /**
     * Check domain protection
     */
    public function check(
        Request $request,
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
        | Validation
        |--------------------------------------------------------------------------
        */


        $request->validate([


            'domain'=>[

                'required',

                'string',

                'max:255'

            ]


        ]);







        /*
        |--------------------------------------------------------------------------
        | Protection Decision
        |--------------------------------------------------------------------------
        */


        $result = ProtectionEngineService::checkDomain(

            $device,

            $request->domain

        );









        /*
        |--------------------------------------------------------------------------
        | Store Violation
        |--------------------------------------------------------------------------
        */

        if(
            isset($result['allowed'])
            &&
            $result['allowed'] === false
        ){

            ProtectionViolationLog::create([

                'device_id'=>$device->id,

                'domain'=>$result['domain'],

                'action'=>'blocked',

                'rule_id'=>$result['rule_id'] ?? null,

                'category'=>$result['category'] ?? null,

            ]);

        }









        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */


        return response()->json([


            'success'=>true,


            'data'=>[


                'device_id'=>$device->id,


                ...$result


            ]



        ]);



    }



}