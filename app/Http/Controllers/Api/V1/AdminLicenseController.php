<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LicenseCode;
use App\Models\SubscriptionPlan;
use App\Services\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class AdminLicenseController extends Controller
{


    public function generate(Request $request): JsonResponse
    {


        $count = $request->input(
            'quantity',
            1
        );


        $plan = SubscriptionPlan::find(
            $request->plan_id
        );


        if(!$plan){

            return response()->json([

                'success'=>false,

                'message'=>'Plan not found.'

            ],404);

        }



        $codes = [];



        for($i=0;$i<$count;$i++){


        $license = LicenseCode::create([

            'subscription_plan_id'=>$plan->id,

            'code'=>'NG-'.
                strtoupper($plan->name).
                '-'.
                strtoupper(
                    str()->random(8)
                ),

            'duration_days'=>$plan->duration_days,

            'status'=>'active',

        ]);



            $codes[] = [

                'id'=>$license->id,

                'code'=>$license->code

            ];


        }





        AdminActivityLogger::log(

            'LICENSE_GENERATED',

            'Generated '.$count.' license codes for '.$plan->name.' plan.',

            $request

        );





        return response()->json([

            'success'=>true,

            'message'=>'License codes generated successfully.',

            'data'=>[

                'codes'=>$codes

            ]

        ]);


    }




    public function index(): JsonResponse
    {

        $licenses = LicenseCode::latest()->get();


        return response()->json([

            'success'=>true,

            'message'=>'License codes retrieved.',

            'data'=>[

                'licenses'=>$licenses

            ]

        ]);

    }


}