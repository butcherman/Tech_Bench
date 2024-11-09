<?php

use App\Http\Controllers\Customer\CustomerAdministrationController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerSearchController;
use App\Models\Customer;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Customer Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    Route::prefix('customers')->name('customers.')->group(function () {

        /*
        |-----------------------------------------------------------------------
        | Customer Searching and Verification
        |-----------------------------------------------------------------------
        */

        Route::post('search', CustomerSearchController::class)->name('search');

        /*
        |-----------------------------------------------------------------------
        | Customer Administration
        |-----------------------------------------------------------------------
        */

        Route::get('settings', [CustomerAdministrationController::class, 'edit'])
            ->name('settings.edit')
            ->breadcrumb('Customer Settings', 'admin.index');
        Route::put('settings', [CustomerAdministrationController::class, 'update'])
            ->name('settings.update');
    });

    /*
    |---------------------------------------------------------------------------
    | Customer Routes
    |---------------------------------------------------------------------------
    */

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->show(
                    fn (Customer|string $customer) => gettype($customer) === 'object'
                        ? $customer->name
                        : $customer
                )->edit('Edit Customer Details');
        })->missing(function (Request $request) {
            // throw new CustomerNotFoundException($request);
            dd('not found');
        });
});

/*******************************************************************************
 * TMP GO AWAY
 *******************************************************************************/
Route::get('create-site', function () {
    return 'create site';
})->name('customers.create-site');

Route::get('show-site', function () {
    return 'show site';
})->name('customers.sites.show');
