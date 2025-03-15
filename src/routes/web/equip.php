<?php

use Illuminate\Support\Facades\Route;

Route::get('admin-equip', function () {
    return 'something admin';
})->name('equipment.index');

Route::get('admin-equipss', function () {
    return 'something admin';
})->name('equipment-data.index');
