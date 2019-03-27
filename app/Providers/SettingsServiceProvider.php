<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
		try
		{
			if(Schema::hasTable('settings'))
			{
				$settings = DB::table('settings')->get();
				foreach($settings as $setting)
				{
					Config([$setting->key => $setting->value]);
				}
			}
		}
		catch(\Illuminate\Database\QueryException $e)
		{
            //  
		}
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
