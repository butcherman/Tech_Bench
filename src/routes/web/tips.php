<?php

use Illuminate\Support\Facades\Route;

Route::get('tech-tips', function () {
    return 'tech tips';
})->name('publicTips.index');
