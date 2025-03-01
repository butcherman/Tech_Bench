<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadFileController;
use App\Http\Controllers\Home\TypographyController;
use App\Http\Controllers\Home\UploadImageController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

Route::get('change-password', function () {
    return 'change password';
})->name('user.change-password.show');
