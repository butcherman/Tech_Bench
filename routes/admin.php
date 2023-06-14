<?php

use App\Http\Controllers\Admin\AdminIndexController;
use App\Http\Controllers\Admin\User\UserAdminController;
// use App\Http\Controllers\Admin\User\UserAdminController;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdminIndexController::class)->name('index')->breadcrumb('Administration');

    /**
     * User Administration
     */
    Route::resource('users', UserAdminController::class)->breadcrumbs(function(ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('User Administration', 'admin.index')->create('New User', 'admin.users.index')->edit('Edit User', 'admin.users.index');
    });
});