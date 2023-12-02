<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\DashboardController;
use App\Http\Controllers\Home\DownloadController;
use App\Http\Controllers\Home\UploadImageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware(['auth', 'user_security'])->group(function () {
    Route::get('dashboard', DashboardController::class)->name('dashboard')->breadcrumb('Dashboard');
    Route::get('about', AboutController::class)->name('about')->breadcrumb('About', 'dashboard');

    Route::post('upload-image', UploadImageController::class)->name('upload-image');

    Route::get('random-stuff/{item}', function (string $item) {
        // return $item;
        if ($item === 'blah') {
            return Inertia::render('Home/Dashboard');
        }

        abort(404);
    })->name('bypass-test');

    /**
     * Temporary Testing Routes
     */
    Route::get('tech-tips', function () {
        return 'tech-tips.index';
    })->name('tech-tips.index');

    Route::get('tech-tips/{name}', function ($name) {
        return 'tech-tips.show';
    })->name('tech-tips.show');
});

/**
 * Non-authenticated Routes
 */
Route::get('download/{file_id}/{file_name}', DownloadController::class)->name('download');
