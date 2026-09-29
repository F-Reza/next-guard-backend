<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\ProtectionViolationLog;

use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;



class ProtectionViolationController extends Controller
{


    /**
     * Get device protection violations
     */
    public function index(
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
        | Filters
        |--------------------------------------------------------------------------
        */


        $query = ProtectionViolationLog::where(
            'device_id',
            $device->id
        );




        if($request->filled('category')){

            $query->where(
                'category',
                $request->category
            );

        }



        if($request->filled('action')){

            $query->where(
                'action',
                $request->action
            );

        }




        if(
            $request->filled('from')
            &&
            $request->filled('to')
        ){

            $query->whereBetween(
                'created_at',
                [
                    $request->from,
                    $request->to
                ]
            );

        }




        /*
        |--------------------------------------------------------------------------
        | Violation History
        |--------------------------------------------------------------------------
        */


        $violations = $query

            ->latest('id')

            ->paginate(50)

            ->through(function($log){

                return [

                    'id'=>$log->id,

                    'domain'=>$log->domain,

                    'category'=>$log->category,

                    'action'=>$log->action,

                    'created_at'=>$log->created_at,

                ];

            });





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