<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class ProtectionAckController extends Controller
{


    /**
     * Device acknowledgement
     */
    public function acknowledge(
        Request $request,
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



        /*
        |--------------------------------------------------------------------------
        | Verify Device
        |--------------------------------------------------------------------------
        */


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
        | Validation
        |--------------------------------------------------------------------------
        */


        $request->validate([


            'rules_hash'=>[
                'required',
                'string'
            ],


            'status'=>[
                'required',
                'in:applied,failed'
            ],


            'device_version'=>[
                'nullable',
                'string',
                'max:50'
            ],


            'failure_reason'=>[
                'nullable',
                'string'
            ]


        ]);







        /*
        |--------------------------------------------------------------------------
        | Latest Pending Sync
        |--------------------------------------------------------------------------
        */


        $sync = ProtectionSyncLog::where(

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
        ->latest('id')
        ->first();






        if(!$sync){


            return response()->json([


                'success'=>false,


                'message'=>'Pending sync record not found.'


            ],404);


        }







        /*
        |--------------------------------------------------------------------------
        | Update ACK
        |--------------------------------------------------------------------------
        */


        $sync->update([


            'apply_status'=>
                $request->status,



            'applied_at'=>
                $request->status === 'applied'
                    ? now()
                    : null,


            'synced_at'=>
                $request->status === 'applied'
                    ? now()
                    : null,


            'device_version'=>
                $request->device_version,



            'failure_reason'=>
                $request->failure_reason,



            'retry_count'=>
                $request->status === 'failed'
                    ? $sync->retry_count + 1
                    : $sync->retry_count,


        ]);







        return response()->json([


            'success'=>true,


            'message'=>
                $request->status === 'applied'
                    ? 'Protection applied successfully.'
                    : 'Protection apply failed.',



            'data'=>[


                'device_id'=>$device->id,


                'rules_hash'=>$sync->rules_hash,


                'status'=>$sync->apply_status,


                'retry_count'=>$sync->retry_count,


                'failure_reason'=>$sync->failure_reason,


                'applied_at'=>$sync->applied_at,


                'synced_at'=>$sync->synced_at,


            ]


        ]);



    }


}