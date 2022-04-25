<?php

use Illuminate\Support\Facades\Route;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;

use App\Http\Controllers\Reports\ReportsIndexController;
use App\Http\Controllers\Reports\UserLoginReportController;

/**
 * Routes for running any system reports
 */
Route::middleware('auth')->prefix('reports')->name('reports.')->group(function()
{
    Route::get('/', ReportsIndexController::class)->name('index')->breadcrumb('Reports');

    Route::prefix('user-login-report')->name('user-login-report.')->group(function()
    {
        Route::get('/',        [UserLoginReportController::class, 'index'])->name('index')->breadcrumb('User Login Report', 'reports.index');
        Route::put('{report}', [UserLoginReportController::class, 'update'])->name('update')->breadcrumb('Report Details', 'reports.user-login-report.index');
    });
});
