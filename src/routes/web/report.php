<?php

use App\Http\Controllers\Report\Customer\CustomerFilesReportController;
use App\Http\Controllers\Report\Customer\CustomerSummaryReportController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Report\User\UserActivityReportController;
use App\Http\Controllers\Report\User\UserContributionReportController;
use App\Http\Controllers\Report\User\UserDetailsReportController;
use App\Http\Controllers\Report\User\UserPermissionsReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', ReportController::class)
        ->name('index')
        ->breadcrumb('Reports');

    /***************************************************************************
     * User Reports
     ***************************************************************************/
    // Route::prefix('user-reports')->name('user.')->group(function () {
    //     Route::get('activity-report', [UserActivityReportController::class, 'index'])
    //         ->name('activity')
    //         ->breadcrumb('User Login Activity Report', 'reports.index');
    //     Route::put('activity-report', [UserActivityReportController::class, 'show'])
    //         ->name('run-activity')
    //         ->breadcrumb('Report Details', 'reports.user.activity');
    //     Route::get('contribution-report', [UserContributionReportController::class, 'index'])
    //         ->name('contribution')
    //         ->breadcrumb('User Contribution Report', 'reports.index');
    //     Route::put('contribution-report', [UserContributionReportController::class, 'show'])
    //         ->name('run-contribution')
    //         ->breadcrumb('Report Details', 'reports.user.contribution');
    //     Route::get('user-permissions-report', [UserPermissionsReportController::class, 'index'])
    //         ->name('permissions')
    //         ->breadcrumb('User Permissions Report', 'reports.index');
    //     Route::put('user-permissions-report', [UserPermissionsReportController::class, 'show'])
    //         ->name('run-permissions')
    //         ->breadcrumb('Report Details', 'reports.user.permissions');
    //     Route::get('user-details-report', [UserDetailsReportController::class, 'index'])
    //         ->name('details')
    //         ->breadcrumb('User Details Report', 'reports.index');
    //     Route::put('user-details-report', [UserDetailsReportController::class, 'show'])
    //         ->name('run-details')
    //         ->breadcrumb('Report Details', 'reports.user.details');
    // });

    /***************************************************************************
     * Customer Reports
     ***************************************************************************/
    // Route::prefix('customer-reports')->name('customer.')->group(function () {
    //     Route::get('summary-report', [CustomerSummaryReportController::class, 'index'])
    //         ->name('summary')
    //         ->breadcrumb('Customer Summary Report', 'reports.index');
    //     Route::put('summary-report', [CustomerSummaryReportController::class, 'show'])
    //         ->name('run-summary')
    //         ->breadcrumb('Report Data', 'reports.customer.summary');
    //     Route::get('files-report', [CustomerFilesReportController::class, 'index'])
    //         ->name('files')
    //         ->breadcrumb('Customer Files Report', 'reports.index');
    //     Route::put('files-report', [CustomerFilesReportController::class, 'show'])
    //         ->name('run-files')
    //         ->breadcrumb('Report Data', 'reports.customer.files');
    // });
});
