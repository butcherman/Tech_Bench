<?php

use App\Http\Controllers\TechTip\TechTipSettingsController;
use App\Http\Controllers\TechTip\TechTipTypeController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Tech Tip Routes
|-------------------------------------------------------------------------------
*/
Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Tech Tip Administration
    | /administration/tech-tips
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration/tech-tips')->name('admin.tech-tips.')->group(function () {
        /*
        |-----------------------------------------------------------------------
        | Tech Tip Settings
        | /administration/tech-tips/settings
        |-----------------------------------------------------------------------
        */
        Route::controller(TechTipSettingsController::class)
            ->prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', 'edit')
                    ->name('edit')
                    ->breadcrumb('Tech Tip Settings', 'admin.index');
                Route::put('/', 'update')->name('update');
            });

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Types
        | /administration/tech-tips/tip-types
        |-----------------------------------------------------------------------
        */
        Route::resource('tip-types', TechTipTypeController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Tech Tip Types', 'admin.index');
            })->except(['create', 'edit', 'show']);
    });
});
