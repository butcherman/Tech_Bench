<?php

use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerIdController;
use App\Http\Controllers\Customer\CustomerSearchController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*******************************************************************************
 *                          Customer Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::post('search', CustomerSearchController::class)->name('search');
        Route::get('check-id/{custId}', CustomerIdController::class)->name('check-id');
    });

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers');
        })->missing(function () {
            return 'customer not found';
        });

    Route::get('create-site', function () {
        return 'create site';
    })->name('customers.site.create');

    Route::get('show-site/{site}', function () {
        return 'show site';
    })->name('customers.sites.show');
});
