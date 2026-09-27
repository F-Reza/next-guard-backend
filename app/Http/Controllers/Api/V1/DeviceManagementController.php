<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Device;
use App\Models\User;

use App\Services\DeviceLimitService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;



class DeviceManagementController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Find User Device
    |--------------------------------------------------------------------------
    */


    private function findDevice(
        int $id
    ): ?Device
    {

        return auth('api')
            ->user()
            ->devices()
            ->where(
                'id',
                $id
            )
            ->first();

    }






    /*
    |--------------------------------------------------------------------------
    | Rename Device
    |--------------------------------------------------------------------------
    */


    public function rename(
        Request $request,
        int $id
    ): JsonResponse
    {


        $request->validate([

            'name'=>[
                'required',
                'string',
                'max:100'
            ]

        ]);



        $device = $this->findDevice($id);



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }



        $device->update([

            'name'=>$request->name

        ]);




        return response()->json([

            'success'=>true,

            'message'=>'Device renamed.',

            'data'=>[

                'device_id'=>$device->id,

                'name'=>$device->name

            ]

        ]);

    }









    /*
    |--------------------------------------------------------------------------
    | Revoke Device
    |--------------------------------------------------------------------------
    */


    public function revoke(
        int $id
    ): JsonResponse
    {


        $device = $this->findDevice($id);



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }



        DB::transaction(function() use ($device){


            $device->update([

                'status'=>'revoked'

            ]);



            $device->deviceSessions()
                ->update([

                    'status'=>'revoked'

                ]);


        });





        return response()->json([

            'success'=>true,

            'message'=>'Device revoked.',


            'data'=>[

                'device_id'=>$device->id,

                'status'=>$device->status

            ]

        ]);

    }









    /*
    |--------------------------------------------------------------------------
    | Transfer Device
    |--------------------------------------------------------------------------
    */


    public function transfer(
        Request $request,
        int $id
    ): JsonResponse
    {


        $request->validate([

            'email'=>[

                'required',

                'email'

            ]

        ]);




        $device = $this->findDevice($id);





        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }





        $newOwner = User::where(
            'email',
            $request->email
        )
        ->first();






        if(!$newOwner){


            return response()->json([

                'success'=>false,

                'message'=>'New owner not found.'

            ],404);


        }







        /*
        |--------------------------------------------------------------------------
        | Same Owner Check
        |--------------------------------------------------------------------------
        */


        if(
            $device->user_id === $newOwner->id
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Device already belongs to this user.'

            ],400);

        }







        /*
        |--------------------------------------------------------------------------
        | Device Limit Check
        |--------------------------------------------------------------------------
        */


        if(
            !DeviceLimitService::canAdd(
                $newOwner
            )
        ){

            return response()->json([

                'success'=>false,

                'message'=>'New owner device limit reached.',


                'data'=>DeviceLimitService::info(
                    $newOwner
                )

            ],403);


        }








        /*
        |--------------------------------------------------------------------------
        | Transfer Transaction
        |--------------------------------------------------------------------------
        */


        DB::transaction(function() use(
            $device,
            $newOwner
        ){


            $device->update([

                'user_id'=>$newOwner->id,

                'status'=>'active'

            ]);




            // remove old sessions

            $device->deviceSessions()
                ->delete();



        });







        return response()->json([


            'success'=>true,


            'message'=>'Device transferred successfully.',



            'data'=>[


                'device_id'=>$device->id,


                'new_owner_id'=>$newOwner->id


            ]

        ]);



    }




}