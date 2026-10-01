<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Payment\GatewayInterface;
use App\Services\Payment\StripeGateway;
use Illuminate\Pagination\Paginator;
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
        Paginator::useBootstrapFive();
    }
}
