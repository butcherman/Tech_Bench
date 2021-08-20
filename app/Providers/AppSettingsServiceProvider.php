<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        /*
        *   All App Settings that can be adjusted are stored in the database - retrieve them here and assign to the config
        */
        if(Schema::hasTable('app_settings'))
        {
            $settings = DB::table('app_settings')->get();
            foreach($settings as $setting)
            {
                config([$setting->key => $setting->value]);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        //
    }
}
