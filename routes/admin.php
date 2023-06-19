<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\User\DeactivatedUserController;
use App\Http\Controllers\Admin\User\PasswordPolicyController;
use App\Http\Controllers\Admin\User\ResetUserPasswordController;
use App\Http\Controllers\Admin\User\SendUserNotificationController;
use App\Http\Controllers\Admin\User\SendWelcomeEmailController;
use App\Http\Controllers\Admin\User\UserAdminController;
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
});
