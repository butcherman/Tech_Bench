<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\UploadImageController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Primary/Non-Specific Routes for Authenticated Users
|-------------------------------------------------------------------------------
*/
Route::middleware('auth.secure')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard')
        ->breadcrumb('Dashboard');

    Route::get('about', AboutController::class)
        ->name('about')
        ->breadcrumb('About', 'dashboard');

    Route::post('upload-image/{folder?}', UploadImageController::class)
        ->name('upload-image')
        ->withoutMiddleware(ValidateCsrfToken::class);
});

Route::get('download/{file}/{fileName}', function () {
    return 'test download';
})    // DownloadFileController::class)
    ->name('download');
