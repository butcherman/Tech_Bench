<?php

use Illuminate\Support\Facades\Route;

Route::get('show-link', function () {
    return 'show link';
})->name('links.show');

Route::get('guest-link', function () {
    return 'guest link';
})->name('guest-link.show');
