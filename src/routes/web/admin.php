<?php

use App\Http\Controllers\Admin\AdministrationController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| System Administration Routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdministrationController::class)
        ->name('index')
        ->breadcrumb('Administration');
});
