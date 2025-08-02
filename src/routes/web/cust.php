<?php

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Customer\CustomerAdministrationController;
use App\Http\Controllers\Customer\CustomerAlertController;
use App\Http\Controllers\Customer\CustomerBookmarkController;
use App\Http\Controllers\Customer\CustomerContactController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerDeletedItemsController;
use App\Http\Controllers\Customer\CustomerEquipmentController;
use App\Http\Controllers\Customer\CustomerEquipmentDataController;
use App\Http\Controllers\Customer\CustomerEquipmentNoteController;
use App\Http\Controllers\Customer\CustomerEquipmentWorkbookController;
use App\Http\Controllers\Customer\CustomerFileController;
use App\Http\Controllers\Customer\CustomerNoteController;
use App\Http\Controllers\Customer\CustomerSearchController;
use App\Http\Controllers\Customer\CustomerSiteController;
use App\Http\Controllers\Customer\CustomerVpnController;
use App\Http\Controllers\Customer\DisabledCustomerController;
use App\Http\Controllers\Customer\DownloadNoteController;
use App\Http\Controllers\Customer\ReAssignCustomerController;
use App\Http\Controllers\Customer\ShareVpnDataController;
use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\CustomerSite;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Customer Based Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {
    /*
    |-----------------------------------------------------------------------
    | Customer Administration
    | /administration/customers
    |-----------------------------------------------------------------------
    */
    Route::prefix('administration')->name('customers.')->group(function () {
        Route::controller(CustomerAdministrationController::class)
            ->name('settings.')
            ->group(function () {
                Route::get('settings', 'edit')
                    ->name('edit')
                    ->breadcrumb('Customer Settings', 'admin.index');
                Route::put('settings', 'update')->name('update');
            });

        Route::controller(ReAssignCustomerController::class)
            ->name('re-assign.')
            ->group(function () {
                Route::get('re-assign-site', 'edit')
                    ->name('edit')
                    ->breadcrumb(
                        'Re-Assign Customer Site',
                        'customers.settings.edit'
                    );
                Route::put('re-assign-site', 'update')->name('update');
            });

        /*
        |-----------------------------------------------------------------------
        | Soft Deleted Customers
        | /administration/customers/disabled-customers
        |-----------------------------------------------------------------------
        */
        Route::prefix('disabled-customers')
            ->name('disabled.')
            ->group(function () {
                Route::get('/', DisabledCustomerController::class)
                    ->name('index')
                    ->breadcrumb('Disabled Customers', 'admin.index');
                Route::controller(CustomerController::class)
                    ->group(function () {
                        Route::get('{customer}/restore', 'restore')
                            ->withTrashed()
                            ->name('restore');
                        Route::delete('{customer}/force-delete', 'forceDelete')
                            ->withTrashed()
                            ->name('force-delete');
                    });
            });
    });

    Route::prefix('customers')->name('customers.')->group(function () {
        /*
        |-----------------------------------------------------------------------
        | Customer Searching and Verification
        | /customers
        |-----------------------------------------------------------------------
        */
        Route::inertia('not-found', 'Customer/NotFound')->name('not-found');
        Route::post('search', CustomerSearchController::class)->name('search');
        Route::post('bookmark/{customer}', CustomerBookmarkController::class)
            ->name('bookmark');

        /*
        |-----------------------------------------------------------------------
        | Customer Sites without parents
        | /customers
        |-----------------------------------------------------------------------
        */
        Route::controller(CustomerSiteController::class)->group(function () {
            Route::get('create-site', 'create')
                ->name('create-site')
                ->breadcrumb('New Customer Site', 'customers.index');
            Route::post('create-site', 'store')->name('store-site');
        });
    });

    /*
    |---------------------------------------------------------------------------
    | Customer Resource Routes
    | /customers/{customer-slug|customer-id}
    |---------------------------------------------------------------------------
    */
    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->show(
                    fn(Customer|string $customer) => gettype($customer) === 'object'
                    ? $customer->name
                    : $customer
                )
                ->create('Create New Customer')
                ->edit('Edit Customer Details');
        })->missing(function () {
            throw new CustomerNotFoundException;
        });

    /*
    |---------------------------------------------------------------------------
    | Routes to deal with specific customers
    | /customers/{customer-slug|customer-id}
    |---------------------------------------------------------------------------
    */
    Route::prefix('customers/{customer}')->name('customers.')->group(function () {
        /*
        |-----------------------------------------------------------------------
        | Customer Alerts
        | /customers/{customer-slug|customer-id}/alerts
        |-----------------------------------------------------------------------
        */
        Route::apiResource('alerts', CustomerAlertController::class)
            ->scoped(['alerts' => 'cust_id'])
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Alerts', 'customers.show');
            })->except(['show']);

        /*
        |---------------------------------------------------------------------------
        | Customer VPN Data
        | /customers/{customer-slug|customer-id}/vpn-data
        |---------------------------------------------------------------------------
        */
        Route::put('vpn-data/{vpn_datum}/share', ShareVpnDataController::class)
            ->name('vpn-data.share');
        Route::apiResource('vpn-data', CustomerVpnController::class)
            ->except(['show', 'index']);

        /*
        |-----------------------------------------------------------------------
        | Customer Deleted Items
        | /customers/{customer-slug|customer-id}/deleted-items
        |-----------------------------------------------------------------------
        */
        Route::prefix('deleted-items')->name('deleted-items.')->group(function () {
            Route::get('/', CustomerDeletedItemsController::class)
                ->name('index')
                ->breadcrumb('Deleted Items', 'customers.show');

            /*
            |-------------------------------------------------------------------
            | Restore a deleted Customer Item
            | /customers/{customer-slug|customer-id}/deleted-items/restore
            |-------------------------------------------------------------------
            */
            Route::prefix('restore')->name('restore.')->group(function () {
                Route::get(
                    'equipment/{equipment}',
                    [CustomerEquipmentController::class, 'restore']
                )->withTrashed()->scopeBindings()->name('equipment');
                Route::get(
                    'contacts/{contact}',
                    [CustomerContactController::class, 'restore']
                )->withTrashed()->scopeBindings()->name('contacts');
                Route::get(
                    'notes/{note}',
                    [CustomerNoteController::class, 'restore']
                )->withTrashed()->scopeBindings()->name('notes');
                Route::get(
                    'files/{file}',
                    [CustomerFileController::class, 'restore']
                )->withTrashed()->scopeBindings()->name('files');
            });

            /*
            |-------------------------------------------------------------------
            | Force Delete a Customer Item
            | /customers/{customer-slug|customer-id}/deleted-items/force-delete
            |-------------------------------------------------------------------
            */
            Route::prefix('force-delete')->name('force-delete.')
                ->group(function () {
                    Route::delete(
                        'equipment/{equipment}',
                        [CustomerEquipmentController::class, 'forceDelete']
                    )->withTrashed()->scopeBindings()->name('equipment');
                    Route::delete(
                        'contacts/{contact}',
                        [CustomerContactController::class, 'forceDelete']
                    )->withTrashed()->scopeBindings()->name('contacts');
                    Route::delete(
                        'notes/{note}',
                        [CustomerNoteController::class, 'forceDelete']
                    )->withTrashed()->scopeBindings()->name('notes');
                    Route::delete(
                        'files/{file}',
                        [CustomerFileController::class, 'forceDelete']
                    )->withTrashed()->scopeBindings()->name('files');
                });
        });

        /*
        |-----------------------------------------------------------------------
        | Customer Sites
        | /customers/{customer-slug|customer-id}/sites
        |-----------------------------------------------------------------------
        */
        Route::prefix('sites/{site:cust_site_id}')->controller(CustomerSiteController::class)->group(function () {
            Route::get('restore', 'restore')
                ->scopeBindings()
                ->withTrashed()
                ->name('sites.restore');
            Route::delete('force-delete', 'forceDelete')
                ->scopeBindings()
                ->withTrashed()
                ->name('sites.forceDelete');
        })->missing(function () {
            throw new CustomerNotFoundException;
        });

        Route::resource('sites', CustomerSiteController::class)
            ->scoped(['sites' => 'cust_id'])
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Sites', 'customers.show')
                    ->create('New Customer Site')
                    ->show(
                        fn(Customer $customer, CustomerSite|string $site) => gettype($site) === 'object'
                        ? $site->site_name
                        : $site
                    )->edit('Edit Site');
            })->missing(function () {
                throw new CustomerNotFoundException;
            });

        /*
        |-----------------------------------------------------------------------
        | Customer Equipment Routes
        | /customers/{customer-slug|customer-id}/equipment
        |-----------------------------------------------------------------------
        */
        Route::prefix('equipment/{equipment}')->name('equipment.')->group(function () {
            Route::controller(CustomerEquipmentWorkbookController::class)->prefix('workbook')->name('workbook.')->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
            });

            Route::resource('notes', CustomerEquipmentNoteController::class)
                ->scoped(['equipment' => 'cust_equip_id'])
                ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                    $breadcrumbs->index('Notes', 'customers.equipment.show')
                        ->create('Create Note')
                        ->show('Note Details')
                        ->edit('Edit Note');
                });
        });

        Route::apiResource('equipment', CustomerEquipmentController::class)
            ->scoped(['equipment' => 'cust_equip_id'])
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Equipment', 'customers.show')
                    ->show(
                        fn(Customer $customer, CustomerEquipment $equipment) => $equipment->equip_name
                    );
            });

        Route::put('{equipment}/equipment-data', CustomerEquipmentDataController::class)
            ->scopeBindings()
            ->name('update-equipment-data');

        /*
        |-----------------------------------------------------------------------
        | Customer Contacts
        | /customers/{customer-slug|customer-id}/contacts
        |-----------------------------------------------------------------------
        */
        Route::apiResource('contacts', CustomerContactController::class)
            ->scoped(['contacts' => 'cust_id'])
            ->except(['index', 'show']);

        /*
        |-----------------------------------------------------------------------
        | Customer Notes
        | /customers/{customer-slug|customer-id}/notes
        |-----------------------------------------------------------------------
        */
        Route::get('notes/{note}/download', DownloadNoteController::class)
            ->scopeBindings()
            ->name('notes.download');

        Route::resource('notes', CustomerNoteController::class)
            ->scoped(['notes' => 'cust_id'])
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Customer Notes', 'customers.show')
                    ->create('New Note')
                    ->show('Note Details')
                    ->edit('Edit Note');
            });

        /*
        |-----------------------------------------------------------------------
        | Customer Files
        | /customers/{customer-slug|customer-id}/files
        |-----------------------------------------------------------------------
        */
        Route::apiResource('files', CustomerFileController::class)
            ->scoped(['files' => 'cust_id'])
            ->except(['show']);
    });
});

/*
|-------------------------------------------------------------------------------
| Public Customer Workbook Routes
|-------------------------------------------------------------------------------
*/

Route::prefix('workbooks')->controller(CustomerEquipmentWorkbookController::class)->name('customer-workbook.')->group(function () {
    Route::get('{workbook}', 'show')->name('show');
    Route::put('{workbook}', 'update')->name('update');
});
