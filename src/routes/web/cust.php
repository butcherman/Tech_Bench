<?php

use App\Http\Controllers\Customer\CustomerController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*******************************************************************************
 *                          Customer Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {
    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers');
        })->missing(function () {
            return 'customer not found';
        });
});
