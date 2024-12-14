<?php

use App\Http\Controllers\Report\Customer\CustomerFilesReportController;
use App\Http\Controllers\Report\Customer\CustomerSummaryReportController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Report\ReportParametersController;
use App\Http\Controllers\Report\RunReportController;
use App\Http\Controllers\Report\User\UserActivityReportController;
use App\Http\Controllers\Report\User\UserContributionReportController;
use App\Http\Controllers\Report\User\UserDetailsReportController;
use App\Http\Controllers\Report\User\UserPermissionsReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::middleware('auth.secure')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', ReportController::class)
        ->name('index')
        ->breadcrumb('Reports');

    Route::get('{group}/{report}', ReportParametersController::class)
        ->name('params')
        ->breadcrumb(fn(string $group, string $report) => Str::headline($report), 'reports.index');
    Route::put('{group}/{report}', RunReportController::class)->name('run');
});
