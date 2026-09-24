<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\ProtectionRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProtectionRuleController extends Controller
{


    /**
     * Get all active protection rules.
     */
    public function index(): JsonResponse
    {

        $rules = ProtectionRule::where('status','active')
            ->get();


        return response()->json([

            'success'=>true,

            'message'=>'Protection rules retrieved.',

            'data'=>[
                'rules'=>$rules
            ]

        ]);

    }



    /**
     * Create protection rule.
     *
     * Later this will move under admin middleware.
     */
    public function store(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(),[

            'category'=>[
                'required',
                'string',
                'max:50'
            ],

            'domain'=>[
                'required',
                'string',
                'max:255'
            ],

            'rule_type'=>[
                'nullable',
                'string',
                'max:30'
            ],

            'description'=>[
                'nullable',
                'string'
            ],

        ]);


        if($validator->fails()){

            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);

        }



        $rule = ProtectionRule::create([

            'category'=>$request->category,

            'domain'=>$request->domain,

            'rule_type'=>$request->rule_type ?? 'domain',

            'status'=>'active',

            'description'=>$request->description,

        ]);



        return response()->json([

            'success'=>true,

            'message'=>'Protection rule created.',

            'data'=>[
                'rule'=>$rule
            ]

        ],201);

    }




    /**
     * Sync rules for a device.
     */
    public function deviceRules(int $id): JsonResponse
    {

        $user = auth('api')->user();


        $device = $user->devices()
            ->where('id',$id)
            ->first();



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }



        $rules = ProtectionRule::where('status','active')
            ->get()
            ->groupBy('category');



        return response()->json([

            'success'=>true,

            'message'=>'Device protection rules synced.',

            'data'=>[
                'device_id'=>$device->id,
                'rules'=>$rules
            ]

        ]);

    }


}