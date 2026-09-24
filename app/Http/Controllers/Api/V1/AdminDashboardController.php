<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;


class AdminDashboardController extends Controller
{


    public function index(): JsonResponse
    {

        $admin = auth('admin')->user();


        return response()->json([

            'success'=>true,

            'message'=>'Admin dashboard data.',

            'data'=>[

                'admin'=>[

                    'id'=>$admin->id,

                    'name'=>$admin->name,

                    'role'=>$admin->role,

                ]

            ]

        ]);

    }


}