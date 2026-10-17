<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadFileController;
use App\Http\Controllers\Home\UploadImageController;
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
        ->name('upload-image');
});

/*
|-------------------------------------------------------------------------------
| Download a File
|-------------------------------------------------------------------------------
*/
Route::get('download/{file}/{fileName}', DownloadFileController::class)->name('download');

// FIXME:  Delete these routes
Route::get('public-tips', function () {
    return 'public tech tips';
})->name('publicTips.index');
