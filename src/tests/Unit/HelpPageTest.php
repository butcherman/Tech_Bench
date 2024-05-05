<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class HelpPageTest extends TestCase
{
    /**
     * These routes should not have a help page
     */
    protected $bypassRoutes = [
        null,
        'debugbar',
        'horizon',
        'ignition',
        'sanctum',
        'init',
        'login',
        'logout',
        'password',
        'initialize',
        '2fa',
        'azure-login',
        'azure-callback',
        'home',
        'remove-device',
        'send-welcome',
        'restore',
        'check-id',
        'notFound',
        'equipment-list',
        'phone-types',
        'file-types',
        'download',
        'test-email',
    ];

    /**
     * Test to make sure that all routes have a help page
     */
    public function test_help_page_exists()
    {
        $routeList = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('GET', $route->methods);
        })->map(function ($route) {
            return $route->action;
        })->pluck('as')->filter(function ($name) {
            $exploded = explode('.', $name);

            return count(array_intersect($this->bypassRoutes, $exploded)) == 0;
        });

        foreach ($routeList as $route) {
            $this->assertFileExists(resource_path('js/Help/Pages/' . $route . '.vue'));
        }
    }
}
