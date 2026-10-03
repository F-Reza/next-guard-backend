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



                /*
                |--------------------------------------------------------------------------
                | Devices
                |--------------------------------------------------------------------------
                */

                Route::get(
                    '/devices',
                    [AdminWebController::class, 'devices']
                )->name('devices');


                Route::get(
                    '/devices/{id}',
                    [AdminWebController::class, 'deviceShow']
                )->name('devices.show');


                Route::get(
                    '/devices/{id}/sessions',
                    [AdminWebController::class, 'sessions']
                )->name('devices.sessions');

                Route::get(
                    '/devices/{id}/events',
                    [AdminWebController::class, 'events']
                )->name('devices.events');


                Route::post(
                    '/devices/{id}/revoke',
                    [AdminWebController::class, 'deviceRevoke']
                )->name('devices.revoke');               

                Route::post(
                    '/devices/{id}/reactivate',
                    [AdminWebController::class, 'deviceReactivate']
                )->name('devices.reactivate');

                Route::post(
                    '/logout',
                    [AdminWebController::class, 'logout']
                )->name('logout');

            });




            /*
            |--------------------------------------------------------------------------
            | Subscriptions
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/subscriptions',
                [AdminWebController::class, 'subscriptions']
            )->name('subscriptions');


            Route::get(
                '/subscriptions/grant',
                [AdminWebController::class, 'subscriptionGrant']
            )->name('subscriptions.grant');


            Route::post(
                '/subscriptions/grant',
                [AdminWebController::class, 'subscriptionGrantStore']
            )->name('subscriptions.grant.store');


            Route::get(
                '/subscriptions/{id}/change-plan',
                [AdminWebController::class, 'subscriptionChangePlan']
            )->name('subscriptions.change-plan');


            Route::post(
                '/subscriptions/{id}/change-plan',
                [AdminWebController::class, 'subscriptionChangePlanStore']
            )->name('subscriptions.change-plan.store');


            Route::get(
                '/subscriptions/{id}/extend',
                [AdminWebController::class, 'subscriptionExtend']
            )->name('subscriptions.extend');


            Route::post(
                '/subscriptions/{id}/extend',
                [AdminWebController::class, 'subscriptionExtendStore']
            )->name('subscriptions.extend.store');


            Route::get(
                '/subscriptions/{id}/reactivate',
                [AdminWebController::class, 'subscriptionReactivate']
            )->name('subscriptions.reactivate');


            Route::post(
                '/subscriptions/{id}/reactivate',
                [AdminWebController::class, 'subscriptionReactivateStore']
            )->name('subscriptions.reactivate.store');


            Route::post(
                '/subscriptions/{id}/cancel',
                [AdminWebController::class, 'subscriptionCancel']
            )->name('subscriptions.cancel');


            Route::get(
                '/subscriptions/{id}',
                [AdminWebController::class, 'subscriptionShow']
            )->name('subscriptions.show');  




            /*
            |--------------------------------------------------------------------------
            | Plans
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/plans',
                [AdminWebController::class, 'plans']
            )->name('plans');


            Route::get(
                '/plans/create',
                [AdminWebController::class, 'planCreate']
            )->name('plans.create');


            Route::post(
                '/plans',
                [AdminWebController::class, 'planStore']
            )->name('plans.store');


            Route::get(
                '/plans/{id}/edit',
                [AdminWebController::class, 'planEdit']
            )->name('plans.edit');


            Route::put(
                '/plans/{id}',
                [AdminWebController::class, 'planUpdate']
            )->name('plans.update');


            Route::post(
                '/plans/{id}/toggle-status',
                [AdminWebController::class, 'planToggleStatus']
            )->name('plans.toggle-status');


            Route::get(
                '/plans/{id}',
                [AdminWebController::class, 'planShow']
            )->name('plans.show');



    







    });