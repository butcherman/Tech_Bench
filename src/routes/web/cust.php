<?php

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Customer\CustomerAdministrationController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerIdController;
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

        Route::inertia('not-found', 'Customer/NotFound')->name('not-found');
        Route::post('search', CustomerSearchController::class)->name('search');
        Route::get('check-id/{custId}', CustomerIdController::class)->name('check-id');

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

        // Route::get('re-assign-site', [ReAssignCustomerController::class, 'edit'])
        //     ->name('re-assign.edit')
        //     ->breadcrumb('Re-Assign Customer Site', 'customers.settings.edit');
        // Route::put('re-assign-site', [ReAssignCustomerController::class, 'update'])
        //     ->name('re-assign.update');

        Route::prefix('disabled-customers')->name('disabled.')->group(function () {
            // Route::get('/', DisabledCustomerController::class)
            //     ->name('index')
            //     ->breadcrumb('Disabled Customers', 'admin.index');
            Route::get('{customer}/restore', [CustomerController::class, 'restore'])
                ->withTrashed()
                ->name('restore');
            Route::delete('{customer}/force-delete', [CustomerController::class, 'forceDelete'])
                ->withTrashed()
                ->name('force-delete');
        });

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
        })->missing(function () {
            throw new CustomerNotFoundException;
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

Route::get('bookmark', function () {
    return 'bookmark';
})->name('customers.bookmark');

Route::get('alerts', function () {
    return 'alerts';
})->name('customers.alerts.index');

Route::get('deleted-items', function () {
    return 'deleted items';
})->name('customers.deleted-items.index');

Route::get('sites', function () {
    return 'sites edit';
})->name('customers.sites.edit');

Route::get('sites-new', function () {
    return 'sites new';
})->name('customers.sites.create');

Route::get('notes', function () {
    return 'create notes';
})->name('customers.site.notes.create');

Route::get('notesee', function () {
    return 'create notes';
})->name('customers.notes.create');
