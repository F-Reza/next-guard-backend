<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionRule;

use App\Services\AdminActivityLogger;
use App\Services\ProtectionRuleService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class ProtectionRuleController extends Controller
{


    /**
     * Admin: Get protection rules
     */
    public function index(
        Request $request
    ): JsonResponse
    {


        $rules = ProtectionRuleService::list(
            $request->device_id
        );



        return response()->json([

            'success'=>true,

            'message'=>'Protection rules retrieved.',

            'data'=>[

                'rules'=>$rules

            ]

        ]);

    }







    /**
     * Admin: Create protection rule
     */
    public function store(
        Request $request
    ): JsonResponse
    {


        $validator = Validator::make(

            $request->all(),

            [

                'device_id'=>[

                    'nullable',

                    'integer',

                    'exists:devices,id'

                ],



                'category'=>[

                    'required',

                    'string',

                    'max:100'

                ],



                'domain'=>[

                    'required',

                    'string',

                    'max:255'

                ],



                'rule_type'=>[

                    'required',

                    'string',

                    'max:50'

                ],



                'status'=>[

                    'nullable',

                    'in:active,inactive'

                ],



                'description'=>[

                    'nullable',

                    'string'

                ]

            ]

        );





        if($validator->fails()){


            return response()->json([


                'success'=>false,


                'message'=>'Validation failed.',


                'errors'=>$validator->errors()


            ],422);


        }






        $rule = ProtectionRuleService::create(

            $request->all()

        );







        AdminActivityLogger::log(


            'PROTECTION_RULE_CREATED',


            'Created protection rule: '.$rule->domain,


            $request


        );







        return response()->json([


            'success'=>true,


            'message'=>'Protection rule created successfully.',


            'data'=>[

                'rule'=>$rule

            ]


        ],201);


    }









    /**
     * User Device: Sync protection rules
     */
    public function deviceRules(
        int $id
    ): JsonResponse
    {


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







        /*
        |--------------------------------------------------------------------------
        | Global Rules + Device Specific Rules
        |--------------------------------------------------------------------------
        */



        $rules = ProtectionRule::where(


            function($query) use($device){


                $query

                ->whereNull(

                    'device_id'

                )

                ->orWhere(

                    'device_id',

                    $device->id

                );


            }


        )

        ->where(

            'status',

            'active'

        )

        ->latest()

        ->get()

        ->groupBy(

            'category'

        );








        return response()->json([



            'success'=>true,



            'message'=>'Device protection rules synced.',



            'data'=>[



                'device_id'=>$device->id,



                'rules'=>$rules



            ]



        ]);



    }









    /**
     * Admin: Delete protection rule
     */
    public function destroy(
        int $id
    ): JsonResponse
    {


        $rule = ProtectionRule::find($id);






        if(!$rule){


            return response()->json([


                'success'=>false,


                'message'=>'Protection rule not found.'


            ],404);


        }







        $domain = $rule->domain;






        ProtectionRuleService::delete(

            $rule

        );







        AdminActivityLogger::log(


            'PROTECTION_RULE_DELETED',


            'Deleted protection rule: '.$domain,


            request()


        );







        return response()->json([


            'success'=>true,


            'message'=>'Protection rule deleted successfully.'


        ]);



    }





}