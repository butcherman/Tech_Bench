<?php

use Illuminate\Support\Facades\Route;

Route::get('show-link', function () {
    return 'show link';
})->name('links.show');

Route::get('link-index', function () {
    return 'link index';
})->name('links.index');

Route::get('guest-link', function () {
    return 'guest link';
})->name('guest-link.show');

Route::get('admin-links', function () {
    return 'something admin';
})->name('admin.links.settings.edit');

Route::get('admin-adfa', function () {
    return 'something admin';
})->name('admin.links.manage.index');
