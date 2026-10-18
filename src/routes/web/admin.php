<?php

use App\Http\Controllers\Admin\AdminIndexController;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| System Administration Routes
| /administration
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->prefix('administration')->name('admin.')->group(function () {
    Route::get('/', AdminIndexController::class)
        ->name('index')
        ->breadcrumb('Administration');
});
