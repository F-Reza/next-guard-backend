<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\LicenseCode;

use App\Services\LicenseService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class LicenseController extends Controller
{



    /**
     * Redeem License
     */
    public function redeem(
        Request $request
    ): JsonResponse
    {


        $request->validate([


            'code'=>[
                'required',
                'string'
            ],


            'device_id'=>[
                'required',
                'exists:devices,id'
            ]


        ]);




        $user = auth('api')->user();





        $license = LicenseCode::where(
            'code',
            $request->code
        )
        ->first();





        if(!$license){


            return response()->json([


                'success'=>false,


                'message'=>'Invalid license code.'


            ],404);


        }







        $device = $user
            ->devices()
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






        try{


            $subscription =
                LicenseService::redeem(


                    $user,


                    $license,


                    $device


                );



        }
        catch(\Exception $e){


            return response()->json([


                'success'=>false,


                'message'=>$e->getMessage()


            ],422);



        }







        return response()->json([


            'success'=>true,


            'message'=>
                'License activated successfully.',



            'data'=>[


                'subscription'=>
                    $subscription->load('plan')


            ]


        ]);



    }




}