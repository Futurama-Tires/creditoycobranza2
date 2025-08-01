<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\NetsuiteService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NetsuiteService::class, function ($app) {
            return new NetsuiteService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
