<?php

use App\Http\Controllers\Reports\ReportController;
use App\Http\Controllers\Reports\ReportParametersController;
use App\Http\Controllers\Reports\RunReportController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Routes for all Application Reports
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->prefix('reports')->name('reports.')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Report Landing page
    | /reports
    |---------------------------------------------------------------------------
    */
    Route::get('/', ReportController::class)
        ->name('index')
        ->breadcrumb('Reports');

    /*
    |---------------------------------------------------------------------------
    | Report Data Pages
    | /reports/{group}/{report-name}
    |---------------------------------------------------------------------------
    */
    Route::get('{group}/{report}', ReportParametersController::class)
        ->name('params')
        ->breadcrumb(fn(string $group, string $report) => Str::headline($report), 'reports.index');

    Route::match(['get', 'put'], '{group}/{report}/run', RunReportController::class)
        ->name('run')
        ->breadcrumb('Report Data', 'reports.params');
});
