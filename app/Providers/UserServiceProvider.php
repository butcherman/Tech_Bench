<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view::composer(
            /** @scrutinizer ignore-type */ '*',
            'App\Http\ViewComposers\UserComposer'
        );
        
        view::composer(
            'layouts.app',
            'App\Http\ViewComposers\NavBarComposer'
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
