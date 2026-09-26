<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\JsonResponse;


class AdminPermissionController extends Controller
{


    /**
     * List all permissions
     */
    public function index(): JsonResponse
    {


        $permissions = Permission::latest()
            ->get();



        return response()->json([

            'success'=>true,

            'message'=>'Permissions retrieved.',

            'data'=>[

                'permissions'=>$permissions

            ]

        ]);


    }



}