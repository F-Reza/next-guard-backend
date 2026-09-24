<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DeviceController;
use App\Http\Controllers\Api\V1\TrialController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Health
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
    | Authentication - Public
    |--------------------------------------------------------------------------
    */

    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::post('/auth/login', [AuthController::class, 'login']);


    /*
    |--------------------------------------------------------------------------
    | Authentication - Protected
    |--------------------------------------------------------------------------
    */

    Route::middleware('auth:api')->group(function () {

        // Authentication
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        // Devices
        Route::post('/devices/enroll', [DeviceController::class, 'enroll']);
        Route::get('/devices', [DeviceController::class, 'index']);
        Route::get('/devices/{id}', [DeviceController::class, 'show']);
        Route::post('/devices/{id}/heartbeat', [DeviceController::class, 'heartbeat']);
        
        // Trial Entitlements
        Route::get('/trial/eligibility', [TrialController::class, 'eligibility']);
        Route::post('/trial/start', [TrialController::class, 'start']);
        Route::get('/trial', [TrialController::class, 'show']);

    });
    
});