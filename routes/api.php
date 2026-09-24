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



Route::prefix('v1')->group(function(){



/*
|--------------------------------------------------------------------------
| HEALTH
|--------------------------------------------------------------------------
*/


Route::get('/health',function(){

    return response()->json([

        'success'=>true,

        'message'=>'Next Guard API running.',

        'version'=>'v1'

    ]);

});






/*
|--------------------------------------------------------------------------
| USER AUTH
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
| ADMIN LOGIN
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
    | License redeem
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


    Route::get(
        '/profile',
        [AdminAuthController::class,'profile']
    );


    Route::post(
        '/change-password',
        [AdminAuthController::class,'changePassword']
    );


    Route::post(
        '/logout',
        [AdminAuthController::class,'logout']
    );  

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */


    Route::middleware('permission:view_dashboard')
    ->group(function(){


        Route::get(
            '/dashboard',
            [AdminDashboardController::class,'index']
        );


        Route::get(
            '/statistics',
            [AdminStatisticsController::class,'index']
        );


    });





    /*
    |--------------------------------------------------------------------------
    | Logs
    |--------------------------------------------------------------------------
    */


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
    | Admin Management
    |--------------------------------------------------------------------------
    */


    Route::middleware('permission:manage_admins')
    ->group(function(){


        /*
        | IMPORTANT:
        | Static route must be before {id}
        */


        Route::get(
            '/admins/deleted',
            [AdminManagementController::class,'deleted']
        );



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



        Route::put(
            '/admins/{id}',
            [AdminManagementController::class,'update']
        );



        /*
        | Permission assign
        */


        Route::post(
            '/admins/{id}/permissions',
            [AdminManagementController::class,'permissions']
        );



        Route::delete(
            '/admins/{id}/permissions/{permission}',
            [AdminManagementController::class,'removePermission']
        );



        /*
        | Password reset
        */


        Route::post(
            '/admins/{id}/reset-password',
            [AdminManagementController::class,'resetPassword']
        );



        /*
        | Soft delete
        */


        Route::delete(
            '/admins/{id}',
            [AdminManagementController::class,'destroy']
        );



        /*
        | Restore
        */


        Route::post(
            '/admins/{id}/restore',
            [AdminManagementController::class,'restore']
        );



        /*
        | Permanent delete
        */


        Route::delete(
            '/admins/{id}/force-delete',
            [AdminManagementController::class,'forceDelete']
        );



    });









    /*
    |--------------------------------------------------------------------------
    | Subscription Plans
    |--------------------------------------------------------------------------
    */


    Route::middleware('permission:manage_plans')
    ->group(function(){



        Route::get(
            '/plans',
            [SubscriptionPlanController::class,'index']
        );



        Route::post(
            '/plans',
            [SubscriptionPlanController::class,'store']
        );



    });









    /*
    |--------------------------------------------------------------------------
    | License Management
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
    | Subscription Management
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


    Route::middleware('permission:manage_rules')
    ->group(function(){



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



});