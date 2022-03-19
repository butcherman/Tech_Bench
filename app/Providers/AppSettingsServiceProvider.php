<?php

namespace App\Providers;

use Exception;
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
        try
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
        catch(Exception $e)
        {
            report($e);
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
