<?php

use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Report\User\UserActivityReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', ReportController::class)->name('index')
        ->breadcrumb('Reports');

    Route::prefix('user-reports')->name('user.')->group(function () {
        Route::get('activity-report', [UserActivityReportController::class, 'index'])
            ->name('activity')
            ->breadcrumb('User Login Activity Report', 'reports.index');
        Route::put('activity-report', [UserActivityReportController::class, 'show'])
            ->name('run-activity')
            ->breadcrumb('User Login Activity Report', 'reports.index');
    });
});