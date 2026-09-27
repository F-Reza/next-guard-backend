<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionSyncLog;

use App\Services\ProtectionRetryService;

use Illuminate\Http\JsonResponse;



class ProtectionRetryController extends Controller
{


    public function retry(
        int $id
    ): JsonResponse
    {


        $log = ProtectionSyncLog::find($id);



        if(!$log){


            return response()->json([

                'success'=>false,

                'message'=>'Sync record not found.'

            ],404);


        }





        $result =
            ProtectionRetryService::retry(
                $log
            );





        return response()->json([


            'success'=>true,


            'message'=>
                $result['retry']
                ?
                'Protection retry queued.'
                :
                $result['message'],



            'data'=>$result


        ]);



    }



}