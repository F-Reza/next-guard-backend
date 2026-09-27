<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use App\Services\ProtectionEngineService;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



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
        | Generate Current Protection Payload
        |--------------------------------------------------------------------------
        */


        $payload = ProtectionEngineService::payload(
            $device
        );





        /*
        |--------------------------------------------------------------------------
        | Generate Rules Hash
        |--------------------------------------------------------------------------
        */


        $rulesHash = hash(
            'sha256',
            json_encode($payload['rules'])
        );





        /*
        |--------------------------------------------------------------------------
        | Client Existing Hash
        |--------------------------------------------------------------------------
        */


        $clientHash = $request->input(
            'last_rules_hash'
        );






        /*
        |--------------------------------------------------------------------------
        | No Changes
        |--------------------------------------------------------------------------
        */


        if(
            $clientHash &&
            hash_equals(
                $rulesHash,
                $clientHash
            )
        ){


            $this->createSyncLog(
                $device->id,
                $rulesHash,
                $request
            );



            return response()->json([


                'success'=>true,


                'message'=>'Protection already up to date.',



                'data'=>[


                    'device_id'=>$device->id,


                    'changed'=>false,


                    'sync_version'=>1,


                    'rules_hash'=>$rulesHash,


                    'synced_at'=>now()



                ]



            ]);

        }






        /*
        |--------------------------------------------------------------------------
        | Changed / First Sync
        |--------------------------------------------------------------------------
        */


        $this->createSyncLog(

            $device->id,

            $rulesHash,

            $request

        );







        return response()->json([


            'success'=>true,


            'message'=>'Protection updated.',



            'data'=>[


                'device_id'=>$device->id,


                'changed'=>true,


                'sync_version'=>1,


                'rules_hash'=>$rulesHash,


                'protection'=>$payload['protection'],


                'rules'=>$payload['rules'],


                'synced_at'=>now()



            ]



        ]);



    }








    /**
     * Save sync history
     */
    private function createSyncLog(

        int $deviceId,

        string $rulesHash,

        Request $request

    ): void
    {


        ProtectionSyncLog::create([


            'device_id'=>$deviceId,


            'sync_version'=>1,


            'rules_hash'=>$rulesHash,


            'ip_address'=>$request->ip(),


            'user_agent'=>$request->userAgent(),


            'synced_at'=>now(),



        ]);


    }





}