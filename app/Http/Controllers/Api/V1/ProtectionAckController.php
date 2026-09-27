<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class ProtectionAckController extends Controller
{


    public function acknowledge(
        Request $request,
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





        $request->validate([

            'rules_hash'=>[
                'required',
                'string',
            ],


            'status'=>[
                'required',
                'in:applied,failed',
            ],


            'device_version'=>[
                'nullable',
                'string',
                'max:50'
            ]

        ]);




        $log = ProtectionSyncLog::where(
            'device_id',
            $device->id
        )
        ->where(
            'rules_hash',
            $request->rules_hash
        )
        ->where(
            'apply_status',
            'pending'
        )
        ->latest()
        ->first();




        if(!$log){

            return response()->json([

                'success'=>false,

                'message'=>'Sync record not found.'

            ],404);

        }







        $log->update([


            'apply_status'=>$request->status,


            'applied_at'=>
                $request->status === 'applied'
                    ? now()
                    : null,


            'device_version'=>
                $request->device_version,


        ]);







        return response()->json([


            'success'=>true,


            'message'=>'Protection acknowledgement received.',


            'data'=>[

                'device_id'=>$device->id,

                'rules_hash'=>$log->rules_hash,

                'status'=>$log->apply_status,

                'applied_at'=>$log->applied_at,

            ]



        ]);



    }


}