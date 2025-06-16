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
        'guest-link.*',
        'home',
        'horizon.*',
        'init.*',
        'initialize',
        'links.expire',
        'links.extend',
        'login',
        'maint.backups.download',
        'maint.backups.run-backup',
        'maint.logs.download',
        'password.*',
        'publicTips.*',
        'storage.local',
        'tech-tips.comments.flag',
        'tech-tips.download',
        'tech-tips.not-found',
        'telescope',
        'two-factor.*',

        // TMP Removals
        // TODO - Finish Adding Help Pages
        'customers.*',
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
