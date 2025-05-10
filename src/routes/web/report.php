<?php

use App\Http\Controllers\Report\ReportController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Routes for all Application Reports
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Report Landing page
    | /reports
    |---------------------------------------------------------------------------
    */
    Route::get('reports', ReportController::class)
        ->name('reports.index')
        ->breadcrumb('Reports');
});
