<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use Illuminate\Http\JsonResponse;


class AdminActivityLogController extends Controller
{


    /**
     * Admin activity logs
     */
    public function index(): JsonResponse
    {


        $logs = AdminActivityLog::with('admin')
            ->latest()
            ->get()
            ->map(function($log){

                return [

                    'id'=>$log->id,

                    'admin'=>$log->admin?->name,

                    'action'=>$log->action,

                    'description'=>$log->description,

                    'ip_address'=>$log->ip_address,

                    'created_at'=>$log->created_at,

                ];

            });



        return response()->json([

            'success'=>true,

            'message'=>'Admin activity logs retrieved.',

            'data'=>[

                'logs'=>$logs

            ]

        ]);

    }


}