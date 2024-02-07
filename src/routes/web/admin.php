<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\User\SendWelcomeEmailController;
use App\Http\Controllers\Admin\User\UserAdministrationController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {

    Route::get('/', AdminController::class)->name('index')
        ->breadcrumb('Administration');

    /**
     * User Administration
     */
    Route::prefix('users')->name('user.')->group(function () {
        Route::get('{user}/resend-welcome-email', SendWelcomeEmailController::class)
            ->name('send-welcome');
        Route::post('send-reset-password-link', [PasswordResetLinkController::class, 'store'])
            ->name('password-link');
    });
    Route::resource('user', UserAdministrationController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('User Administration', 'admin.index')
                ->create('New User', 'admin.user.index')
                ->show('User Details', 'admin.user.index')
                ->edit('Edit User Details', 'admin.user.show');
        })->withTrashed();

    // Route::get('basic-settings', [BasicSettingsController::class, 'show'])
    //     ->name('basic-settings.show');
    // Route::put('basic-settings', [BasicSettingsController::class, 'update'])
    //     ->name('basic-settings.update');

    // Route::get('email-settings', [EmailSettingsController::class, 'show'])
    //     ->name('email-settings.show');
    // Route::put('email-settings', [EmailSettingsController::class, 'update'])
    //     ->name('email-settings.update');

    // Route::resource('users', UserAdministrationController::class);

});
