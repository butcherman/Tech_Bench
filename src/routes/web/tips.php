<?php

use Illuminate\Support\Facades\Route;

Route::get('tech-tips', function () {
    return 'tech tips';
})->name('publicTips.index');

Route::get('public-tips', function () {
    return ' show public tip';
})->name('publicTips.show');

Route::get('tech-tips/show', function () {
    return 'show tech tip';
})->name('tech-tips.show');
