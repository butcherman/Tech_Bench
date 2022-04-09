<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRolesController;
use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\Config\GetConfigController;
use App\Http\Controllers\Admin\Config\SetConfigController;
use App\Http\Controllers\Admin\ReactivateUserController;
use App\Http\Controllers\Admin\DeactivatedUserController;
use App\Http\Controllers\Admin\Email\GetEmailSettingsController;
use App\Http\Controllers\Admin\Email\SendTestEmailController;
use App\Http\Controllers\Admin\Email\SetEmailSettingsController;
use App\Http\Controllers\Admin\GetPasswordPolicyController;
use App\Http\Controllers\Admin\Logo\GetLogoController;
use App\Http\Controllers\Admin\Logo\SetLogoController;
use App\Http\Controllers\Admin\Logs\LogFilesController;
use App\Http\Controllers\Admin\SetPasswordPolicyController;
use App\Http\Controllers\Admin\Logs\LogSettingsController;
use App\Http\Controllers\Admin\Logs\SetLogSettingsController;
use App\Http\Controllers\Admin\Logs\ViewLogController;
use App\Http\Controllers\Admin\Modules\DownloadModuleController;
use App\Http\Controllers\Admin\Modules\GetModulesOnlineController;
use App\Http\Controllers\Admin\Modules\ModuleIndexController;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function()
{
    /**
     * User Admin Routes
     */
    Route::get('/',                 AdminIndexController::class)     ->name('index');
    Route::get('deactivated-users', DeactivatedUserController::class)->name('deactivated-users');
    Route::get('{user}/activate',   ReactivateUserController::class) ->name('reactivate-user');
    Route::get('password-policy',   GetPasswordPolicyController::class)->name('password-policy');
    Route::put('password-policy',   SetPasswordPolicyController::class)->name('set-password-policy');

    Route::resource('user',         UserController::class);
    Route::resource('user-roles',   UserRolesController::class);

    /**
     * Application Administration Routes
     */
    Route::get( 'logo',             GetLogoController::class)  ->name('get-logo');
    Route::post('logo',             SetLogoController::class)  ->name('set-logo');
    Route::get( 'config',           GetConfigController::class)->name('get-config');
    Route::post('config',           SetConfigController::class)->name('set-config');
    Route::get( 'email',            GetEmailSettingsController::class)->name('get-email');
    Route::post('email',            SetEmailSettingsController::class)->name('set-email');
    Route::get( 'test-email',       SendTestEmailController::class)->name('test-email');

    /**
     * Add On Module Administration Routes
     */
    // Route::prefix('modules')->name('modules.')->group(function()
    // {
    //     Route::get('/',             ModuleIndexController::class)->name('index');
    //     Route::get('get-online',    GetModulesOnlineController::class)->name('get-online');
    //     Route::post('download',     DownloadModuleController::class)->name('download');
    // });

    /**
     * Application Maintenance Routes
     */
    Route::get( 'logs',           LogFilesController::class)->name('logs.index');
    Route::get( 'logs/settings',  LogSettingsController::class)->name('logs.settings');
    Route::post('logs/settings',  SetLogSettingsController::class)->name('logs.set-settings');
    Route::get( 'logs/{channel}', LogFilesController::class)->name('logs.channel');
    Route::get( 'logs/{channel}/{name}', ViewLogController::class)->name('logs.view');

    Route::resource('backups', BackupController::class);
});
