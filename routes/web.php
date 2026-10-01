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


                /*
                |--------------------------------------------------------------------------
                | Users
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/users',
                    [AdminWebController::class, 'users']
                )->name('users');


                Route::get(
                    '/users/create',
                    [AdminWebController::class, 'userCreate']
                )->name('users.create');


                Route::post(
                    '/users',
                    [AdminWebController::class, 'userStore']
                )->name('users.store');


                Route::get(
                    '/users/{id}',
                    [AdminWebController::class, 'userShow']
                )->name('users.show');


                Route::get(
                    '/users/{id}/edit',
                    [AdminWebController::class, 'userEdit']
                )->name('users.edit');


                Route::put(
                    '/users/{id}',
                    [AdminWebController::class, 'userUpdate']
                )->name('users.update');


                Route::delete(
                    '/users/{id}',
                    [AdminWebController::class, 'userDestroy']
                )->name('users.destroy');


                Route::post(
                    '/logout',
                    [AdminWebController::class, 'logout']
                )->name('logout');

            });






    });