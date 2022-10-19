<?php

use Illuminate\Support\Facades\Route;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

use App\Http\Controllers\Admin\AdminIndexController;

use App\Http\Controllers\Admin\BackupController;

use App\Http\Controllers\Admin\GetPasswordPolicyController;
use App\Http\Controllers\Admin\SetPasswordPolicyController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRolesController;
use App\Http\Controllers\Admin\ReactivateUserController;
use App\Http\Controllers\Admin\DeactivatedUserController;

use App\Http\Controllers\Admin\Config\GetConfigController;
use App\Http\Controllers\Admin\Config\SetConfigController;
use App\Http\Controllers\Admin\Email\SendTestEmailController;
use App\Http\Controllers\Admin\Email\GetEmailSettingsController;
use App\Http\Controllers\Admin\Email\SetEmailSettingsController;

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
use App\Http\Controllers\Admin\UserDisabledController;
use App\Http\Controllers\Admin\UserPasswordController;
use App\Http\Controllers\Admin\UserPasswordPolicyController;
use App\Models\UserRoles;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function()
{
    Route::get('/', AdminIndexController::class)
        ->name('index')
        ->breadcrumb('Administration');

    /**
     * User Admin Routes
     */
    Route::prefix('users')->name('users.')->group(function()
    {
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

        Route::resource('password-policy', UserPasswordPolicyController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
        {
            $breadcrumbs->index('Password Policy', 'admin.users.index');
        });

        Route::resource('roles', UserRolesController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
        {
            $breadcrumbs->index('Roles', 'admin.users.index');
            $breadcrumbs->create('New Role', 'admin.users.roles.index');
            $breadcrumbs->show('View Role', 'admin.users.roles.index');
            $breadcrumbs->edit('Edit Role', 'admin.users.roles.index');
        });
    });
    Route::resource('users', UserController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
    {
        $breadcrumbs->index('Select User', 'admin.index');
        $breadcrumbs->create('New User', 'admin.users.index');
        $breadcrumbs->edit('Edit User', 'admin.users.index');
    });


    // Route::get('deactivated-users', DeactivatedUserController::class)->name('deactivated-users')    ->breadcrumb('Disabled Users', '.index');
    // Route::get('{user}/activate',   ReactivateUserController::class) ->name('reactivate-user')      ->breadcrumb('Active Users', '.index');
    // Route::get('password-policy',   GetPasswordPolicyController::class)->name('password-policy')    ->breadcrumb('Password Policy', '.index');
    // Route::put('password-policy',   SetPasswordPolicyController::class)->name('set-password-policy');

    // Route::resource('user', UserController::class)
    //     ->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs)
    //     {
    //         $breadcrumbs->index('Users', 'admin.index');
    //         $breadcrumbs->create('New User', '.index');
    //         $breadcrumbs->edit('Edit User', '.index');
    //     });



    /**
     * User Roles and Permission Routes
     */
    // Route::prefix('user-roles')->name('user-roles.')->group(function()
    // {
    //     Route::get('/',                  [UserRolesController::class, 'index'])  ->name('index') ->breadcrumb('Roles', 'admin.index');
    //     Route::get('create/{baseline?}', [UserRolesController::class, 'create']) ->name('create')->breadcrumb('New Role', '.index');
    //     Route::get('{role}/edit',        [UserRolesController::class, 'edit'])   ->name('edit')  ->breadcrumb('Edit Role', '.index');
    //     Route::put('{role}/edit',        [UserRolesController::class, 'update']) ->name('update');
    //     Route::post('store',             [UserRolesController::class, 'store'])   ->name('store');
    //     Route::delete('{role}',          [UserRolesController::class, 'destroy'])->name('destroy');
    // });

    /**
     * Application Administration Routes
     */
    // Route::get( 'logo',             GetLogoController::class)         ->name('get-logo')  ->breadcrumb('App Logo', '.index');
    // Route::post('logo',             SetLogoController::class)         ->name('set-logo');
    // Route::get( 'config',           GetConfigController::class)       ->name('get-config')->breadcrumb('App Configuration', '.index');
    // Route::post('config',           SetConfigController::class)       ->name('set-config');
    // Route::get( 'email',            GetEmailSettingsController::class)->name('get-email') ->breadcrumb('Email Settings', '.index');
    // Route::post('email',            SetEmailSettingsController::class)->name('set-email');
    // Route::get( 'test-email',       SendTestEmailController::class)   ->name('test-email');

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
