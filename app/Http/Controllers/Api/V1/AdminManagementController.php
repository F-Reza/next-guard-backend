<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Services\AdminActivityLogger;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
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
                'string',
                'max:50'
            ],


        ]);



        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);

        }



        if(
            $request->role === 'super_admin'
            &&
            auth('admin')->user()->role !== 'super_admin'
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can create super admin.'

            ],403);

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




        AdminActivityLogger::log(

            'ADMIN_CREATED',

            'Created admin account: '.$admin->email,

            $request

        );




        return response()->json([


            'success'=>true,


            'message'=>'Admin created successfully.',


            'data'=>[

                'admin'=>$admin

            ]


        ],201);


    }





    /**
     * Update admin
     */
    public function update(
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


            'name'=>[
                'sometimes',
                'string',
                'max:100'
            ],


            'email'=>[
                'sometimes',
                'email',
                'unique:admins,email,'.$id
            ],


            'role'=>[
                'sometimes',
                'string',
                'max:50'
            ],


            'status'=>[
                'sometimes',
                'in:active,inactive'
            ]


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




        AdminActivityLogger::log(

            'ADMIN_UPDATED',

            'Updated admin ID: '.$admin->id,

            $request

        );





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






    /**
     * Reset password
     */
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



        $validator = Validator::make($request->all(),[


            'permission_ids'=>[

                'required',

                'array'

            ],


            'permission_ids.*'=>[

                'exists:permissions,id'

            ]


        ]);




        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);


        }





        $admin->permissions()

            ->sync($request->permission_ids);





        AdminActivityLogger::log(

            'ADMIN_PERMISSION_UPDATED',

            'Updated permissions for admin ID: '.$admin->id,

            $request

        );





        return response()->json([


            'success'=>true,


            'message'=>'Admin permissions updated.',


            'data'=>[

                'permissions'=>$admin->permissions

            ]


        ]);


    }







    /**
     * Remove permission
     */
    public function removePermission(
        int $id,
        int $permission
    ): JsonResponse
    {


        $admin = Admin::find($id);



        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);

        }



        $permissionModel = Permission::find($permission);



        if(!$permissionModel){

            return response()->json([

                'success'=>false,

                'message'=>'Permission not found.'

            ],404);

        }




        $admin->permissions()

            ->detach($permission);





        AdminActivityLogger::log(

            'ADMIN_PERMISSION_REMOVED',

            'Removed permission '.$permissionModel->name.
            ' from admin ID: '.$admin->id,

            request()

        );





        return response()->json([

            'success'=>true,

            'message'=>'Permission removed successfully.'

        ]);


    }


    public function deleted(): JsonResponse
    {

        $admins = Admin::onlyTrashed()
            ->with('permissions')
            ->latest('deleted_at')
            ->get();


        return response()->json([

            'success'=>true,

            'message'=>'Deleted admins retrieved.',

            'data'=>[

                'admins'=>$admins

            ]

        ]);

    }

    public function restore(int $id): JsonResponse
    {

        $admin = Admin::onlyTrashed()
            ->find($id);


        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Deleted admin not found.'

            ],404);

        }



        $admin->restore();



        AdminActivityLogger::log(

            'ADMIN_RESTORED',

            'Restored admin: '.$admin->email,

            request()

        );



        return response()->json([

            'success'=>true,

            'message'=>'Admin restored successfully.'

        ]);

    }

    
    /**
     * Delete admin
     */
    public function destroy(int $id): JsonResponse
    {


        $admin = Admin::find($id);



        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);

        }




        if($admin->role === 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Super admin cannot be deleted.'

            ],403);


        }




        if(auth('admin')->id() === $admin->id){


            return response()->json([

                'success'=>false,

                'message'=>'You cannot delete your own account.'

            ],403);


        }




        $email = $admin->email;




        $admin->permissions()->detach();


        $admin->delete();





        AdminActivityLogger::log(

            'ADMIN_DELETED',

            'Deleted admin: '.$email,

            request()

        );





        return response()->json([

            'success'=>true,

            'message'=>'Admin deleted successfully.'

        ]);


    }


    /**
     * Permanently delete admin
     */
    public function forceDelete(int $id): JsonResponse
    {


        $admin = Admin::withTrashed()
            ->find($id);



        if(!$admin){

            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);

        }



        /*
        |--------------------------------------------------------------------------
        | Prevent deleting super admin
        |--------------------------------------------------------------------------
        */

        if($admin->role === 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Super admin cannot be permanently deleted.'

            ],403);

        }




        DB::transaction(function() use ($admin){



            /*
            | Remove permissions
            */

            $admin->permissions()
                ->detach();



            /*
            | Remove activity logs
            */

            $admin->activityLogs()
                ->delete();



            /*
            | Permanent delete
            */

            $admin->forceDelete();


        });





        AdminActivityLogger::log(

            'ADMIN_FORCE_DELETED',

            'Permanently deleted admin ID: '.$id,

            request()

        );




        return response()->json([

            'success'=>true,

            'message'=>'Admin permanently deleted successfully.'

        ]);

    }


}