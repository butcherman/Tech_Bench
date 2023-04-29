<?php

use App\Http\Controllers\Reports\ReportsIndexController;
use App\Http\Controllers\Reports\UserLoginReportController;
use App\Http\Controllers\Reports\UserPermissionsReportController;
use Illuminate\Support\Facades\Route;

/**
 * Routes for running any system reports
 */
// Route::middleware('auth')->prefix('reports')->name('reports.')->group(function()
// {
//     Route::get('/', ReportsIndexController::class)->name('index')->breadcrumb('Reports');

//     Route::prefix('user-login-report')->name('user-login-report.')->group(function()
//     {
//         Route::get('/',        [UserLoginReportController::class, 'index'])->name('index')->breadcrumb('User Login Report', 'reports.index');
//         Route::put('{report}', [UserLoginReportController::class, 'update'])->name('update')->breadcrumb('Report Details', 'reports.user-login-report.index');
//         Route::get('{report}', [UserLoginReportController::class, 'show'])->name('show');
//     });

//     Route::prefix('user-permissions-report')->name('user-permissions-report.')->group(function()
//     {
//         Route::get('/',        [UserPermissionsReportController::class, 'index'])->name('index')->breadcrumb('User Permissions Report', 'reports.index');
//         Route::put('{report}', [UserPermissionsReportController::class, 'update'])->name('update')->breadcrumb('Report Details', 'reports.user-permissions-report.index');
//         Route::get('{report}', [UserPermissionsReportController::class, 'show'])->name('show');
//     });
// });
