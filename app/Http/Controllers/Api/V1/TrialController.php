<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;

use App\Services\TrialService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class TrialController extends Controller
{



    /**
     * Eligibility
     */
    public function eligibility(
        Request $request
    ): JsonResponse
    {


        $user = auth('api')->user();



        $request->validate([

            'device_id'=>'required|integer'

        ]);




        $device =
            Device::where(
                'id',
                $request->device_id
            )
            ->where(
                'user_id',
                $user->id
            )
            ->first();



        if(!$device){


            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);


        }





        return response()->json([


            'success'=>true,


            'message'=>'Trial eligibility checked.',


            'data'=>
                TrialService::eligibility(
                    $user,
                    $device
                )



        ]);



    }








    /**
     * Start Trial
     */
    public function start(
        Request $request
    ): JsonResponse
    {


        $user = auth('api')->user();



        $request->validate([

            'device_id'=>'required|integer'

        ]);




        $device =
            Device::where(
                'id',
                $request->device_id
            )
            ->where(
                'user_id',
                $user->id
            )
            ->first();



        if(!$device){


            return response()->json([


                'success'=>false,


                'message'=>'Device not found.'


            ],404);


        }






        $result =
            TrialService::start(
                $user,
                $device
            );





        if(!$result['created']){


            return response()->json([


                'success'=>false,


                'message'=>
                'Trial has already been started.',


                'data'=>[

                    'trial'=>$result['trial']

                ]


            ],409);



        }





        return response()->json([


            'success'=>true,


            'message'=>'Trial started successfully.',


            'data'=>[


                'trial'=>$result['trial']


            ]


        ],201);



    }









    /**
     * Current Trial
     */
    public function show(
        Request $request
    ): JsonResponse
    {


        $user = auth('api')->user();




        $request->validate([

            'device_id'=>'required|integer'

        ]);




        $device =
            Device::where(
                'id',
                $request->device_id
            )
            ->where(
                'user_id',
                $user->id
            )
            ->first();



        if(!$device){


            return response()->json([


                'success'=>false,


                'message'=>'Device not found.'


            ],404);


        }







        return response()->json([


            'success'=>true,


            'message'=>'Trial retrieved successfully.',


            'data'=>[


                'trial'=>
                TrialService::current(
                    $user,
                    $device
                )


            ]



        ]);



    }



}