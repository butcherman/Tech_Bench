<?php

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Customer\CustomerAdministrationController;
use App\Http\Controllers\Customer\CustomerAlertController;
use App\Http\Controllers\Customer\CustomerBookmarkController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerSearchController;
use App\Http\Controllers\Customer\CustomerSiteController;
use App\Http\Controllers\Customer\DisabledCustomerController;
use App\Http\Controllers\Customer\ReAssignCustomerController;
use App\Models\Customer;
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
        // Route::inertia('not-found', 'Customer/NotFound')->name('not-found');
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
        Route::resource('alerts', CustomerAlertController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Alerts', 'customers.show');
            })->only(['index', 'store', 'update', 'destroy']);

        /*
        |-----------------------------------------------------------------------
        | Customer Deleted Items
        | /customers/{customer-slug|customer-id}/deleted-items
        |-----------------------------------------------------------------------
        */
        // Route::prefix('deleted-items')->name('deleted-items.')->group(function () {
        //     Route::get('/', CustomerDeletedItemsController::class)
        //         ->name('index')
        //         ->breadcrumb('Deleted Items', 'customers.show');

        /*
            |-------------------------------------------------------------------
            | Restore a deleted Customer Item
            | /customers/{customer-slug|customer-id}/deleted-items/restore
            |-------------------------------------------------------------------
            */
        // Route::prefix('restore')->name('restore.')->group(function () {
        //     Route::get(
        //         'equipment/{equipment}',
        //         [CustomerEquipmentController::class, 'restore']
        //     )->withTrashed()->name('equipment');
        //     Route::get(
        //         'contacts/{contact}',
        //         [CustomerContactController::class, 'restore']
        //     )->withTrashed()->name('contacts');
        //     Route::get(
        //         'notes/{note}',
        //         [CustomerNoteController::class, 'restore']
        //     )->withTrashed()->name('notes');
        //     Route::get(
        //         'files/{file}',
        //         [CustomerFileController::class, 'restore']
        //     )->withTrashed()->name('files');
        // });

        /*
            |-------------------------------------------------------------------
            | Force Delete a Customer Item
            | /customers/{customer-slug|customer-id}/deleted-items/force-delete
            |-------------------------------------------------------------------
            */
        // Route::prefix('force-delete')->name('force-delete.')
        //     ->group(function () {
        //         Route::delete(
        //             'equipment/{equipment}',
        //             [CustomerEquipmentController::class, 'forceDelete']
        //         )->withTrashed()->name('equipment');
        //         Route::delete(
        //             'contacts/{contact}',
        //             [CustomerContactController::class, 'forceDelete']
        //         )->withTrashed()->name('contacts');
        //         Route::delete(
        //             'notes/{note}',
        //             [CustomerNoteController::class, 'forceDelete']
        //         )->withTrashed()->name('notes');
        //         Route::delete(
        //             'files/{file}',
        //             [CustomerFileController::class, 'forceDelete']
        //         )->withTrashed()->name('files');
        //     });
        // });

        /*
        |-----------------------------------------------------------------------
        | Customer Sites
        | /customers/{customer-slug|customer-id}/sites
        |-----------------------------------------------------------------------
        */
        Route::resource('sites', CustomerSiteController::class)
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
        // Route::get(
        //     'equipment/{equipment}/note/create',
        //     [CustomerNoteController::class, 'createEquipmentNote']
        // )->name('equipment.note.create');

        // Route::resource('equipment', CustomerEquipmentController::class)
        //     ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        //         $breadcrumbs->index('Equipment', 'customers.show')
        //             ->show(
        //                 fn (Customer $customer, CustomerEquipment $equipment) => $equipment->equip_name
        //             );
        //     })->except(['create', 'edit']);

        // Route::put('equipment-data', CustomerEquipmentDataController::class)
        //     ->name('update-equipment-data');

        /*
        |-----------------------------------------------------------------------
        | Customer Contacts
        | /customers/{customer-slug|customer-id}/contacts
        |-----------------------------------------------------------------------
        */
        // Route::resource('contacts', CustomerContactController::class)
        //     ->only(['store', 'update', 'destroy']);

        /*
        |-----------------------------------------------------------------------
        | Customer Notes
        | /customers/{customer-slug|customer-id}/notes
        |-----------------------------------------------------------------------
        */
        // Route::get('notes/{note}/download', DownloadNoteController::class)
        //     ->name('notes.download');

        // Route::resource('notes', CustomerNoteController::class)
        //     ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        //         $breadcrumbs->index('Customer Notes', 'customers.show')
        //             ->create('New Note')
        //             ->show('Note Details')
        //             ->edit('Edit Note');
        //     });

        /*
        |-----------------------------------------------------------------------
        | Customer Site Notes
        | /customers/{customer-slug|customer-id}/site/{site-slug|site-id}/notes
        |-----------------------------------------------------------------------
        */
        // Route::prefix('site/{site}')->name('site.')->group(function () {
        //     Route::resource('notes', CustomerNoteSiteController::class)
        //         ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        //             $breadcrumbs->create('New Note', 'customers.sites.show')
        //                 ->show('Note Details', 'customers.sites.show')
        //                 ->edit('Edit Note');
        //         })->except(['index']);
        // });

        /*
        |-----------------------------------------------------------------------
        | Customer Equipment Notes
        | /customers/{customer-slug|customer-id}/equipment/{equipment-id}/notes
        |-----------------------------------------------------------------------
        */
        // Route::prefix('equipment/{equipment}')
        //     ->name('equipment.')
        //     ->group(function () {
        //         Route::resource('notes', CustomerNoteEquipmentController::class)
        //             ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        //                 $breadcrumbs->create('New Note', 'customers.equipment.show')
        //                     ->show('Note Details', 'customers.equipment.show')
        //                     ->edit('Edit Note');
        //             })->except(['index']);
        //     });

        /*
        |-----------------------------------------------------------------------
        | Customer Files
        | /customers/{customer-slug|customer-id}/files
        |-----------------------------------------------------------------------
        */
        // Route::resource('files', CustomerFileController::class)
        //     ->except(['index', 'show', 'edit', 'create']);
    });
});
