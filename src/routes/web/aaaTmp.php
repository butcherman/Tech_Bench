<?php

use Illuminate\Support\Facades\Route;

Route::get('user-settings', function () {
    return 'user-settings';
})->name('user.user-settings.show');

Route::get('change-password', function () {
    return 'change password';
})->name('user.change-password.show');
