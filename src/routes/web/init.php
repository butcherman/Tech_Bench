<?php

use App\Http\Controllers\Init\Finish;
use App\Http\Controllers\Init\SaveSetupController;
use App\Http\Controllers\Init\SaveStepController;
use App\Http\Controllers\Init\StepFive;
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

        Route::get('basic-settings', StepOne::class)->name('step-1');
        Route::get('email-settings', StepTwo::class)->name('step-2');
        Route::get('user-settings', StepThree::class)->name('step-3');
        Route::get('administrator-account', StepFour::class)->name('step-4');
        Route::get('verify-information', StepFive::class)->name('step-5');
        Route::get('finish', Finish::class)
            ->name('finish')
            ->withoutMiddleware('init');

        Route::put('basic-settings', SaveStepController::class)
            ->name('step-1.submit');
        Route::put('email-settings', SaveStepController::class)
            ->name('step-2.submit');
        Route::put('user-settings', SaveStepController::class)
            ->name('step-3.submit');
        Route::put('administrator-account/{user}', SaveStepController::class)
            ->name('step-4.submit');
        Route::put('administrator-password', SaveStepController::class)
            ->name('step-4b.submit');
        Route::put('verify-information', SaveStepController::class)
            ->name('step-5.submit');

        Route::get('save-setup', SaveSetupController::class)->name('save-setup');
    });
