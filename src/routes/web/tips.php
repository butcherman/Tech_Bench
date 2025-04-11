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

Route::get('tech-tips-index', function () {
    return 'tech tips index';
})->name('tech-tips.index');

Route::get('admin-tips', function () {
    return 'something admin';
})->name('admin.tech-tips.settings.edit');

Route::get('admin-tipsd', function () {
    return 'something admin';
})->name('admin.tech-tips.tip-types.index');

Route::get('admin-tipsss', function () {
    return 'something admin';
})->name('admin.tech-tips.deleted-tips');

Route::get('admin-sdftips', function () {
    return 'something admin';
})->name('tech-tips.comments.index');
