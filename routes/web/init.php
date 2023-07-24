<?php

use App\Http\Controllers\Init\StepFive;
use App\Http\Controllers\Init\StepFour;
use App\Http\Controllers\Init\StepOne;
use App\Http\Controllers\Init\StepThree;
use App\Http\Controllers\Init\StepTwo;
use Illuminate\Support\Facades\Route;

/**
 *   Tech Bench Initialization Routes.  Used for first time setup
 */
Route::middleware('auth')->name('init.')->group(function () {
    Route::inertia('first-time-setup', 'Init/Welcome')
        ->name('welcome')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/secure-admin-account', StepOne::class)
        ->name('step-1')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/basic-settings', StepTwo::class)
        ->name('step-2')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/email-setup', StepThree::class)
        ->name('step-3')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('first-time-setup/user-setup', StepFour::class)
        ->name('step-4')
        ->breadcrumb('Welcome to the Tech Bench');

    Route::get('finish-setup', StepFive::class)
        ->name('step-5')
        ->breadcrumb('Setup Completed');
});
