<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppSettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        /**
         * We will only Dynamically load the config if it has not been cached
         */
        if (File::missing('../../bootstrap/cache/config.php')) {
            try {
                /*
                *   All App Settings that can be adjusted are stored in the database - retrieve them here and assign to the config
                */
                if (Schema::hasTable('app_settings')) {
                    $settings = DB::table('app_settings')->get();
                    foreach ($settings as $setting) {
                        config([$setting->key => $setting->value]);
                    }
                }
            // @codeCoverageIgnoreStart
            } catch (Exception $e) {
                report($e);
            }
            // @codeCoverageIgnoreEnd
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
