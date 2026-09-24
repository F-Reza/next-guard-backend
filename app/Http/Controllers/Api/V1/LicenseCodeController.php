<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\LicenseCode;
use App\Models\SubscriptionPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LicenseCodeController extends Controller
{


    /**
     * Generate license codes.
     */
    public function generate(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(), [

            'plan_id' => [
                'required',
                'exists:subscription_plans,id'
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:1000'
            ]

        ]);



        if ($validator->fails()) {


            return response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $validator->errors()

            ],422);


        }



        $plan = SubscriptionPlan::find(
            $request->plan_id
        );



        $codes = [];



        for(
            $i = 0;
            $i < $request->quantity;
            $i++
        ){


            $code =
                'NG-'
                .
                strtoupper($plan->name)
                .
                '-'
                .
                strtoupper(Str::random(8));



            $license = LicenseCode::create([

                'code' => $code,

                'subscription_plan_id' => $plan->id,

                'duration_days' => $plan->duration_days,

                'status' => 'active',

            ]);



            $codes[] = [

                'id' => $license->id,

                'code' => $license->code,

                'plan' => $plan->name,

                'duration_days' =>
                    $license->duration_days,

                'status' =>
                    $license->status,

            ];

        }



        return response()->json([

            'success'=>true,

            'message'=>'License codes generated successfully.',

            'data'=>[

                'codes'=>$codes

            ]

        ],201);


    }

    public function redeem(Request $request): JsonResponse
    {

        $validator = Validator::make($request->all(), [

            'code' => [
                'required',
                'string'
            ],

            'device_id' => [
                'required',
                'exists:devices,id'
            ],

        ]);


        if ($validator->fails()) {

            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);

        }



        $user = auth('api')->user();



        $license = LicenseCode::where(
            'code',
            $request->code
        )->first();



        if (!$license) {

            return response()->json([

                'success'=>false,

                'message'=>'Invalid license code.'

            ],404);

        }



        if ($license->status !== 'active') {

            return response()->json([

                'success'=>false,

                'message'=>'License code already used or inactive.'

            ],409);

        }



        $device = $user->devices()
            ->where(
                'id',
                $request->device_id
            )
            ->first();



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }



        $subscription = \App\Models\Subscription::create([

            'user_id'=>$user->id,

            'subscription_plan_id'=>$license->subscription_plan_id,

            'device_id'=>$device->id,

            'starts_at'=>now(),

            'expires_at'=>now()->addDays(
                $license->duration_days
            ),

            'status'=>'active',

            'source'=>'license',

        ]);



        $license->update([

            'status'=>'used',

            'used_by'=>$user->id,

            'used_device_id'=>$device->id,

            'used_at'=>now(),

        ]);



        return response()->json([

            'success'=>true,

            'message'=>'License activated successfully.',

            'data'=>[

                'subscription'=>$subscription->load('plan')

            ]

        ]);

    }

}