<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Services\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SubscriptionPlanController extends Controller
{


    /**
     * Get active subscription plans.
     */
    public function index(): JsonResponse
    {

        $plans = SubscriptionPlan::where(
            'status',
            'active'
        )
        ->get();



        return response()->json([

            'success'=>true,

            'message'=>'Subscription plans retrieved.',

            'data'=>[

                'plans'=>$plans

            ]

        ]);

    }







    /**
     * Create subscription plan.
     */
    public function store(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(), [


            'name'=>[
                'required',
                'string',
                'max:100'
            ],


            'description'=>[
                'nullable',
                'string'
            ],


            'price'=>[
                'required',
                'numeric'
            ],


            'currency'=>[
                'nullable',
                'string',
                'max:10'
            ],


            'duration_days'=>[
                'required',
                'integer',
                'min:1'
            ],


            'features'=>[
                'nullable',
                'array'
            ],


        ]);




        if($validator->fails()){


            return response()->json([


                'success'=>false,


                'message'=>'Validation failed.',


                'errors'=>$validator->errors()


            ],422);


        }







        $plan = SubscriptionPlan::create([


            'name'=>$request->name,


            'description'=>$request->description,


            'price'=>$request->price,


            'currency'=>$request->currency ?? 'BDT',


            'duration_days'=>$request->duration_days,


            'status'=>'active',


            'features'=>$request->features,


        ]);







        /*
        |--------------------------------------------------------------------------
        | Admin Activity Log
        |--------------------------------------------------------------------------
        */


        AdminActivityLogger::log(


            'PLAN_CREATED',


            'Created subscription plan: '.$plan->name,


            $request


        );








        return response()->json([


            'success'=>true,


            'message'=>'Subscription plan created.',


            'data'=>[

                'plan'=>$plan

            ]


        ],201);


    }



}