<?php

namespace App\Providers;

use App\Facades\CacheHelper;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services and register the customer Facades.
     */
    public function boot(): void
    {
        $this->app->bind('CacheData', CacheHelper::class);
    }
}
