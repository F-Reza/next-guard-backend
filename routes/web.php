<?php

use App\Http\Controllers\AdminWebController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Admin Frontend
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/login',
            [AdminWebController::class, 'showLogin']
        )->name('login');

        Route::post(
            '/login',
            [AdminWebController::class, 'login']
        )
        ->middleware('throttle:login')
        ->name('login.submit');


        /*
        |--------------------------------------------------------------------------
        | Protected Admin Panel
        |--------------------------------------------------------------------------
        */

        Route::middleware('admin.web')
            ->group(function () {

                Route::get(
                    '/',
                    function () {
                        return redirect()->route(
                            'admin.dashboard'
                        );
                    }
                )->name('home');


                Route::get(
                    '/dashboard',
                    [AdminWebController::class, 'dashboard']
                )->name('dashboard');


                Route::post(
                    '/logout',
                    [AdminWebController::class, 'logout']
                )->name('logout');

            });

    });