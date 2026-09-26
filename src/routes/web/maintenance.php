<?php

use App\Http\Controllers\Maintenance\Backup\BackupIndexController;
use App\Http\Controllers\Maintenance\Backup\BackupSettingsController;
use App\Http\Controllers\Maintenance\Backup\DeleteBackupController;
use App\Http\Controllers\Maintenance\Backup\DownloadBackupController;
use App\Http\Controllers\Maintenance\Backup\RestoreBackupController;
use App\Http\Controllers\Maintenance\Backup\RunBackupController;
use App\Http\Controllers\Maintenance\Backup\ShowAllBackupsController;
use App\Http\Controllers\Maintenance\Backup\UploadBackupController;
use App\Http\Controllers\Maintenance\Logs\DownloadLogController;
use App\Http\Controllers\Maintenance\Logs\LogLoadMoreController;
use App\Http\Controllers\Maintenance\Logs\LogSettingsController;
use App\Http\Controllers\Maintenance\Logs\LogsIndexController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for Application Maintenance
 */
Route::middleware('auth.secure')->prefix('maintenance')->name('maint.')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Logging and Log Settings
    | /maintenance/logs
    |---------------------------------------------------------------------------
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

        Route::get('{logFile}/load-more', LogLoadMoreController::class)
            ->name('load');
        Route::get('{logFile}/download', DownloadLogController::class)
            ->name('download');
        Route::get('{logFile}', LogsIndexController::class)
            ->name('show')
            ->breadcrumb(fn (string $logFile) => $logFile, 'maint.logs.index');
        Route::get('/', LogsIndexController::class)
            ->name('index')
            ->breadcrumb('Logs', 'admin.index');
    });

    /*
     |---------------------------------------------------------------------------
     | Backup and Backup Maintenance
     | /maintenance/backups
     |---------------------------------------------------------------------------
     */
    Route::prefix('backups')->name('backups.')->group(function () {
        Route::get('all', ShowAllBackupsController::class)
            ->name('show-all')
            ->breadcrumb('All Backup Files', 'maint.backups.index');
        Route::put('settings', BackupSettingsController::class)->name('update');
        Route::controller(UploadBackupController::class)
            ->prefix('upload')
            ->name('upload.')
            ->group(function () {
                Route::get('/', 'create')
                    ->name('create')
                    ->breadcrumb('Upload Backup File', 'maint.backups.index');
                Route::post('/', 'store')->name('store');
            });
        Route::get('download/{backupName:backup_name}', DownloadBackupController::class)
            ->name('download');
        Route::get('run-backup', RunBackupController::class)->name('run-backup');
        Route::delete('delete-backup/{backupName:backup_name}', DeleteBackupController::class)
            ->name('delete');
        Route::put('restore', RestoreBackupController::class)->name('restore');
        Route::get('/', BackupIndexController::class)
            ->name('index')
            ->breadcrumb('Backups', 'admin.index');
    });
});
