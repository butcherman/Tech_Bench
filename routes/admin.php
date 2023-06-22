<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\LogoController;
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
    Route::get('logo', [LogoController::class, 'get'])->name('logo.get')->breadcrumb('Logo', 'admin.index');
    Route::post('logo', [LogoController::class, 'set'])->name('logo.set');
});
