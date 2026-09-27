<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;

use App\Services\ProtectionService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\Validator;



class DeviceProtectionController extends Controller
{





    /**
     * Get protection settings
     */
    public function show(
        int $id
    ): JsonResponse
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



        $settings =
            ProtectionService::get(
                $device
            );




        return response()->json([


            'success'=>true,


            'message'=>'Protection settings retrieved.',


            'data'=>[

                'device_id'=>$device->id,

                'settings'=>$settings

            ]


        ]);



    }









    /**
     * Update protection
     */
    public function update(
        Request $request,
        int $id
    ): JsonResponse
    {


        $validator = Validator::make(
            $request->all(),
            [

                'betting_block'=>'boolean',

                'adult_content_block'=>'boolean',

                'facebook_ad_block'=>'boolean',

                'youtube_ad_block'=>'boolean',

                'safe_search'=>'boolean',

                'dns_protection'=>'boolean',

            ]
        );




        if($validator->fails()){


            return response()->json([


                'success'=>false,


                'message'=>'Validation failed.',


                'errors'=>$validator->errors()


            ],422);



        }





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





        $settings =
            ProtectionService::update(

                $device,

                $request->only([

                    'betting_block',

                    'adult_content_block',

                    'facebook_ad_block',

                    'youtube_ad_block',

                    'safe_search',

                    'dns_protection',

                ])

            );





        return response()->json([


            'success'=>true,


            'message'=>'Protection settings updated.',


            'data'=>[

                'settings'=>$settings

            ]


        ]);



    }









    /**
     * Android sync
     */
    public function sync(
        int $id
    ): JsonResponse
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





        $settings =
            ProtectionService::sync(
                $device
            );






        return response()->json([


            'success'=>true,


            'message'=>'Protection sync completed.',


            'data'=>[

                'device_id'=>$device->id,

                'status'=>$settings->protection_status,

                'last_sync_at'=>$settings->last_sync_at

            ]

        ]);



    }





}