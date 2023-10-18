<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(\App\Repositories\Car\CarRepositoryInterface::class, \App\Repositories\Car\CarRepository::class);
        $this->app->bind(\App\Repositories\Trip\TripRepositoryInterface::class, \App\Repositories\Trip\TripRepository::class);
        $this->app->bind(\App\Repositories\Route\RouteRepositoryInterface::class, \App\Repositories\Route\RouteRepository::class);
        $this->app->bind(\App\Repositories\User\UserRepositoryInterface::class, \App\Repositories\User\UserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
