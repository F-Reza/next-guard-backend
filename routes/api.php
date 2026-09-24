<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'message' => 'Next Guard API is running.',
            'version' => 'v1',
        ]);
    });

    Route::post('/auth/register', [AuthController::class, 'register']);
});