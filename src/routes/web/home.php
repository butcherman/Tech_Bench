<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
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
});

Route::get('download/{file}/{fileName}', function () {
    return 'test download';
})    // DownloadFileController::class)
    ->name('download');
