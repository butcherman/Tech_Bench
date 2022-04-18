<?php

use App\Http\Controllers\Reports\ReportsIndexController;
use App\Http\Controllers\Reports\UserLoginReportController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for running any system reports
 */
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function()
{
    Route::get('/', ReportsIndexController::class)->name('index');

    Route::resource('user-login-report', UserLoginReportController::class);
});
