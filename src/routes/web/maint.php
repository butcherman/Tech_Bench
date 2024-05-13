<?php

use App\Http\Controllers\Maintenance\LogSettingsController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for Application Maintenance
 */
Route::middleware('auth.secure')->prefix('maintenance')->name('maint.')->group(function () {
    /**
     * Logging and Log Settings
     */
    Route::get('log-settings', [LogSettingsController::class, 'show'])
        ->name('log-settings.show')
        ->breadcrumb('Log Settings', 'admin.index');
    Route::put('log-settings', [LogSettingsController::class, 'update'])
        ->name('log-settings.update');
});