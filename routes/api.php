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
    | User Public Auth
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
    | Admin Public Auth
    |--------------------------------------------------------------------------
    */


    Route::post(
        '/admin/login',
        [AdminAuthController::class,'login']
    );







    /*
    |--------------------------------------------------------------------------
    | USER PROTECTED API
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:api')
    ->group(function(){



        /*
        | User Auth
        */

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
        | License
        */


        Route::post(
            '/license-codes/redeem',
            [LicenseCodeController::class,'redeem']
        );



    });







    /*
    |--------------------------------------------------------------------------
    | ADMIN PROTECTED API
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:admin')
    ->prefix('admin')
    ->group(function(){



        /*
        | Dashboard
        */


        Route::get(
            '/dashboard',
            [AdminDashboardController::class,'index']
        );


        Route::get(
            '/statistics',
            [AdminStatisticsController::class,'index']
        );


        /*
        | Users Management
        */


        Route::get(
            '/users',
            [AdminUserController::class,'index']
        );


        Route::get(
            '/users/{id}',
            [AdminUserController::class,'show']
        );





        /*
        | Subscription Plans
        */


        Route::get(
            '/plans',
            [SubscriptionPlanController::class,'index']
        );


        Route::post(
            '/plans',
            [SubscriptionPlanController::class,'store']
        );





        /*
        | License Management
        */


        Route::post(
            '/licenses/generate',
            [AdminLicenseController::class,'generate']
        );


        Route::get(
            '/licenses',
            [AdminLicenseController::class,'index']
        );





        /*
        | Subscription Management
        */


        Route::get(
            '/subscriptions',
            [AdminSubscriptionController::class,'index']
        );


        Route::post(
            '/subscriptions/{id}/cancel',
            [AdminSubscriptionController::class,'cancel']
        );





        /*
        | Protection Rules
        */


        Route::get(
            '/protection-rules',
            [ProtectionRuleController::class,'index']
        );


        Route::post(
            '/protection-rules',
            [ProtectionRuleController::class,'store']
        );

        



    });



});