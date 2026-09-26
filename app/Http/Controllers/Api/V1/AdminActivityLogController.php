<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\AdminActivityLog;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;



class AdminActivityLogController extends Controller
{


    /**
     * Get Activity Logs
     */
    public function index(Request $request): JsonResponse
    {


        $query = AdminActivityLog::with('admin:id,name,email,role');





        /*
        |--------------------------------------------------------------------------
        | Search Admin
        |--------------------------------------------------------------------------
        */


        if($request->filled('search')){


            $search = $request->search;


            $query->whereHas('admin', function($q) use ($search){


                $q->where('name','like',"%{$search}%")
                  ->orWhere('email','like',"%{$search}%");


            });


        }







        /*
        |--------------------------------------------------------------------------
        | Filter Action
        |--------------------------------------------------------------------------
        */


        if($request->filled('action')){


            $query->where(
                'action',
                $request->action
            );


        }








        /*
        |--------------------------------------------------------------------------
        | Filter Severity
        |--------------------------------------------------------------------------
        */


        if($request->filled('severity')){


            $query->where(
                'severity',
                $request->severity
            );


        }









        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */


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










        $logs = $query

            ->latest()

            ->paginate(
                $request->get('per_page',20)
            );









        return response()->json([


            'success'=>true,


            'message'=>'Admin activity logs retrieved.',



            'data'=>$logs



        ]);



    }







    /**
     * Security Events Only
     */
    public function security(): JsonResponse
    {


        $logs = AdminActivityLog::with(
            'admin:id,name,email'
        )

        ->whereIn(

            'severity',

            [

                'warning',

                'critical'

            ]

        )

        ->latest()

        ->paginate(20);






        return response()->json([


            'success'=>true,


            'message'=>'Security activity logs retrieved.',


            'data'=>$logs



        ]);



    }



}