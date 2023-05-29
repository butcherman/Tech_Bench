<?php

use App\Http\Controllers\Customers\CheckCustIdController;
use App\Http\Controllers\Customers\CustomerBookmarkController;
use App\Http\Controllers\Customers\CustomerContactController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerEquipmentController;
use App\Http\Controllers\Customers\CustomerFileController;
use App\Http\Controllers\Customers\CustomerFileTypeController;
use App\Http\Controllers\Customers\CustomerIdController;
use App\Http\Controllers\Customers\CustomerNoteController;
use App\Http\Controllers\Customers\CustomerSearchController;
use App\Http\Controllers\Customers\CustomerSettingsController;
use App\Http\Controllers\Customers\DeactivatedCustomerController;
use App\Http\Controllers\Customers\DownloadContactController;
use App\Http\Controllers\Customers\GetDeletedItemsController;
use App\Http\Controllers\Customers\LinkedCustomerController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::delete('force-delete', [CustomerController::class, 'forceDelete'])->name('force-delete');
        Route::post('restore', [CustomerController::class, 'restore'])->name('restore');
        Route::post('search', CustomerSearchController::class)->name('search');
        Route::post('bookmark', CustomerBookmarkController::class)->name('bookmark');
        Route::post('linked', [LinkedCustomerController::class, 'set'])->name('set-link');
        Route::get('{id}/check-id', CheckCustIdController::class)->name('check-id');
        Route::get('{customer}/get-linked', [LinkedCustomerController::class, 'get'])->name('linked');
        Route::get('{customer}/deleted-items', GetDeletedItemsController::class)->name('deleted-items');

        /**
         * Equipment
         */
        Route::resource('equipment', CustomerEquipmentController::class);
        Route::prefix('equipment')->name('equipment.')->group(function () {
            Route::get('{equipment}/restore', [CustomerEquipmentController::class, 'restore'])
                ->name('restore')
                ->withTrashed();
            Route::delete('{equipment}/force-delete', [CustomerEquipmentController::class, 'forceDelete'])
                ->name('force-delete')
                ->withTrashed();
        });

        /**
         * Contacts
         */
        Route::resource('contacts', CustomerContactController::class);
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('{contact}/restore', [CustomerContactController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('{contact}/force-delete', [CustomerContactController::class, 'forceDelete'])->name('force-delete')->withTrashed();
            Route::get('download/{contact}', DownloadContactController::class)->name('download');
        });

        /**
         * Notes
         */
        Route::resource('notes', CustomerNoteController::class);
        Route::get('{customer}/notes/{note}', [CustomerNoteController::class, 'show'])->name('notes.show')->breadcrumb('Note Details', 'customers.show');
        Route::prefix('notes')->name('notes.')->group(function () {
            Route::get('{note}/restore', [CustomerNoteController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('{note}/force-delete', [CustomerNoteController::class, 'forceDelete'])->name('force-delete')->withTrashed();
        });

        /**
         * Files
         */
        Route::resource('files', CustomerFileController::class);
        Route::prefix('files')->name('files.')->group(function () {
            Route::get('{file}/restore', [CustomerFileController::class, 'restore'])->name('restore')->withTrashed();
            Route::delete('{file}/force-delete', [CustomerFileController::class, 'forceDelete'])->name('force-delete')->withTrashed();
        });

        Route::inertia('404', 'Customers/NotFound')->name('not-found')->breadcrumb('Err: Customer Not Found (404)', 'customers.index');
    });

    Route::resource('customers', CustomerController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Customers')
                ->create('New Customer')
                ->show('Customer Details');
        })->missing(function () {
            return redirect()->route('customers.not-found');
        });

    /**
     * Customer Administration Routes
     */
    Route::prefix('administration/customers')->name('admin.cust.')->group(function () {
        Route::get('settings', [CustomerSettingsController::class, 'get'])
            ->name('settings')
            ->breadcrumb('Customer Settings', 'admin.index');
        Route::post('settings', [CustomerSettingsController::class, 'set'])
            ->name('set-settings');
        Route::get('deactivated-customers', DeactivatedCustomerController::class)
            ->name('deactivated')
            ->breadcrumb('Deactivated Customers', 'admin.cust.settings');
        Route::resource('change_id', CustomerIdController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Find Customer', 'admin.cust.settings')
                    ->show('Change ID');
            });
        Route::resource('file-types', CustomerFileTypeController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Customer File Types', 'admin.cust.settings');
            });
    });
});
