<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\Config\AppConfigController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\Config\LogoController;
use App\Http\Controllers\Admin\Config\SecurityController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;
use App\Http\Controllers\Admin\Maintenance\LogsController;
use App\Http\Controllers\Admin\Maintenance\ViewLogController;
use App\Http\Controllers\Admin\User\DeactivatedUserController;
use App\Http\Controllers\Admin\User\PasswordPolicyController;
use App\Http\Controllers\Admin\User\ResetUserPasswordController;
use App\Http\Controllers\Admin\User\SendUserNotificationController;
use App\Http\Controllers\Admin\User\SendWelcomeEmailController;
use App\Http\Controllers\Admin\User\UserAdminController;
use App\Http\Controllers\Admin\User\UserRoleController;
use App\Http\Controllers\Admin\User\UserSettingsController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdminIndexController::class)->name('index')->breadcrumb('Administration');

    /**
     * User Administration
     */
    Route::prefix('user')->name('users.')->group(function () {
        Route::get('deactivated-users', DeactivatedUserController::class)->name('deactivated')->breadcrumb('Deactivated Users', 'admin.users.index');
        Route::get('{user}/resend-welcome-email', SendWelcomeEmailController::class)->name('send-welcome');
        Route::get('{user}/restore', [UserAdminController::class, 'restore'])->withTrashed()->name('restore');
        Route::post('{user}/reset-password', ResetUserPasswordController::class)->name('reset-password');
        Route::post('{user}/send-notification', SendUserNotificationController::class)->name('send-notification');

        Route::get('password-policy', [PasswordPolicyController::class, 'get'])->name('password-policy.get')->breadcrumb('Password Policy', 'admin.users.index');
        Route::post('password-policy', [PasswordPolicyController::class, 'set'])->name('password-policy.set');
    });
    Route::resource('users', UserAdminController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('User Administration', 'admin.index')
            ->create('New User', 'admin.users.index')
            ->show('User Details', 'admin.users.index')
            ->edit('Edit User', 'admin.users.index');
    })->withTrashed();

    Route::get('user-settings', [UserSettingsController::class, 'get'])->name('user-settings.get')->breadcrumb('User Settings', 'admin.users.index');
    Route::post('user-settings', [UserSettingsController::class, 'set'])->name('user-settings.set');

    Route::get('user-roles/{user_role}/copy', [UserRoleController::class, 'copy'])->name('user-roles.copy')->breadcrumb('Copy Role', 'admin.user-roles.index');
    Route::resource('user-roles', UserRoleController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Roles and Permissions', 'admin.index')
            ->create('Build New Role', 'admin.user-roles.index')
            ->show('View Role', 'admin.user-roles.index')
            ->edit('Modify Role', 'admin.user-roles.show');
    });

    /**
     * App Administration
     */
    Route::prefix('logo')->name('logo.')->group(function() {
        Route::get('/', [LogoController::class, 'get'])->name('get')->breadcrumb('Logo', 'admin.index');
        Route::post('/', [LogoController::class, 'set'])->name('set');
    });

    Route::prefix('config')->name('config.')->group(function() {
        Route::get('/', [AppConfigController::class, 'get'])->name('get')->breadcrumb('Application Configuration', 'admin.index');
        Route::post('/', [AppConfigController::class, 'set'])->name('set');
    });

    Route::prefix('email-settings')->name('email.')->group(function() {
        Route::get('/', [EmailSettingsController::class, 'get'])->name('get')->breadcrumb('Email Settings', 'admin.index');
        Route::post('/', [EmailSettingsController::class, 'set'])->name('set');
        Route::get('send-test-email', SendTestEmailController::class)->name('test');
    });

    Route::prefix('security')->name('security.')->group(function() {
        Route::get('/', [SecurityController::class, 'index'])->name('index')->breadcrumb('Security Settings', 'admin.index');
        Route::get('create', [SecurityController::class, 'create'])->name('create')->breadcrumb('Upload SSL Certificate', 'admin.security.index');
        Route::post('create', [SecurityController::class, 'store'])->name('store');
        Route::delete('/', [SecurityController::class, 'destroy'])->name('destroy');
    });

    /**
     * App Maintenance
     */
    Route::prefix('logs')->name('logs.')->group(function() {
        Route::get('/', LogsController::class)->name('index')->breadcrumb('App Logs', 'admin.index');
        Route::get('{channel}', LogsController::class)->name('channel')->breadcrumb('Log List', 'admin.logs.index');
        Route::get('{channel}/{log}', ViewLogController::class)->name('view')->breadcrumb('Log Details', '.channel');
    });
});
