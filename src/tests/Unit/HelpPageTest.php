<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class HelpPageTest extends TestCase
{
    /**
     * These routes should not have a help page.
     *
     * @var array<int, string>
     */
    protected $bypassRoutes = [
        '*.restore',
        'admin.test-email',
        'azure*',
        'debugbar.*',
        'download',
        'dusk.*',
        'home',
        'horizon.*',
        'init.*',
        'initialize',

        'login',
        'password.*',
        'two-factor.*',
        'telescope',
        'guest-link.*',
        'publicTips.*',
        'storage.local',
        'maint.logs.download',
        'maint.backups.download',
        'maint.backups.run-backup',

        // TMP Removals
        'customers.*',
        'equipment.*',
        'equipment-data.*',
        'links.*',
        'user.*',
        'reports.*',
        'tech-tips.*',

        'admin.tech-tips.*',
        'admin.links.*',
        'admin.file-types.*',
        'admin.phone-types.*',
    ];

    /*
    |---------------------------------------------------------------------------
    | Test to verify that all GET routes have a Help Page available.
    |---------------------------------------------------------------------------
    */
    public function test_help_page_exists(): void
    {
        $routeList = collect(Route::getRoutes())->filter(function ($route) {
            return in_array('GET', $route->methods);
        })->map(function ($route) {
            return $route->action;
        })->pluck('as')->filter(function ($name) {
            // return !is_null($name);
            if (is_null($name)) {
                return false;
            }

            foreach ($this->bypassRoutes as $bypass) {
                if (Str::is($bypass, $name)) {
                    return false;
                }
            }

            return true;
        });

        foreach ($routeList as $route) {
            $path = str_replace('.', DIRECTORY_SEPARATOR, $route);

            $this->assertFileExists(
                resource_path('js/Help/Routes/' . $path . '.vue')
            );
        }
    }
}
