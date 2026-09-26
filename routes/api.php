<?php

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| Controllers
|--------------------------------------------------------------------------
*/


use App\Http\Controllers\Api\V1\AuthController;

use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\DeviceProtectionController;

use App\Http\Controllers\Api\V1\TrialController;

use App\Http\Controllers\Api\V1\SubscriptionController;
use App\Http\Controllers\Api\V1\SubscriptionPlanController;

use App\Http\Controllers\Api\V1\LicenseCodeController;

use App\Http\Controllers\Api\V1\ProtectionRuleController;



/*
|--------------------------------------------------------------------------
| ADMIN Controllers
|--------------------------------------------------------------------------
*/


use App\Http\Controllers\Api\V1\AdminAuthController;

use App\Http\Controllers\Api\V1\AdminDashboardController;
use App\Http\Controllers\Api\V1\AdminStatisticsController;

use App\Http\Controllers\Api\V1\AdminUserController;

use App\Http\Controllers\Api\V1\AdminLicenseController;

use App\Http\Controllers\Api\V1\AdminSubscriptionController;

use App\Http\Controllers\Api\V1\AdminActivityLogController;

use App\Http\Controllers\Api\V1\AdminManagementController;

use App\Http\Controllers\Api\V1\AdminPermissionController;




Route::prefix('v1')->group(function(){





/*
|--------------------------------------------------------------------------
| HEALTH CHECK
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
| ADMIN LOGIN
|--------------------------------------------------------------------------
*/


Route::post(
    '/admin/login',
    [AdminAuthController::class,'login']
);









/*
|--------------------------------------------------------------------------
| USER AUTH PROTECTED
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
    | Protection
    |--------------------------------------------------------------------------
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
    |--------------------------------------------------------------------------
    | Trial
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
    | Subscription
    |--------------------------------------------------------------------------
    */


    Route::get(
        '/subscription-plans',
        [SubscriptionPlanController::class,'index']
    );


    Route::get(
        '/subscriptions',
        [SubscriptionController::class,'index']
    );


    Route::post(
        '/subscriptions/activate',
        [SubscriptionController::class,'activate']
    );


    Route::get(
        '/subscriptions/current',
        [SubscriptionController::class,'current']
    );









    /*
    |--------------------------------------------------------------------------
    | License Redeem
    |--------------------------------------------------------------------------
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
| Admin Profile
|--------------------------------------------------------------------------
*/


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
| Permissions
|--------------------------------------------------------------------------
*/


Route::middleware('permission:manage_admins')
->get(
    '/permissions',
    [AdminPermissionController::class,'index']
);










/*
|--------------------------------------------------------------------------
| Activity Logs
|--------------------------------------------------------------------------
*/


Route::middleware('permission:view_logs')
->get(
    '/activity-logs',
    [AdminActivityLogController::class,'index']
);










/*
|--------------------------------------------------------------------------
| Users Management
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



    Route::get(
        '/admins',
        [AdminManagementController::class,'index']
    );



    Route::get(
        '/admins/deleted',
        [AdminManagementController::class,'deleted']
    );



    Route::post(
        '/admins',
        [AdminManagementController::class,'store']
    );



    Route::put(
        '/admins/{id}',
        [AdminManagementController::class,'update']
    );



    Route::get(
        '/admins/{id}',
        [AdminManagementController::class,'show']
    );



    Route::post(
        '/admins/{id}/reset-password',
        [AdminManagementController::class,'resetPassword']
    );



    Route::delete(
        '/admins/{id}',
        [AdminManagementController::class,'destroy']
    );



    Route::post(
        '/admins/{id}/restore',
        [AdminManagementController::class,'restore']
    );

    
    Route::middleware('permission:manage_admins')
    ->post(
        '/admins/{id}/unlock',
        [AdminManagementController::class,'unlock']
    );


    Route::delete(
        '/admins/{id}/force-delete',
        [AdminManagementController::class,'forceDelete']
    );



});



/*
|--------------------------------------------------------------------------
| Permission Management
|--------------------------------------------------------------------------
| Only super admin
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| Plans
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



    Route::get(
        '/licenses',
        [AdminLicenseController::class,'index']
    );



    Route::post(
        '/licenses/generate',
        [AdminLicenseController::class,'generate']
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