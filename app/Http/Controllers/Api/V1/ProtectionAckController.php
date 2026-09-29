<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\ProtectionSyncLog;
use App\Models\DeviceProtectionSetting;
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


            'sync_version'=>[
                'required',
                'integer'
            ],


            'rules_hash'=>[
                'required',
                'string',
                'size:64'
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

        $alreadyApplied = ProtectionSyncLog::where(
            'device_id',
            $device->id
        )
        ->where(
            'sync_version',
            $request->sync_version
        )
        ->where(
            'rules_hash',
            $request->rules_hash
        )
        ->where(
            'apply_status',
            'applied'
        )
        ->first();


        if($alreadyApplied){

            return response()->json([

                'success'=>true,

                'message'=>'Protection already acknowledged.',

                'data'=>[

                    'device_id'=>$device->id,

                    'sync_version'=>$alreadyApplied->sync_version,

                    'status'=>$alreadyApplied->apply_status,

                    'synced_at'=>$alreadyApplied->synced_at,

                ]

            ]);

        }



        $sync = ProtectionSyncLog::where(

            'device_id',

            $device->id

        )
        ->where(

            'sync_version',

            $request->sync_version

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


        DB::transaction(function() use(
            $sync,
            $request,
            $device
        ){

            $sync->update([

                'apply_status'=>$request->status,

                'applied_at'=>
                    $request->status === 'applied'
                        ? now()
                        : null,

                'synced_at'=>
                    $request->status === 'applied'
                        ? now()
                        : null,

                'device_version'=>
                    $request->device_version
                    ??
                    $sync->device_version,

                'failure_reason'=>
                    $request->status === 'failed'
                        ? $request->failure_reason
                        : null,

                'retry_count'=>
                    $request->status === 'failed'
                        ? $sync->retry_count + 1
                        : $sync->retry_count,

            ]);



            if(
                $request->status === 'applied'
            ){

                DeviceProtectionSetting::where(
                    'device_id',
                    $device->id
                )
                ->update([

                    'last_sync_at'=>now(),

                ]);

            }

        });





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