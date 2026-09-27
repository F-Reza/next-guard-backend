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
            ->where('id',$id)
            ->first();



        if(!$device){

            return response()->json([

                'success'=>false,

                'message'=>'Device not found.'

            ],404);

        }





        /*
        |--------------------------------------------------------------------------
        | Generate Payload
        |--------------------------------------------------------------------------
        */


        $payload = ProtectionEngineService::payload(
            $device
        );





        /*
        |--------------------------------------------------------------------------
        | Stable Hash
        |--------------------------------------------------------------------------
        */


        $rules = collect($payload['rules'])
            ->sortBy('id')
            ->values()
            ->toArray();



        $rulesHash = hash(
            'sha256',
            json_encode($rules)
        );







        /*
        |--------------------------------------------------------------------------
        | Existing Sync Check
        |--------------------------------------------------------------------------
        */


        $existing = ProtectionSyncLog::where(
            'device_id',
            $device->id
        )
        ->where(
            'rules_hash',
            $rulesHash
        )
        ->latest('id')
        ->first();







        /*
        |--------------------------------------------------------------------------
        | Already Applied
        |--------------------------------------------------------------------------
        */


        if(
            $existing &&
            $existing->apply_status === 'applied'
        ){


            return response()->json([


                'success'=>true,


                'message'=>'Protection already applied.',



                'data'=>[


                    'device_id'=>$device->id,


                    'changed'=>false,


                    'sync_version'=>$existing->sync_version,


                    'rules_hash'=>$rulesHash,


                    'synced_at'=>$existing->synced_at,


                ]



            ]);

        }







        /*
        |--------------------------------------------------------------------------
        | Pending Sync Exists
        |--------------------------------------------------------------------------
        */


        if(
            $existing &&
            $existing->apply_status === 'pending'
        ){



            return response()->json([


                'success'=>true,


                'message'=>'Protection update pending.',



                'data'=>[


                    'device_id'=>$device->id,


                    'changed'=>true,


                    'sync_version'=>$existing->sync_version,


                    'rules_hash'=>$rulesHash,


                    'protection'=>$payload['protection'],


                    'rules'=>$rules,


                    'synced_at'=>$existing->synced_at,


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


                'ip_address'=>$request->ip(),


                'user_agent'=>$request->userAgent(),


                'synced_at'=>now(),


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