<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use App\Services\ProtectionRetryService;

use Illuminate\Http\JsonResponse;



class ProtectionRetryController extends Controller
{


    /**
     * Retry failed protection sync
     */
    public function retry(
        int $device,
        int $id
    ): JsonResponse
    {


        $user = auth('api')->user();





        /*
        |--------------------------------------------------------------------------
        | Find Sync Record
        |--------------------------------------------------------------------------
        */


        $log = ProtectionSyncLog::where(

                'id',

                $id

            )
            ->where(

                'device_id',

                $device

            )
            ->first();





        if(!$log){


            return response()->json([


                'success'=>false,


                'message'=>'Sync record not found.'


            ],404);


        }





        /*
        |--------------------------------------------------------------------------
        | Verify Device Ownership
        |--------------------------------------------------------------------------
        */


        $ownedDevice = $user->devices()

            ->where(

                'id',

                $device

            )

            ->first();





        if(!$ownedDevice){


            return response()->json([


                'success'=>false,


                'message'=>'Device not found.'

            ],404);


        }







        /*
        |--------------------------------------------------------------------------
        | Retry Sync
        |--------------------------------------------------------------------------
        */


        $result =

            ProtectionRetryService::retry(

                $log

            );








        return response()->json([



            'success'=>

                $result['retry'],



            'message'=>

                $result['retry']

                ?

                'Protection retry queued.'

                :

                $result['message'],



            'data'=>$result



        ],

        $result['retry']

            ?

            200

            :

            422

        );



    }



}