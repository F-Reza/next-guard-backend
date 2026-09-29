<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use App\Services\ProtectionEngineService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;

use Illuminate\Support\Facades\DB;



class ProtectionSyncController extends Controller
{


    /**
     * Sync protection configuration
     */
    public function sync(
        Request $request,
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();



        /*
        |--------------------------------------------------------------------------
        | Verify Device Ownership
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
        | Generate Protection Payload
        |--------------------------------------------------------------------------
        */


        $payload = ProtectionEngineService::payload(
            $device
        );





        /*
        |--------------------------------------------------------------------------
        | Stable Rules Hash
        |--------------------------------------------------------------------------
        */


        $rules = collect($payload['rules'])
            ->sortBy('id')
            ->values()
            ->toArray();



        $syncData = [

            'protection'=>$payload['protection'],

            'rules'=>$rules,

        ];



        $rulesHash = hash(
            'sha256',
            json_encode($syncData)
        );






        /*
        |--------------------------------------------------------------------------
        | Latest Sync State
        |--------------------------------------------------------------------------
        */


        $latest = ProtectionSyncLog::where(
            'device_id',
            $device->id
        )
        ->orderByDesc('sync_version')
        ->first();




        /*
        |--------------------------------------------------------------------------
        | Pending
        |--------------------------------------------------------------------------
        */


        if(
            $latest &&
            $latest->apply_status === 'pending'
        ){

            return response()->json([


                'success'=>true,


                'message'=>'Protection update pending.',



                'data'=>[


                    'device_id'=>$device->id,


                    'changed'=>true,


                    'sync_version'=>$latest->sync_version,


                    'rules_hash'=>$latest->rules_hash,


                    'protection'=>$payload['protection'],


                    'rules'=>$rules,


                    'synced_at'=>$latest->synced_at,


                ]



            ]);

        }








        /*
        |--------------------------------------------------------------------------
        | Failed
        |--------------------------------------------------------------------------
        */


        if(
            $latest &&
            $latest->apply_status === 'failed' &&
            $latest->rules_hash === $rulesHash
        ){

            return response()->json([

                'success'=>true,

                'message'=>'Protection sync failed. Retry required.',

                'data'=>[

                    'device_id'=>$device->id,

                    'sync_version'=>$latest->sync_version,

                    'rules_hash'=>$latest->rules_hash,

                    'retry_count'=>$latest->retry_count,

                    'max_retry'=>$latest->max_retry,

                    'retry_available'=>
                        $latest->retry_count < $latest->max_retry,

                    'failure_reason'=>$latest->failure_reason,

                ]

            ]);

        }








        /*
        |--------------------------------------------------------------------------
        | Already Applied With Same Rules
        |--------------------------------------------------------------------------
        */


        if(
            $latest &&
            $latest->apply_status === 'applied' &&
            $latest->rules_hash === $rulesHash
        ){

            return response()->json([


                'success'=>true,


                'message'=>'Protection already applied.',



                'data'=>[


                    'device_id'=>$device->id,


                    'changed'=>false,


                    'sync_version'=>$latest->sync_version,


                    'rules_hash'=>$latest->rules_hash,


                    'synced_at'=>$latest->synced_at,


                ]



            ]);

        }








        /*
        |--------------------------------------------------------------------------
        | Create New Sync Version
        |--------------------------------------------------------------------------
        */


        $log = DB::transaction(function() use(

            $device,

            $rulesHash,

            $request

        ){


            $lastVersion = ProtectionSyncLog::where(

                    'device_id',

                    $device->id

                )
                ->lockForUpdate()
                ->max(
                    'sync_version'
                );




            return ProtectionSyncLog::create([


                'device_id'=>$device->id,


                'sync_version'=>
                    ($lastVersion ?? 0)+1,


                'rules_hash'=>$rulesHash,


                'apply_status'=>'pending',


                'retry_count'=>0,


                'max_retry'=>3,


                'ip_address'=>$request->ip(),


                'user_agent'=>$request->userAgent(),


                'synced_at'=>null,


            ]);



        });








        return response()->json([


            'success'=>true,


            'message'=>'Protection updated.',



            'data'=>[


                'device_id'=>$device->id,


                'changed'=>true,


                'sync_version'=>$log->sync_version,


                'rules_hash'=>$rulesHash,


                'protection'=>$payload['protection'],


                'rules'=>$rules,


                'synced_at'=>$log->synced_at,


            ]



        ]);



    }


}