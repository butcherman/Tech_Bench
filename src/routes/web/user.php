<?php

use App\Http\Controllers\User\UserPasswordController;
use App\Http\Middleware\CheckPasswordExpiration;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->prefix('user')->name('user.')->group(function () {
    Route::get('change-password', UserPasswordController::class)
        ->name('change-password.show')
        ->withoutMiddleware(CheckPasswordExpiration::class);
});

Route::get('user-settings', function () {
    return 'user-settings';
})->name('user.user-settings.show');
