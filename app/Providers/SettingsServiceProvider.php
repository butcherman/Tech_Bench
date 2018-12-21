<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Config;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        if (Schema::hasTable('settings'))
        {
            $settings = DB::table('settings')->get();
            foreach ($settings as $setting)
            {
                Config([$setting->key => $setting->value]);
            }
        }
    }
}
