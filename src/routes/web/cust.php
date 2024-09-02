<?php

use App\Exceptions\Customer\CustomerNotFoundException;
use App\Http\Controllers\Customer\CustomerAdminController;
use App\Http\Controllers\Customer\CustomerAlertsController;
use App\Http\Controllers\Customer\CustomerBookmarkController;
use App\Http\Controllers\Customer\CustomerContactController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\Customer\CustomerDeletedItemsController;
use App\Http\Controllers\Customer\CustomerEquipmentController;
use App\Http\Controllers\Customer\CustomerEquipmentDataController;
use App\Http\Controllers\Customer\CustomerFileController;
use App\Http\Controllers\Customer\CustomerIdController;
use App\Http\Controllers\Customer\CustomerNoteController;
use App\Http\Controllers\Customer\CustomerNoteEquipmentController;
use App\Http\Controllers\Customer\CustomerNoteSiteController;
use App\Http\Controllers\Customer\CustomerSearchController;
use App\Http\Controllers\Customer\CustomerSiteController;
use App\Http\Controllers\Customer\DisabledCustomerController;
use App\Http\Controllers\Customer\DownloadNoteController;
use App\Http\Controllers\Customer\ReAssignCustomerController;
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
        Route::post('bookmark/{customer}', CustomerBookmarkController::class)
            ->name('bookmark');

        /***********************************************************************
         * Customer Administration
         ***********************************************************************/
        Route::get('settings', [CustomerAdminController::class, 'edit'])
            ->name('settings.edit')
            ->breadcrumb('Customer Settings', 'admin.index');
        Route::put('settings', [CustomerAdminController::class, 'update'])
            ->name('settings.update');
        Route::prefix('disabled-customers')->name('disabled.')->group(function () {
            Route::get('/', DisabledCustomerController::class)
                ->name('index')
                ->breadcrumb('Disabled Customers', 'admin.index');
            Route::get('{customer}/restore', [CustomerController::class, 'restore'])
                ->withTrashed()
                ->name('restore');
            Route::delete('{customer}/force-delete', [CustomerController::class, 'forceDelete'])
                ->withTrashed()
                ->name('force-delete');
        });
        Route::get('re-assign-site', [ReAssignCustomerController::class, 'edit'])
            ->name('re-assign.edit')
            ->breadcrumb('Re-Assign Customer Site', 'customers.settings.edit');
        Route::put('re-assign-site', [ReAssignCustomerController::class, 'update'])
            ->name('re-assign.update');
    });

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->show(
                    fn(Customer|string $customer) =>
                    gettype($customer) === 'object' ? $customer->name : $customer
                )->edit('Edit Customer Details');
        })->missing(function (Request $request) {
            throw new CustomerNotFoundException($request);
        });

    /***************************************************************************
     *                          Customer Specific Routes                       *
     ***************************************************************************/
    Route::prefix('customers/{customer}')->name('customers.')->group(function () {

        Route::prefix('deleted-items')->name('deleted-items.')->group(function () {
            Route::get('/', CustomerDeletedItemsController::class)
                ->name('index')
                ->breadcrumb('Deleted Items', 'customers.show');
            Route::prefix('restore')->name('restore.')->group(function () {
                Route::get('equipment/{equipment}', [CustomerEquipmentController::class, 'restore'])
                    ->withTrashed()
                    ->name('equipment');
                Route::get('contacts/{contact}', [CustomerContactController::class, 'restore'])
                    ->withTrashed()
                    ->name('contacts');
                Route::get('notes/{note}', [CustomerNoteController::class, 'restore'])
                    ->withTrashed()
                    ->name('notes');
                Route::get('files/{file}', [CustomerFileController::class, 'restore'])
                    ->withTrashed()
                    ->name('files');
            });
            Route::prefix('force-delete')->name('force-delete.')->group(function () {
                Route::delete('equipment/{equipment}', [CustomerEquipmentController::class, 'forceDelete'])
                    ->withTrashed()
                    ->name('equipment');
                Route::delete('contacts/{contact}', [CustomerContactController::class, 'forceDelete'])
                    ->withTrashed()
                    ->name('contacts');
                Route::delete('notes/{note}', [CustomerNoteController::class, 'forceDelete'])
                    ->withTrashed()
                    ->name('notes');
                Route::delete('files/{file}', [CustomerFileController::class, 'forceDelete'])
                    ->withTrashed()
                    ->name('files');
            });
        });

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
        Route::get('equipment/{equipment}/note/create', [CustomerNoteController::class, 'createEquipmentNote'])
            ->name('equipment.note.create');

        Route::resource('equipment', CustomerEquipmentController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Equipment', 'customers.show')
                    ->show(
                        fn(Customer $customer, CustomerEquipment $equipment) => $equipment->equip_name
                    );
            })->except(['create', 'edit']);
        Route::put('equipment-data', CustomerEquipmentDataController::class)
            ->name('update-equipment-data');

        /***********************************************************************
         *                     Customer Contacts Routes                        *
         ***********************************************************************/
        Route::resource('contacts', CustomerContactController::class)
            ->except(['index', 'edit', 'show', 'create']);

        /***********************************************************************
         *                     Customer Notes Routes                           *
         ***********************************************************************/
        Route::get('{note}/download', DownloadNoteController::class)
            ->name('notes.download');
        Route::resource('notes', CustomerNoteController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Customer Notes', 'customers.show')
                    ->create('New Note')
                    ->show('Note Details')
                    ->edit('Edit Note');
            });
        Route::prefix('site/{site}')->name('site.')->group(function () {
            Route::resource('notes', CustomerNoteSiteController::class)
                ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                    $breadcrumbs->create('New Note', 'customers.sites.show')
                        ->show('Note Details', 'customers.sites.show')
                        ->edit('Edit Note');
                })->except(['index']);
        });
        Route::prefix('equipment/{equipment}')->name('equipment.')->group(function () {
            Route::resource('notes', CustomerNoteEquipmentController::class)
                ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                    $breadcrumbs->create('New Note', 'customers.equipment.show')
                        ->show('Note Details', 'customers.equipment.show')
                        ->edit('Edit Note');
                })->except(['index']);
        });

        /***********************************************************************
         *                     Customer Files Routes                           *
         ***********************************************************************/
        Route::resource('files', CustomerFileController::class)
            ->except(['index', 'show', 'edit', 'create']);
    });
});
