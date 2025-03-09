<?php

use Illuminate\Support\Facades\Route;

Route::get('change-password', function () {
    return 'change password';
})->name('user.change-password.show');

Route::get('user-settings', function () {
    return 'user settings';
})->name('user.user-settings.show');
