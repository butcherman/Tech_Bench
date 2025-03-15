<?php

use Illuminate\Support\Facades\Route;

Route::get('admin-index', function () {
    return 'admin index';
})->name('admin.index');
