<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionViolationLog;

use Illuminate\Http\JsonResponse;



class ProtectionViolationController extends Controller
{


    /**
     * Get device protection violations
     */
    public function index(
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
        | Violation History
        |--------------------------------------------------------------------------
        */


        $violations = ProtectionViolationLog::where(
                'device_id',
                $device->id
            )
            ->latest('id')
            ->paginate(50);



        return response()->json([


            'success'=>true,


            'message'=>'Protection violations retrieved.',


            'data'=>[

                'device_id'=>$device->id,

                'violations'=>$violations

            ]


        ]);


    }


}