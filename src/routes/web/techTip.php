<?php

use App\Http\Controllers\TechTip\TechTipSettingsController;
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
    });
});
