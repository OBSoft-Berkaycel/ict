<?php

namespace App\Providers;

use App\Libraries\Services\Interfaces\OrderListServiceInterface;
use App\Libraries\Services\OrderListService;
use Illuminate\Support\ServiceProvider;

class SignServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderListServiceInterface::class, OrderListService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
