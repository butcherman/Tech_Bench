<?php

use Illuminate\Support\Facades\Route;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

use App\Http\Controllers\Admin\AdminIndexController;
//  User Controllers
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\User\UserRolesController;
use App\Http\Controllers\Admin\User\UserDisabledController;
use App\Http\Controllers\Admin\User\UserPasswordController;
use App\Http\Controllers\Admin\User\UserPasswordPolicyController;
//  App Configuration Controllers
use App\Http\Controllers\Admin\Config\GetConfigController;
use App\Http\Controllers\Admin\Config\SetConfigController;
use App\Http\Controllers\Admin\Config\GetEmailSettingsController;
use App\Http\Controllers\Admin\Config\SetEmailSettingsController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;


use App\Http\Controllers\Admin\BackupController;
use App\Http\Controllers\Admin\ReactivateUserController;
use App\Http\Controllers\Admin\DeactivatedUserController;






use App\Http\Controllers\Admin\Logo\GetLogoController;
use App\Http\Controllers\Admin\Logo\SetLogoController;
use App\Http\Controllers\Admin\Logs\DownloadLogController;
use App\Http\Controllers\Admin\Logs\ViewLogController;
use App\Http\Controllers\Admin\Logs\LogFilesController;
use App\Http\Controllers\Admin\Logs\LogSettingsController;
use App\Http\Controllers\Admin\Logs\SetLogSettingsController;

use App\Http\Controllers\Admin\Modules\ModuleIndexController;
use App\Http\Controllers\Admin\Modules\DownloadModuleController;
use App\Http\Controllers\Admin\Modules\GetModulesOnlineController;


Route::middleware('auth')->prefix('administration')->name('admin.')->group(function() {
    Route::get('/', AdminIndexController::class)
        ->name('index')
        ->breadcrumb('Administration');

    /**
     * User Admin Routes
     */
    Route::prefix('users')->name('users.')->group(function()     {
        Route::get('{user}/enable',   [UserController::class, 'enable'])
            ->name('enable')
            ->withTrashed();
        Route::put('{user}/password', [UserPasswordController::class, 'update'])
            ->name('reset-password.update');
        Route::get('{user}/password', [UserPasswordController::class, 'edit'])
            ->name('reset-password.edit')
            ->breadcrumb('Reset Password', 'admin.users.index');

        Route::get('disabled-users', UserDisabledController::class)
            ->name('disabled')
            ->breadcrumb('Disabled Users', 'admin.users.index');

        Route::resource('password-policy', UserPasswordPolicyController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Password Policy', 'admin.users.index');
        });

        Route::get('{role}/copy', [UserRolesController::class, 'copy'])
                ->name('roles.copy')
                ->breadcrumb('New Role', 'admin.users.roles.index');
        Route::resource('roles', UserRolesController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Roles', 'admin.users.index');
            $breadcrumbs->create('New Role', 'admin.users.roles.index');
            $breadcrumbs->show('View Role', 'admin.users.roles.index');
            $breadcrumbs->edit('Edit Role', 'admin.users.roles.show');
        });
    });
    Route::resource('users', UserController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Select User', 'admin.index');
        $breadcrumbs->create('New User', 'admin.users.index');
        $breadcrumbs->edit('Edit User', 'admin.users.index');
    });

    /**
     * Application Administration Routes
     */
    // Route::get( 'logo',             GetLogoController::class)         ->name('get-logo')  ->breadcrumb('App Logo', '.index');
    // Route::post('logo',             SetLogoController::class)         ->name('set-logo');
    Route::get( 'config',     GetConfigController::class)->name('get-config')->breadcrumb('App Configuration', '.index');
    Route::post('config',     SetConfigController::class)->name('set-config');
    Route::get('email',       GetEmailSettingsController::class)->name('get-email')->breadcrumb('Email Settings', '.get-config');
    Route::post('email',      SetEmailSettingsController::class)->name('set-email');
    Route::get( 'test-email', SendTestEmailController::class)   ->name('test-email');






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
    // Route::get( 'logs',                          LogFilesController::class)      ->name('logs.index')   ->breadcrumb('Logs', 'admin.index');
    // Route::get( 'logs/settings',                 LogSettingsController::class)   ->name('logs.settings')->breadcrumb('Settings', 'admin.logs.index');
    // Route::post('logs/settings',                 SetLogSettingsController::class)->name('logs.set-settings');
    // Route::get('logs/download/{channel}/{file}', DownloadLogController::class)->name('logs.download');
    // Route::get( 'logs/{channel}',                LogFilesController::class)      ->name('logs.channel') ->breadcrumb(fn($channel) => $channel, 'admin.logs.index');
    // Route::get( 'logs/{channel}/{name}',         ViewLogController::class)->name('logs.view')    ->breadcrumb('View Log', 'admin.logs.channel');

    // Route::resource('backups', BackupController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
    // {
    //     $breadcrumbs->index('Backups', 'admin.index');
    // });
});
