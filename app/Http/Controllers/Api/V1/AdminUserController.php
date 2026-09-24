<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;


class AdminUserController extends Controller
{


    /**
     * List all users
     */
    public function index(): JsonResponse
    {

        $users = User::latest()
            ->get()
            ->map(function($user){

                return [

                    'id'=>$user->id,

                    'name'=>$user->name,

                    'email'=>$user->email,

                    'phone'=>$user->phone,

                    'status'=>$user->status,

                    'created_at'=>$user->created_at,

                ];

            });



        return response()->json([

            'success'=>true,

            'message'=>'Users retrieved successfully.',

            'data'=>[

                'users'=>$users

            ]

        ]);

    }





    /**
     * Single user
     */
    public function show(int $id): JsonResponse
    {

        $user = User::find($id);



        if(!$user){

            return response()->json([

                'success'=>false,

                'message'=>'User not found.'

            ],404);

        }



        return response()->json([

            'success'=>true,

            'message'=>'User retrieved successfully.',

            'data'=>[

                'user'=>$user

            ]

        ]);

    }


}