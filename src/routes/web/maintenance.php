<?php

use App\Http\Controllers\Maintenance\Backup\UploadBackupController;
use App\Http\Controllers\Maintenance\Backup\BackupIndexController;
use App\Http\Controllers\Maintenance\Backup\BackupSettingsController;
use App\Http\Controllers\Maintenance\Backup\DeleteBackupController;
use App\Http\Controllers\Maintenance\Backup\DownloadBackupController;
use App\Http\Controllers\Maintenance\Backup\RunBackupController;
use App\Http\Controllers\Maintenance\Logs\LogSettingsController;
use App\Http\Controllers\Maintenance\Logs\LogsIndexController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for Application Maintenance
 */
Route::middleware('auth.secure')->prefix('maintenance')->name('maint.')->group(function () {
    /**
     * Logging and Log Settings
     */
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::controller(LogSettingsController::class)
            ->name('settings.')
            ->group(function () {
                Route::get('settings', 'show')
                    ->name('show')
                    ->breadcrumb('Log Settings', 'maint.logs.index');
                Route::put('settings', 'update')->name('update');
            });

        Route::get('/', LogsIndexController::class)->name('index')->breadcrumb('Logs', 'admin.index');
    });
    // Route::get('log-settings', [LogSettingsController::class, 'show'])
    //     ->name('log-settings.show')
    //     ->breadcrumb('Log Settings', 'admin.index');
    // Route::put('log-settings', [LogSettingsController::class, 'update'])
    //     ->name('log-settings.update');
    // Route::get('logs', LogsIndexController::class)
    //     ->name('logs.index')
    //     ->breadcrumb('Logs', 'admin.index');
    // Route::get('logs/{channel}', LogsIndexController::class)
    //     ->name('logs.show')
    //     ->breadcrumb(fn (string $channel) => ucfirst($channel), 'maint.logs.index');
    // Route::get('logs/{channel}/{logFile}', ViewLogController::class)
    //     ->name('logs.view')
    //     ->breadcrumb('View Log File', 'maint.logs.show');
    // Route::get('logs/{channel}/{logFile}/download', DownloadLogController::class)
    //     ->name('logs.download');

    /*
     |---------------------------------------------------------------------------
     | Backup and Backup Maintenance
     | /maintenance/backups
     |---------------------------------------------------------------------------
     */
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::controller(BackupSettingsController::class)
            ->name('settings.')
            ->group(function () {
                Route::get('settings', 'show')
                    ->name('show')
                    ->breadcrumb('Backup Settings', 'maint.backups.index');
                Route::put('settings',  'update')
                    ->name('update');
            });
        Route::post('upload-backup', UploadBackupController::class)
            ->name('upload');
        Route::get('download/{backupName}', DownloadBackupController::class)
            ->name('download');
        Route::get('run-backup', RunBackupController::class)->name('run-backup');
        Route::delete('delete-backup/{backupName}', DeleteBackupController::class)
            ->name('delete');
        Route::get('/', BackupIndexController::class)
            ->name('index')
            ->breadcrumb('Backups', 'admin.index');
    });
});
