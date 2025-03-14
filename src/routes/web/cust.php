<?php

use Illuminate\Support\Facades\Route;

Route::get('customers', function () {
    return 'show customer';
})->name('customers.sites.show');

Route::get('customer-index', function () {
    return 'customer index';
})->name('customers.index');
