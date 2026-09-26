<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;

use App\Models\Admin;
use App\Models\Permission;

use App\Services\AdminActivityLogger;
use App\Services\AdminNotificationService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
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
            ->paginate(20);


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
                'in:admin,support,super_admin'
            ]

        ]);



        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);


        }



        /** @var Admin|null $creator */
        $creator = auth('admin')->user();



        if(
            $request->role === 'super_admin'
            &&
            (!$creator || $creator->role !== 'super_admin')
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can create super admin.'

            ],403);

        }



        $admin = Admin::create([

            'name'=>$request->name,

            'email'=>$request->email,

            'password'=>$request->password,

            'role'=>$request->role,

            'status'=>'active',

            'created_by'=>$creator?->id

        ]);




        AdminActivityLogger::log(

            'ADMIN_CREATED',

            'Created admin account: '.$admin->email,

            $request,

            $creator,

            AdminActivityLogger::INFO,

            [

                'created_admin_id'=>$admin->id,

                'role'=>$admin->role

            ]

        );




        AdminNotificationService::send(

            $admin,

            'ACCOUNT',

            'Admin Account Created',

            'Your admin account has been created.',

            [

                'created_by'=>$creator?->name,

                'role'=>$admin->role

            ]

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



        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();



        if(
            $request->role === 'super_admin'
            &&
            (!$currentAdmin || $currentAdmin->role !== 'super_admin')
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can assign super admin role.'

            ],403);

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
                'in:admin,support,super_admin'
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




        $oldRole   = $admin->role;
        $oldStatus = $admin->status;



        $admin->update(

            $request->only([

                'name',
                'email',
                'role',
                'status'

            ])

        );




        $action = 'ADMIN_UPDATED';

        $description =
            'Updated admin ID: '.$admin->id;




        if(
            $request->has('role')
            &&
            $oldRole !== $admin->role
        ){

            $action='ADMIN_ROLE_CHANGED';


            $description =
                'Changed admin role from '
                .$oldRole
                .' to '
                .$admin->role;

        }




        if(
            $request->has('status')
            &&
            $oldStatus !== $admin->status
        ){

            $action='ADMIN_STATUS_CHANGED';


            $description =
                'Changed admin status from '
                .$oldStatus
                .' to '
                .$admin->status;

        }





        AdminActivityLogger::log(

            $action,

            $description,

            $request,

            $currentAdmin,

            'warning',

            [

                'target_admin'=>$admin->email,

                'old_role'=>$oldRole,

                'new_role'=>$admin->role,

                'old_status'=>$oldStatus,

                'new_status'=>$admin->status

            ]

        );





        AdminNotificationService::send(

            $admin,

            'ACCOUNT',

            'Admin Account Updated',

            'Your admin account information has been updated.',

            [

                'updated_by'=>$currentAdmin?->name,

                'action'=>$action

            ]

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
    public function show(
        int $id
    ): JsonResponse
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
     * Reset admin password
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

            'force_password_change'=>true,

            'failed_login_attempts'=>0,

            'locked_until'=>null,

            'last_failed_login_at'=>null

        ]);





        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();





        AdminActivityLogger::log(

            'ADMIN_PASSWORD_RESET',

            'Reset password for admin: '.$admin->email,

            $request,

            $currentAdmin,

            'critical',

            [

                'target_admin'=>$admin->email

            ]

        );





        AdminNotificationService::send(

            $admin,

            'SECURITY',

            'Password Reset',

            'Your admin password was reset.',

            [

                'reset_by'=>$currentAdmin?->name

            ]

        );





        return response()->json([

            'success'=>true,

            'message'=>'Admin password reset successfully.'

        ]);


    }

        /**
     * Update Admin Permissions
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





        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();





        /*
        |--------------------------------------------------------------------------
        | Only Super Admin
        |--------------------------------------------------------------------------
        */


        if(
            !$currentAdmin
            ||
            $currentAdmin->role !== 'super_admin'
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);

        }





        $validator = Validator::make($request->all(),[


            'permissions'=>[

                'required',

                'array'

            ],


            'permissions.*'=>[

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





        $permissionIds = $request->permissions;





        $permissions = Permission::whereIn(
            'id',
            $permissionIds
        )
        ->get();






        $admin->permissions()->sync(
            $permissionIds
        );





        $permissionNames =
            $permissions
            ->pluck('name')
            ->toArray();






        AdminActivityLogger::log(

            'ADMIN_PERMISSION_UPDATED',

            'Updated permissions for admin: '.$admin->email,

            $request,

            $currentAdmin,

            'critical',

            [

                'target_admin'=>$admin->email,

                'permissions'=>$permissionNames

            ]

        );







        AdminNotificationService::send(

            $admin,

            'SECURITY',

            'Permissions Updated',

            'Your admin permissions have been changed.',

            [

                'updated_by'=>$currentAdmin->name,

                'permissions'=>$permissionNames

            ]

        );







        return response()->json([


            'success'=>true,


            'message'=>'Admin permissions updated.',


            'data'=>[

                'permissions'=>
                    $admin
                    ->permissions()
                    ->get()

            ]


        ]);

    }









    /**
     * Remove Admin Permission
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





        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();





        if(
            !$currentAdmin
            ||
            $currentAdmin->role !== 'super_admin'
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);

        }






        $permissionModel = Permission::find($permission);



        if(!$permissionModel){


            return response()->json([

                'success'=>false,

                'message'=>'Permission not found.'

            ],404);


        }







        $admin
            ->permissions()
            ->detach($permission);







        AdminActivityLogger::log(

            'ADMIN_PERMISSION_REMOVED',

            'Removed permission from admin: '.$admin->email,

            request(),

            $currentAdmin,

            'critical',

            [

                'removed_permission'=>
                    $permissionModel->name

            ]

        );







        AdminNotificationService::send(

            $admin,

            'SECURITY',

            'Permission Removed',

            'A permission was removed from your account.',

            [

                'permission'=>
                    $permissionModel->name,

                'removed_by'=>
                    $currentAdmin->name

            ]

        );







        return response()->json([


            'success'=>true,


            'message'=>'Permission removed successfully.'


        ]);

    }









    /**
     * Unlock Admin Account
     */
    public function unlock(
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







        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();






        $admin->update([


            'failed_login_attempts'=>0,


            'locked_until'=>null,


            'last_failed_login_at'=>null


        ]);







        AdminActivityLogger::log(

            'ADMIN_ACCOUNT_UNLOCKED',

            'Unlocked admin account: '.$admin->email,

            request(),

            $currentAdmin,

            'warning',

            [

                'admin_id'=>$admin->id

            ]

        );








        AdminNotificationService::send(

            $admin,

            'SECURITY',

            'Account Unlocked',

            'Your admin account has been unlocked.',

            [

                'unlocked_by'=>$currentAdmin?->name

            ]

        );








        return response()->json([


            'success'=>true,


            'message'=>'Admin account unlocked successfully.'


        ]);

    }

        /**
     * Deleted Admin List
     */
    public function deleted(): JsonResponse
    {


        $admins = Admin::onlyTrashed()
            ->with('permissions')
            ->latest()
            ->paginate(20);



        return response()->json([

            'success'=>true,

            'message'=>'Deleted admins retrieved.',

            'data'=>[
                'admins'=>$admins
            ]

        ]);

    }









    /**
     * Restore Deleted Admin
     */
    public function restore(
        int $id
    ): JsonResponse
    {


        $admin = Admin::onlyTrashed()
            ->find($id);




        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Deleted admin not found.'

            ],404);


        }






        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();





        $admin->restore();





        AdminActivityLogger::log(

            'ADMIN_RESTORED',

            'Restored admin: '.$admin->email,

            request(),

            $currentAdmin,

            'warning',

            [

                'restored_admin_id'=>$admin->id,

                'restored_email'=>$admin->email

            ]

        );






        AdminNotificationService::send(

            $admin,

            'ACCOUNT',

            'Account Restored',

            'Your admin account has been restored.',

            [

                'restored_by'=>$currentAdmin?->name

            ]

        );






        return response()->json([


            'success'=>true,


            'message'=>'Admin restored successfully.',


            'data'=>[

                'admin'=>$admin

            ]

        ]);

    }









    /**
     * Soft Delete Admin
     */
    public function destroy(
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






        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();





        if(
            $currentAdmin
            &&
            $currentAdmin->id === $admin->id
        ){

            return response()->json([

                'success'=>false,

                'message'=>'You cannot delete your own account.'

            ],403);

        }







        $email = $admin->email;



        $admin->delete();







        AdminActivityLogger::log(

            'ADMIN_DELETED',

            'Deleted admin: '.$email,

            request(),

            $currentAdmin,

            'critical',

            [

                'deleted_admin_id'=>$id,

                'deleted_email'=>$email

            ]

        );







        AdminNotificationService::send(

            $admin,

            'ACCOUNT',

            'Account Disabled',

            'Your admin account has been disabled.',

            [

                'deleted_by'=>$currentAdmin?->name

            ]

        );







        return response()->json([


            'success'=>true,


            'message'=>'Admin deleted successfully.'


        ]);

    }









    /**
     * Permanent Delete Admin
     */
    public function forceDelete(
        int $id
    ): JsonResponse
    {


        $admin = Admin::onlyTrashed()
            ->find($id);





        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Deleted admin not found.'

            ],404);


        }






        /** @var Admin|null $currentAdmin */
        $currentAdmin = auth('admin')->user();







        if(
            !$currentAdmin
            ||
            $currentAdmin->role !== 'super_admin'
        ){

            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can permanently delete admin.'

            ],403);


        }







        $email = $admin->email;



        $admin->forceDelete();








        AdminActivityLogger::log(

            'ADMIN_FORCE_DELETED',

            'Permanently deleted admin: '.$email,

            request(),

            $currentAdmin,

            'critical',

            [

                'deleted_admin_id'=>$id,

                'deleted_email'=>$email

            ]

        );







        return response()->json([


            'success'=>true,


            'message'=>'Admin permanently deleted successfully.'


        ]);

    }



}