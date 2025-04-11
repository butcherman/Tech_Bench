<?php

use Illuminate\Support\Facades\Route;

Route::get('customers', function () {
    return 'show customer';
})->name('customers.sites.show');

Route::get('customer-index', function () {
    return 'customer index';
})->name('customers.index');

Route::get('customer-settings', function () {
    return 'something admin';
})->name('customers.settings.edit');

Route::get('customer-disabled', function () {
    return 'something admin';
})->name('customers.disabled.index');

Route::get('customer-assign', function () {
    return 'something admin';
})->name('customers.re-assign.edit');
