<?php

use Illuminate\Support\Facades\Route;

Route::get('public-tips', function () {
    return 'public tech tips';
})->name('publicTips.index');
