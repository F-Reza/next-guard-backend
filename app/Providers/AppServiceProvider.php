<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\GatewayInterface;
use App\Services\Payment\StripeGateway;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        $this->app->bind(
            GatewayInterface::class,
            StripeGateway::class
        );

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
