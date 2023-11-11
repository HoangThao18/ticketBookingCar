<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
        $this->app->bind(\App\Repositories\Points\PointsRepositoryInterface::class, \App\Repositories\Points\PointsRepository::class);
        $this->app->bind(\App\Repositories\User\UserRepositoryInterface::class, \App\Repositories\User\UserRepository::class);
        $this->app->bind(\App\Repositories\Station\StationRepositoryInterface::class, \App\Repositories\Station\StationRepository::class);
        $this->app->bind(\App\Repositories\TimePoints\TimePointsRepositoryInterface::class, \App\Repositories\TimePoints\TimePointsRepository::class);
        $this->app->bind(\App\Repositories\Ticket\TicketRepositoryInterface::class, \App\Repositories\Ticket\TicketRepository::class);
        $this->app->bind(\App\Repositories\Bill\BillRepositoryInterface::class, \App\Repositories\Bill\BillRepository::class);
        $this->app->bind(\App\Repositories\BillDetail\BillDetailRepositoryInterface::class, \App\Repositories\BillDetail\BillDetailRepository::class);
        $this->app->bind(\App\Repositories\News\NewsRepositoryInterface::class, \App\Repositories\News\NewsRepository::class);
        $this->app->bind(\App\Repositories\Job\JobRepositoryInterface::class, \App\Repositories\Job\JobRepository::class);
        $this->app->bind(\App\Repositories\Comment\CommentRepositoryInterface::class, \App\Repositories\Comment\commentRepository::class);
        $this->app->bind(\App\Repositories\Seats\SeatsRepositoryInterface::class, \App\Repositories\Seats\SeatsRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            File::append(
                storage_path('/logs/query.log'),
                '[' . date('Y-m-d H:i:s') . ']' . PHP_EOL . $query->sql . ' [' . implode(', ', $query->bindings) . ']' . PHP_EOL . PHP_EOL
            );
        });
    }
}
