<?php

use App\Http\Controllers\Maintenance\BackupSettingsController;
use App\Http\Controllers\Maintenance\DownloadLogController;
use App\Http\Controllers\Maintenance\LogSettingsController;
use App\Http\Controllers\Maintenance\LogsIndexController;
use App\Http\Controllers\Maintenance\ViewLogController;
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
    Route::get('logs', LogsIndexController::class)
        ->name('logs.index')
        ->breadcrumb('Logs', 'admin.index');
    Route::get('logs/{channel}', LogsIndexController::class)
        ->name('logs.show')
        ->breadcrumb(fn(string $channel) => ucfirst($channel), 'maint.logs.index');
    Route::get('logs/{channel}/{logFile}', ViewLogController::class)
        ->name('logs.view')
        ->breadcrumb('View Log File', 'maint.logs.show');
    Route::get('logs/{channel}/{logFile}/download', DownloadLogController::class)
        ->name('logs.download');

    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('settings', [BackupSettingsController::class, 'show'])
            ->name('settings.show')
            ->breadcrumb('Backup Settings', 'admin.index');
        Route::put('settings', [BackupSettingsController::class, 'update'])
            ->name('settings.update');
    });
});