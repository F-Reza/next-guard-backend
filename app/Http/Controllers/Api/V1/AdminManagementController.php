<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Services\AdminActivityLogger;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AdminManagementController extends Controller
{


    /**
     * List admins
     */
    public function index(): JsonResponse
    {

        $admins = Admin::with('permissions')
            ->latest()
            ->get();



        return response()->json([

            'success'=>true,

            'message'=>'Admins retrieved.',

            'data'=>[

                'admins'=>$admins

            ]

        ]);

    }




    /**
     * Create admin
     */
    public function store(Request $request): JsonResponse
    {


        $validator = Validator::make($request->all(),[


            'name'=>[
                'required',
                'string',
                'max:100'
            ],


            'email'=>[
                'required',
                'email',
                'unique:admins,email'
            ],


            'password'=>[
                'required',
                'string',
                'min:6'
            ],


            'role'=>[
                'required',
                'in:admin,support'
            ],


        ]);



        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);


        }




        $creator = auth('admin')->user();



        $admin = Admin::create([


            'name'=>$request->name,

            'email'=>$request->email,

            'password'=>$request->password,

            'role'=>$request->role,

            'status'=>'active',

            'created_by'=>$creator->id,

        ]);





        return response()->json([


            'success'=>true,


            'message'=>'Admin created successfully.',


            'data'=>[

                'admin'=>$admin

            ]


        ],201);


    }


    public function update(Request $request,int $id): JsonResponse
    {

        $admin = Admin::find($id);


        if(!$admin){

            return response()->json([
                'success'=>false,
                'message'=>'Admin not found.'
            ],404);

        }


        $validator = Validator::make($request->all(),[

            'name'=>'sometimes|string|max:100',

            'email'=>'sometimes|email|unique:admins,email,'.$id,

            'role'=>'sometimes|in:admin,support',

            'status'=>'sometimes|in:active,inactive'

        ]);


        if($validator->fails()){

            return response()->json([
                'success'=>false,
                'message'=>'Validation failed.',
                'errors'=>$validator->errors()
            ],422);

        }



        $admin->update(
            $request->only([
                'name',
                'email',
                'role',
                'status'
            ])
        );



        if($request->has('status')){


            AdminActivityLogger::log(

                'ADMIN_STATUS_CHANGED',

                'Changed admin ID: '.$admin->id.
                ' status to '.$admin->status,

                $request

            );


        }
        else{


            AdminActivityLogger::log(

                'ADMIN_UPDATED',

                'Updated admin ID: '.$admin->id,

                $request

            );


        }



        return response()->json([

            'success'=>true,

            'message'=>'Admin updated successfully.',

            'data'=>[
                'admin'=>$admin
            ]

        ]);

    }


    /**
     * Show admin
     */
    public function show(int $id): JsonResponse
    {

        $admin = Admin::with('permissions')
            ->find($id);



        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);


        }



        return response()->json([

            'success'=>true,

            'message'=>'Admin retrieved.',

            'data'=>[

                'admin'=>$admin

            ]

        ]);

    }


    public function resetPassword(
        Request $request,
        int $id
    ): JsonResponse
    {


        $admin = Admin::find($id);



        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);

        }



        $validator = Validator::make($request->all(),[

            'password'=>[
                'required',
                'string',
                'min:6'
            ]

        ]);



        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);

        }




        $admin->update([

            'password'=>$request->password,

            'force_password_change'=>true

        ]);





        AdminActivityLogger::log(

            'ADMIN_PASSWORD_RESET',

            'Reset password for admin ID: '.$admin->id,

            $request

        );





        return response()->json([

            'success'=>true,

            'message'=>'Admin password reset successfully.'

        ]);

    }


    /**
     * Assign permissions
     */
    public function permissions(
        Request $request,
        int $id
    ): JsonResponse
    {


        $admin = Admin::find($id);



        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);

        }



        $permissionIds = Permission::whereIn(
            'id',
            $request->permission_ids
        )
        ->pluck('id');



        $admin->permissions()
            ->sync($permissionIds);



        return response()->json([

            'success'=>true,

            'message'=>'Admin permissions updated.',

            'data'=>[

                'permissions'=>$admin->permissions

            ]

        ]);

    }


}