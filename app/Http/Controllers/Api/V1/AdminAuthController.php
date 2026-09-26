<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;

use App\Models\Admin;

use App\Models\AdminSession;

use App\Services\AdminActivityLogger;

use App\Services\AdminLoginSecurity;

use App\Services\AdminNotificationService;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;



class AdminAuthController extends Controller
{


    /**
     * Admin Login
     */
    public function login(
        Request $request
    ): JsonResponse
    {


        $validator = Validator::make(
            $request->all(),
            [

                'email'=>[
                    'required',
                    'email'
                ],


                'password'=>[
                    'required',
                    'string'
                ]

            ]
        );



        if($validator->fails()){


            return response()->json([

                'success'=>false,

                'message'=>'Validation failed.',

                'errors'=>$validator->errors()

            ],422);


        }




        /*
        |--------------------------------------------------------------------------
        | Find Admin
        |--------------------------------------------------------------------------
        */


        $admin = Admin::where(
                'email',
                $request->email
            )
            ->whereNull('deleted_at')
            ->first();





        if(!$admin){


            AdminActivityLogger::log(

                'ADMIN_LOGIN_FAILED',

                'Login attempt with unknown email.',

                $request,

                null,

                AdminActivityLogger::WARNING,

                [

                    'reason'=>'email_not_found',

                    'email'=>$request->email

                ]

            );



            return response()->json([

                'success'=>false,

                'message'=>'Invalid admin credentials.'

            ],401);


        }





        /*
        |--------------------------------------------------------------------------
        | Lock Check
        |--------------------------------------------------------------------------
        */


        if(
            $admin->role !== 'super_admin'
            &&
            AdminLoginSecurity::isLocked($admin)
        ){


            AdminActivityLogger::log(

                'ADMIN_LOGIN_BLOCKED',

                'Login blocked because account is locked.',

                $request,

                $admin,

                AdminActivityLogger::CRITICAL,

                [

                    'locked_until'=>$admin->locked_until

                ]

            );



            return response()->json([

                'success'=>false,

                'message'=>'Account temporarily locked. Try again later.'

            ],423);


        }

                /*
        |--------------------------------------------------------------------------
        | Password Check
        |--------------------------------------------------------------------------
        */


        if(!Hash::check(

            $request->password,

            $admin->password

        )){


            AdminLoginSecurity::failed($admin);



            /*
            |--------------------------------------------------------------------------
            | Account Locked After Failed Attempts
            |--------------------------------------------------------------------------
            */


            if(
                $admin->role !== 'super_admin'
                &&
                AdminLoginSecurity::isLocked($admin)
            ){


                AdminActivityLogger::log(

                    'ADMIN_ACCOUNT_LOCKED',

                    'Admin account locked after failed attempts.',

                    $request,

                    $admin,

                    AdminActivityLogger::CRITICAL,

                    [

                        'attempts'=>$admin->failed_login_attempts,

                        'locked_until'=>$admin->locked_until

                    ]

                );




                AdminNotificationService::send(

                    $admin,

                    'SECURITY',

                    'Account Locked',

                    'Your admin account has been locked after multiple failed login attempts.',

                    [

                        'attempts'=>$admin->failed_login_attempts,

                        'locked_until'=>$admin->locked_until

                    ]

                );


            }





            AdminActivityLogger::log(

                'ADMIN_LOGIN_FAILED',

                'Invalid password attempt.',

                $request,

                $admin,

                AdminActivityLogger::WARNING,

                [

                    'reason'=>'wrong_password'

                ]

            );





            AdminNotificationService::send(

                $admin,

                'LOGIN_FAILED',

                'Failed Login Attempt',

                'A failed login attempt was detected.',

                [

                    'ip'=>$request->ip(),

                    'time'=>now()

                ]

            );





            return response()->json([


                'success'=>false,


                'message'=>'Invalid admin credentials.'


            ],401);



        }





        /*
        |--------------------------------------------------------------------------
        | Status Check
        |--------------------------------------------------------------------------
        */


        if($admin->status !== 'active'){


            return response()->json([


                'success'=>false,


                'message'=>'Admin account inactive.'


            ],403);


        }





        /*
        |--------------------------------------------------------------------------
        | Reset Login Security
        |--------------------------------------------------------------------------
        */


        AdminLoginSecurity::success($admin);





        /*
        |--------------------------------------------------------------------------
        | JWT Token
        |--------------------------------------------------------------------------
        */


        $token = JWTAuth::fromUser($admin);





        /*
        |--------------------------------------------------------------------------
        | Create Admin Session
        |--------------------------------------------------------------------------
        */


        AdminSession::create([


            'admin_id'=>$admin->id,


            'token_hash'=>Hash::make($token),


            'ip_address'=>$request->ip(),


            'user_agent'=>$request->userAgent(),


            'last_activity'=>now(),


            'expires_at'=>now()->addMinutes(60)


        ]);





        /*
        |--------------------------------------------------------------------------
        | Update Last Login
        |--------------------------------------------------------------------------
        */


        $admin->update([

            'last_login_at'=>now()

        ]);

                /*
        |--------------------------------------------------------------------------
        | Activity Log
        |--------------------------------------------------------------------------
        */


        AdminActivityLogger::log(

            'ADMIN_LOGIN',

            'Admin logged in successfully.',

            $request,

            $admin,

            AdminActivityLogger::INFO,

            [

                'session_created'=>true

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Login Notification
        |--------------------------------------------------------------------------
        */


        AdminNotificationService::send(

            $admin,

            'LOGIN',

            'New Admin Login',

            'Your admin account was logged in successfully.',

            [

                'ip'=>$request->ip(),

                'user_agent'=>$request->userAgent(),

                'time'=>now()

            ]

        );





        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */


        return response()->json([


            'success'=>true,


            'message'=>'Admin login successful.',




            'data'=>[



                'admin'=>[


                    'id'=>$admin->id,


                    'name'=>$admin->name,


                    'email'=>$admin->email,


                    'role'=>$admin->role,


                    'status'=>$admin->status,


                    'force_password_change'=>$admin->force_password_change,



                    'permissions'=>

                        $admin

                        ->permissions()

                        ->pluck('name')



                ],





                'access_token'=>$token,


                'token_type'=>'Bearer'



            ]



        ]);



    }

    
    /**
     * Logout
     */
    public function logout(
        Request $request
    ): JsonResponse
    {

        $authAdmin = auth('admin')->user();


        $admin = $authAdmin instanceof Admin
            ? $authAdmin
            : null;



        AdminActivityLogger::log(

            'ADMIN_LOGOUT',

            'Admin logged out.',

            $request,

            $admin,

            AdminActivityLogger::INFO

        );



        try {


            JWTAuth::invalidate(
                JWTAuth::getToken()
            );


        } catch(\Throwable $e){


            report($e);


        }



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


        $authAdmin = auth('admin')->user();




        if(!$authAdmin instanceof Admin){


            return response()->json([


                'success'=>false,


                'message'=>'Unauthenticated admin.'


            ],401);


        }




        $admin = $authAdmin;





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


                    'force_password_change'=>$admin->force_password_change,



                    'permissions'=>

                        $admin

                        ->permissions()

                        ->pluck('name')



                ]



            ]



        ]);



    }









    /**
     * Profile
     */
    public function profile(): JsonResponse
    {


        $authAdmin = auth('admin')->user();




        if(!$authAdmin instanceof Admin){


            return response()->json([


                'success'=>false,


                'message'=>'Unauthenticated admin.'


            ],401);


        }




        $admin = $authAdmin;





        return response()->json([


            'success'=>true,


            'message'=>'Admin profile retrieved.',




            'data'=>[


                'admin'=>

                    $admin

                    ->load('permissions')


            ]



        ]);



    }

    /**
     * Change Password
     */
    public function changePassword(
        Request $request
    ): JsonResponse
    {


        $authAdmin = auth('admin')->user();



        if(!$authAdmin instanceof Admin){


            return response()->json([


                'success'=>false,


                'message'=>'Unauthenticated admin.'


            ],401);


        }



        $admin = $authAdmin;





        $validator = Validator::make(

            $request->all(),

            [


                'old_password'=>[

                    'required',

                    'string'

                ],



                'new_password'=>[

                    'required',

                    'string',

                    'min:6',

                    'confirmed'

                ]


            ]

        );





        if($validator->fails()){


            return response()->json([


                'success'=>false,


                'message'=>'Validation failed.',


                'errors'=>$validator->errors()


            ],422);



        }







        if(!Hash::check(


            $request->old_password,


            $admin->password


        )){


            return response()->json([


                'success'=>false,


                'message'=>'Old password incorrect.'



            ],422);



        }







        $admin->update([


            'password'=>$request->new_password,


            'force_password_change'=>false



        ]);







        AdminActivityLogger::log(



            'ADMIN_PASSWORD_CHANGED',



            'Admin changed own password.',



            $request,



            $admin,



            AdminActivityLogger::INFO



        );







        AdminNotificationService::send(



            $admin,



            'SECURITY',



            'Password Changed',



            'Your admin password was changed successfully.',



            [


                'ip'=>$request->ip(),


                'time'=>now()



            ]



        );








        return response()->json([



            'success'=>true,



            'message'=>'Password changed successfully.'



        ]);



    }


}