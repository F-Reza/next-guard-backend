<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\TrialController;
use App\Http\Controllers\Api\V1\DeviceProtectionController;
use App\Http\Controllers\Api\V1\ProtectionRuleController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\LicenseCodeController;

use App\Http\Controllers\Api\V1\AdminAuthController;
use App\Http\Controllers\Api\V1\AdminDashboardController;
use App\Http\Controllers\Api\V1\AdminUserController;
use App\Http\Controllers\Api\V1\AdminLicenseController;
use App\Http\Controllers\Api\V1\AdminSubscriptionController;
use App\Http\Controllers\Api\V1\AdminStatisticsController;
use App\Http\Controllers\Api\V1\AdminActivityLogController;
use App\Http\Controllers\Api\V1\AdminManagementController;



Route::prefix('v1')->group(function () {



    /*
    |--------------------------------------------------------------------------
    | Health
    |--------------------------------------------------------------------------
    */


    Route::get('/health', function () {

        return response()->json([

            'success'=>true,

            'message'=>'Next Guard API running.',

            'version'=>'v1'

        ]);

    });





    /*
    |--------------------------------------------------------------------------
    | USER PUBLIC AUTH
    |--------------------------------------------------------------------------
    */


    Route::post(
        '/auth/register',
        [AuthController::class,'register']
    );


    Route::post(
        '/auth/login',
        [AuthController::class,'login']
    );


    Route::post(
        '/auth/refresh',
        [AuthController::class,'refresh']
    );





    /*
    |--------------------------------------------------------------------------
    | ADMIN PUBLIC AUTH
    |--------------------------------------------------------------------------
    */


    Route::post(
        '/admin/login',
        [AdminAuthController::class,'login']
    );








    /*
    |--------------------------------------------------------------------------
    | USER PROTECTED
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:api')
    ->group(function(){



        Route::get(
            '/auth/me',
            [AuthController::class,'me']
        );


        Route::post(
            '/auth/logout',
            [AuthController::class,'logout']
        );




        /*
        | Devices
        */


        Route::post(
            '/devices/enroll',
            [DeviceController::class,'enroll']
        );


        Route::get(
            '/devices',
            [DeviceController::class,'index']
        );


        Route::get(
            '/devices/{id}',
            [DeviceController::class,'show']
        );


        Route::post(
            '/devices/{id}/heartbeat',
            [DeviceController::class,'heartbeat']
        );




        /*
        | Protection
        */


        Route::middleware('subscription')
        ->group(function(){


            Route::get(
                '/devices/{id}/protection',
                [DeviceProtectionController::class,'show']
            );


            Route::post(
                '/devices/{id}/protection/update',
                [DeviceProtectionController::class,'update']
            );


            Route::post(
                '/devices/{id}/protection/sync',
                [DeviceProtectionController::class,'sync']
            );


        });





        /*
        | Trial
        */


        Route::get(
            '/trial/eligibility',
            [TrialController::class,'eligibility']
        );


        Route::post(
            '/trial/start',
            [TrialController::class,'start']
        );


        Route::get(
            '/trial',
            [TrialController::class,'show']
        );





        /*
        | Subscription
        */


        Route::get(
            '/subscription-plans',
            [SubscriptionPlanController::class,'index']
        );


        Route::post(
            '/subscriptions/activate',
            [SubscriptionController::class,'activate']
        );


        Route::get(
            '/subscriptions',
            [SubscriptionController::class,'index']
        );


        Route::get(
            '/subscriptions/current',
            [SubscriptionController::class,'current']
        );




        /*
        | License Redeem
        */


        Route::post(
            '/license-codes/redeem',
            [LicenseCodeController::class,'redeem']
        );


    });









    /*
    |--------------------------------------------------------------------------
    | ADMIN PROTECTED
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:admin')
    ->prefix('admin')
    ->group(function(){





        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */


        Route::middleware('permission:view_dashboard')
        ->get(
            '/dashboard',
            [AdminDashboardController::class,'index']
        );



        Route::middleware('permission:view_dashboard')
        ->get(
            '/statistics',
            [AdminStatisticsController::class,'index']
        );




        Route::middleware('permission:view_logs')
        ->get(
            '/activity-logs',
            [AdminActivityLogController::class,'index']
        );






        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */


        Route::middleware('permission:manage_users')
        ->group(function(){


            Route::get(
                '/users',
                [AdminUserController::class,'index']
            );


            Route::get(
                '/users/{id}',
                [AdminUserController::class,'show']
            );


        });


        /*
        |--------------------------------------------------------------------------
        | Admins
        |--------------------------------------------------------------------------
        */
        Route::get(
            '/admins',
            [AdminManagementController::class,'index']
        );


        Route::post(
            '/admins',
            [AdminManagementController::class,'store']
        );


        Route::get(
            '/admins/{id}',
            [AdminManagementController::class,'show']
        );


        Route::post(
            '/admins/{id}/permissions',
            [AdminManagementController::class,'permissions']
        );




        /*
        |--------------------------------------------------------------------------
        | Plans
        |--------------------------------------------------------------------------
        */


        Route::get(
            '/plans',
            [SubscriptionPlanController::class,'index']
        );



        Route::middleware('permission:manage_plans')
        ->post(
            '/plans',
            [SubscriptionPlanController::class,'store']
        );







        /*
        |--------------------------------------------------------------------------
        | Licenses
        |--------------------------------------------------------------------------
        */


        Route::middleware('permission:manage_licenses')
        ->group(function(){


            Route::post(
                '/licenses/generate',
                [AdminLicenseController::class,'generate']
            );


            Route::get(
                '/licenses',
                [AdminLicenseController::class,'index']
            );


        });







        /*
        |--------------------------------------------------------------------------
        | Subscriptions
        |--------------------------------------------------------------------------
        */


        Route::middleware('permission:manage_subscriptions')
        ->group(function(){


            Route::get(
                '/subscriptions',
                [AdminSubscriptionController::class,'index']
            );


            Route::post(
                '/subscriptions/{id}/cancel',
                [AdminSubscriptionController::class,'cancel']
            );


        });







        /*
        |--------------------------------------------------------------------------
        | Protection Rules
        |--------------------------------------------------------------------------
        */


        Route::get(
            '/protection-rules',
            [ProtectionRuleController::class,'index']
        );



        Route::middleware('permission:manage_rules')
        ->post(
            '/protection-rules',
            [ProtectionRuleController::class,'store']
        );



    });



});