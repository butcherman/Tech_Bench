<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
// use App\Http\Controllers\Home\DownloadFileController;
// use App\Http\Controllers\Home\UploadImageController;
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

    // Route::post('upload-image/{folder?}', UploadImageController::class)
    //     ->name('upload-image')
    //     ->withoutMiddleware(ValidateCsrfToken::class);
});

/*
|-------------------------------------------------------------------------------
| Download a File
|-------------------------------------------------------------------------------
*/
// Route::get('download/{file}/{fileName}', DownloadFileController::class)->name('download');

/*
|---------------------------------------------------------------------------
| TMP for testing
|---------------------------------------------------------------------------
*/
// TODO - REMOVE ME
Route::get('download', function () {
    return 'download';
})->name('download');
Route::get('invalid-1', function () {
    return 'customer-sites';
})->name('customers.sites.show');
Route::get('invalid-2', function () {
    return 'tech tips';
})->name('tech-tips.show');
Route::get('invalid-3', function () {
    return 'links';
})->name('links.show');
Route::get('invalid-4', function () {
    return 'guest links';
})->name('guest-link.show');
Route::get('invlid-5', function () {
    return 'public tips';
})->name('publicTips.show');
