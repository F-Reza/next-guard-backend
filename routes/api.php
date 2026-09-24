<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\TrialController;
use App\Http\Controllers\Api\V1\DeviceProtectionController;
use App\Http\Controllers\Api\V1\ProtectionRuleController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;
use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\LicenseCodeController;



Route::prefix('v1')->group(function () {


    /*
    |--------------------------------------------------------------------------
    | Health Check
    |--------------------------------------------------------------------------
    */

    Route::get('/health', function () {

        return response()->json([

            'success' => true,

            'message' => 'Next Guard API is running.',

            'version' => 'v1',

        ]);

    });



    /*
    |--------------------------------------------------------------------------
    | Public Authentication
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
    | Protected API
    |--------------------------------------------------------------------------
    */


    Route::middleware('auth:api')->group(function () {



        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Devices
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Device Protection
        |--------------------------------------------------------------------------
        |
        | Active subscription required
        |
        */


        Route::middleware('subscription')->group(function () {


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
        |--------------------------------------------------------------------------
        | Trial System
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Protection Rules
        |--------------------------------------------------------------------------
        */


        Route::get(
            '/protection-rules',
            [ProtectionRuleController::class,'index']
        );


        Route::post(
            '/protection-rules',
            [ProtectionRuleController::class,'store']
        );


        Route::get(
            '/devices/{id}/protection/rules',
            [ProtectionRuleController::class,'deviceRules']
        );





        /*
        |--------------------------------------------------------------------------
        | Subscription Plans
        |--------------------------------------------------------------------------
        */


        Route::get(
            '/subscription-plans',
            [SubscriptionPlanController::class,'index']
        );


        Route::post(
            '/subscription-plans',
            [SubscriptionPlanController::class,'store']
        );





        /*
        |--------------------------------------------------------------------------
        | Subscription
        |--------------------------------------------------------------------------
        */


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
        |--------------------------------------------------------------------------
        | License Codes
        |--------------------------------------------------------------------------
        */
        Route::post(
            '/license-codes/generate',
            [LicenseCodeController::class,'generate']
        );

        Route::post(
            '/license-codes/redeem',
            [LicenseCodeController::class,'redeem']
        );



    });



});