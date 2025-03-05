<?php

use Illuminate\Support\Facades\Route;

Route::get('change-password', function () {
    return 'change password';
})->name('user.change-password.show');
