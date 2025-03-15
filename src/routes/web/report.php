<?php

use Illuminate\Support\Facades\Route;

Route::get('reports', function () {
    return 'reports index';
})->name('reports.index');
