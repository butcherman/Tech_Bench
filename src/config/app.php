<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [
    'name' => env('APP_NAME', 'Tech Bench'),
    'company_name' => '',
    'logo' => '/images/TechBenchLogo.png',
    'env' => env('APP_ENV', 'production'),
    'debug' => (bool) env('APP_DEBUG', false),
    'url' => env('APP_URL', 'https://localhost'),
    'asset_url' => env('ASSET_URL', null),
    'timezone' => 'UTC',
    'schedule_timezone' => 'UTC',
    'locale' => 'en',
    'fallback_locale' => 'en',
    'faker_locale' => 'en_US',
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    'maintenance' => [
        'driver' => 'file',
    ],

    /**
     * Login Page additional data/links
     *
     * Links must be in form of [
     *      'url' => 'site_url'
     *      'text' => 'text to display'
     *   ]
     */
    'welcome_message' => '',
    'home_links' => [],

    /*
    |--------------------------------------------------------------------------
    | Autoload Service Providers
    |--------------------------------------------------------------------------
    */

    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Karmendra\LaravelAgentDetector\AgentDetectorServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\FortifyServiceProvider::class,
        App\Providers\HorizonServiceProvider::class,
        App\Providers\AppSettingsServiceProvider::class,
    ])->toArray(),

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */

    'aliases' => Facade::defaultAliases()->merge([
        'Timezonelist' => Jackiedo\Timezonelist\Facades\Timezonelist::class,
        'AgentDetector' => Karmendra\LaravelAgentDetector\Facades\AgentDetector::class,
    ])->toArray(),
];
