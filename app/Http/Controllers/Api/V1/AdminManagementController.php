<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Permission;
use App\Services\AdminActivityLogger;

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





        /*
        |--------------------------------------------------------------------------
        | Only Super Admin can create Super Admin
        |--------------------------------------------------------------------------
        */


        if(
            $request->role === 'super_admin'
            &&
            $creator->role !== 'super_admin'
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




        $currentAdmin = auth('admin')->user();



        /*
        |--------------------------------------------------------------------------
        | Protect super admin role
        |--------------------------------------------------------------------------
        */

        if(
            $request->role === 'super_admin'
            &&
            $currentAdmin->role !== 'super_admin'
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







        $oldRole = $admin->role;
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

        $description = 'Updated admin ID: '.$admin->id;






        if(
            $request->has('role')
            &&
            $oldRole !== $admin->role
        ){

            $action = 'ADMIN_ROLE_CHANGED';

            $description =
                'Changed admin ID: '.$admin->id.
                ' role from '.$oldRole.
                ' to '.$admin->role;

        }






        if(
            $request->has('status')
            &&
            $oldStatus !== $admin->status
        ){

            $action = 'ADMIN_STATUS_CHANGED';

            $description =
                'Changed admin ID: '.$admin->id.
                ' status from '.$oldStatus.
                ' to '.$admin->status;

        }







        AdminActivityLogger::log(

            $action,

            $description,

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


        $currentAdmin = auth('admin')->user();


        if(!$currentAdmin || $currentAdmin->role !== 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);


        }

        if(!$currentAdmin){

            return response()->json([

                'success'=>false,

                'message'=>'Unauthenticated admin.'

            ],401);

        }




        /*
        |--------------------------------------------------------------------------
        | Only Super Admin can manage permissions
        |--------------------------------------------------------------------------
        */

        if($currentAdmin->role !== 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);


        }






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








        $permissionNames = Permission::whereIn(

            'id',

            $request->permission_ids

        )
        ->pluck('name')
        ->toArray();








        $admin->permissions()

            ->sync($request->permission_ids);








        AdminActivityLogger::log(

            'ADMIN_PERMISSION_UPDATED',

            'Updated permissions for admin ID: '.$admin->id.
            ' Permissions: '.implode(', ', $permissionNames),

            $request

        );








        return response()->json([


            'success'=>true,


            'message'=>'Admin permissions updated.',


            'data'=>[


                'permissions'=>$admin
                    ->permissions

            ]


        ]);



    }




    /**
     * Unlock admin account
     */
    public function unlock(int $id): JsonResponse
    {


        $admin = Admin::find($id);



        if(!$admin){


            return response()->json([

                'success'=>false,

                'message'=>'Admin not found.'

            ],404);


        }




        /*
        |--------------------------------------------------------------------------
        | Prevent unlocking super admin
        |--------------------------------------------------------------------------
        */


        if($admin->role === 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Super admin security cannot be modified.'

            ],403);


        }





        /*
        |--------------------------------------------------------------------------
        | Reset Login Security
        |--------------------------------------------------------------------------
        */


        $admin->update([


            'failed_login_attempts'=>0,


            'locked_until'=>null,


            'last_failed_login_at'=>null


        ]);







        AdminActivityLogger::log(

            'ADMIN_ACCOUNT_UNLOCKED',

            'Unlocked admin account: '.$admin->email,

            request()

        );







        return response()->json([


            'success'=>true,


            'message'=>'Admin account unlocked successfully.',



            'data'=>[

                'admin'=>[

                    'id'=>$admin->id,

                    'email'=>$admin->email,

                    'failed_login_attempts'=>$admin->failed_login_attempts,

                    'locked_until'=>$admin->locked_until

                ]

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


        $currentAdmin = auth('admin')->user();


        if(!$currentAdmin || $currentAdmin->role !== 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);


        }


        if(!$currentAdmin){


            return response()->json([

                'success'=>false,

                'message'=>'Unauthenticated admin.'

            ],401);


        }






        /*
        |--------------------------------------------------------------------------
        | Only Super Admin
        |--------------------------------------------------------------------------
        */


        if($currentAdmin->role !== 'super_admin'){


            return response()->json([

                'success'=>false,

                'message'=>'Only super admin can manage permissions.'

            ],403);


        }








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








        /*
        |--------------------------------------------------------------------------
        | Prevent removing manage_admins from own super admin
        |--------------------------------------------------------------------------
        */


        if(

            $admin->id === $currentAdmin->id

            &&

            $permissionModel->name === 'manage_admins'

        ){


            return response()->json([


                'success'=>false,


                'message'=>'Cannot remove manage_admins from your own account.'


            ],403);


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

        /**
     * Deleted admins list
     */
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









    /**
     * Restore deleted admin
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
     * Soft delete admin
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







        /*
        |--------------------------------------------------------------------------
        | Protect Super Admin
        |--------------------------------------------------------------------------
        */


        if($admin->role === 'super_admin'){


            return response()->json([


                'success'=>false,


                'message'=>'Super admin cannot be deleted.'


            ],403);


        }








        /*
        |--------------------------------------------------------------------------
        | Prevent self delete
        |--------------------------------------------------------------------------
        */


        if(auth('admin')->id() === $admin->id){


            return response()->json([


                'success'=>false,


                'message'=>'You cannot delete your own account.'


            ],403);


        }







        $email = $admin->email;







        $admin->permissions()

            ->detach();






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
    public function forceDelete(
        int $id
    ): JsonResponse
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
        | Super Admin Protection
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
            |--------------------------------------------------------------------------
            | Remove permissions
            |--------------------------------------------------------------------------
            */


            $admin->permissions()

                ->detach();






            /*
            |--------------------------------------------------------------------------
            | Remove activity logs
            |--------------------------------------------------------------------------
            */


            $admin->activityLogs()

                ->delete();






            /*
            |--------------------------------------------------------------------------
            | Permanent delete
            |--------------------------------------------------------------------------
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