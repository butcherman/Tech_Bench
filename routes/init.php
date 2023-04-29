<?php

use App\Http\Controllers\Init\StepThree;
use App\Http\Controllers\Init\StepTwo;
use Illuminate\Support\Facades\Route;

/**
 *   Tech Bench Initialization Routes for first time setup
 */
Route::middleware('auth')->name('init.')->group(function () {
    Route::inertia('first-time-setup', 'Init/StepOne')
        ->name('step-1')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/basic-settings', StepTwo::class)
        ->name('step-2')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/email-setup', StepThree::class)
        ->name('step-3')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::inertia('first-time-setup/user-setup', 'Init/StepFour')
        ->name('step-4')
        ->breadcrumb('Welcome to the Tech Bench');
});
