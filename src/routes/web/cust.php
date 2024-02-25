<?php

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Customer\CustomerAlertsController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerDeletedItemsController;
use App\Http\Controllers\Customer\CustomerEquipmentController;
use App\Http\Controllers\Customer\CustomerIdController;
use App\Http\Controllers\Customer\CustomerSearchController;
use App\Http\Controllers\Customer\CustomerSiteController;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*******************************************************************************
 *                          Customer Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::inertia('customer-not-found', 'Customer/NotFound')
            ->name('notFound');
        Route::post('search', CustomerSearchController::class)
            ->name('search');
        Route::get('check-id/{custId}', CustomerIdController::class)
            ->name('check-id');
        Route::get('create-site', [CustomerSiteController::class, 'create'])
            ->name('create-site')
            ->breadcrumb('New Customer Site', 'customers.index');
        Route::post('create-site', [CustomerSiteController::class, 'store'])
            ->name('store-site');
    });

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->show(
                    fn(Customer|string $customer) => gettype($customer) === 'object' ? $customer->name : $customer
                )->edit('Edit Customer Details');
        })->missing(function (Request $request) {
            throw new CustomerNotFoundException($request);
        });

    /***************************************************************************
     *                          Customer Specific Routes                       *
     ***************************************************************************/
    Route::prefix('{customer}')->name('customers.')->group(function () {
        Route::get('deleted-items', CustomerDeletedItemsController::class)
            ->name('deleted-items')
            ->breadcrumb('Deleted Items', 'customers.show');

        Route::resource('alerts', CustomerAlertsController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Alerts', 'customers.show');
            })->only(['index', 'store', 'update', 'destroy']);

        Route::resource('sites', CustomerSiteController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Sites', 'customers.show')
                    ->create('New Customer Site')
                    ->show(fn(Customer $customer, CustomerSite $site) => $site->site_name)
                    ->edit('Edit Site');
            });

        /***********************************************************************
         *                     Customer Equipment Routes                       *
         ***********************************************************************/
        Route::resource('equipment', CustomerEquipmentController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Equipment', 'customers.show')
                    ->show(fn(Customer $customer, CustomerEquipment $equipment) => $equipment->equip_name);
            });
    });
});
