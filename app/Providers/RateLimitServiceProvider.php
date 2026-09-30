<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */

    public function boot(): void
    {

        RateLimiter::for('login', function(Request $request){

            return Limit::perMinute(5)
                ->by(
                    strtolower(
                        $request->input('login')
                    )
                    .
                    '|'.
                    $request->ip()
                );

        });



        RateLimiter::for('api', function(Request $request){

            return Limit::perMinute(120)
                ->by(
                    $request->user()?->id
                    ??
                    $request->ip()
                );

        });



        RateLimiter::for('heartbeat', function(Request $request){

            return Limit::perMinute(10)
                ->by(
                    $request->user()?->id
                    ??
                    $request->ip()
                );

        });



        RateLimiter::for('payment', function(Request $request){

            return Limit::perMinute(10)
                ->by(
                    $request->user()?->id
                    ??
                    $request->ip()
                );

        });




    }

    


}
