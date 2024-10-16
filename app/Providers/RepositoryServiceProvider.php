<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\Repository\Interfaces\OrderRepositoryInterface;
use App\Libraries\Repository\Interfaces\ProductRepositoryInterface;
use App\Libraries\Repository\OrderRepository;
use App\Libraries\Repository\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->singleton(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
