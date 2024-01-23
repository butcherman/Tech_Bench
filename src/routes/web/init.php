<?php

use App\Http\Controllers\Init\Finish;
use App\Http\Controllers\Init\StepFour;
use App\Http\Controllers\Init\StepOne;
use App\Http\Controllers\Init\StepThree;
use App\Http\Controllers\Init\StepTwo;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'init'])
    ->prefix('first-time-setup')
    ->name('init.')
    ->group(function () {
        Route::inertia('/', 'Init/Welcome')->name('welcome');
        Route::get('secure-administrator-account', StepOne::class)->name('step-1');
        Route::get('basic-settings', StepTwo::class)->name('step-2');
        Route::get('email-settings', StepThree::class)->name('step-3');
        Route::get('user-settings', StepFour::class)->name('step-4');
        Route::get('finish', Finish::class)->name('finish');
    });
