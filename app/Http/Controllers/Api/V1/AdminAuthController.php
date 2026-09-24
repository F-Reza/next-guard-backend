<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;


class AdminAuthController extends Controller
{


    /**
     * Admin Login
     */
    public function login(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(), [

            'email' => [

                'required',

                'email'

            ],

            'password' => [

                'required',

                'string'

            ]

        ]);



        if ($validator->fails()) {


            return response()->json([

                'success' => false,

                'message' => 'Validation failed.',

                'errors' => $validator->errors()

            ],422);


        }




        $admin = Admin::where(

            'email',

            $request->email

        )->first();





        if (

            !$admin ||

            !Hash::check(

                $request->password,

                $admin->password

            )

        ) {


            return response()->json([

                'success' => false,

                'message' => 'Invalid admin credentials.'

            ],401);


        }





        if ($admin->status !== 'active') {


            return response()->json([

                'success' => false,

                'message' => 'Admin account inactive.'

            ],403);


        }





        /*
        |--------------------------------------------------------------------------
        | Generate Admin JWT Token
        |--------------------------------------------------------------------------
        */


        $token = auth('admin')->login($admin);





        /*
        |--------------------------------------------------------------------------
        | Update Last Login
        |--------------------------------------------------------------------------
        */


        $admin->update([

            'last_login_at' => now()

        ]);







        return response()->json([

            'success' => true,

            'message' => 'Admin login successful.',


            'data' => [


                'admin' => [


                    'id' => $admin->id,


                    'name' => $admin->name,


                    'email' => $admin->email,


                    'role' => $admin->role,


                    'status' => $admin->status,


                ],



                'access_token' => $token,


                'token_type' => 'Bearer'


            ]

        ]);

    }





    /**
     * Admin Logout
     */
    public function logout(): JsonResponse
    {


        auth('admin')->logout();



        return response()->json([

            'success'=>true,

            'message'=>'Admin logout successful.'

        ]);

    }





    /**
     * Current Admin
     */
    public function me(): JsonResponse
    {


        $admin = auth('admin')->user();



        return response()->json([

            'success'=>true,

            'message'=>'Authenticated admin.',


            'data'=>[

                'admin'=>[

                    'id'=>$admin->id,

                    'name'=>$admin->name,

                    'email'=>$admin->email,

                    'role'=>$admin->role,

                    'status'=>$admin->status,

                ]

            ]

        ]);

    }



}