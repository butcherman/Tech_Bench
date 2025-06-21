<?php

use App\Http\Controllers\Init\SaveSetupController;
use App\Http\Controllers\Init\SaveStepController;
use App\Http\Controllers\Init\StepFiveController;
use App\Http\Controllers\Init\StepFourController;
use App\Http\Controllers\Init\StepOneController;
use App\Http\Controllers\Init\StepThreeController;
use App\Http\Controllers\Init\StepTwoController;
use App\Http\Middleware\CheckForInit;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'init'])
    ->withoutMiddleware(CheckForInit::class)
    ->prefix('first-time-setup')
    ->name('init.')
    ->group(function () {
        Route::inertia('/', 'Init/Welcome')->name('welcome');

        Route::get('basic-settings', StepOneController::class)->name('step-1');
        Route::get('email-settings', StepTwoController::class)->name('step-2');
        Route::get('user-settings', StepThreeController::class)->name('step-3');
        Route::get('administrator-account', StepFourController::class)
            ->name('step-4');
        Route::get('verify-information', StepFiveController::class)
            ->name('step-5');
        Route::inertia('finish', 'Init/Finish', ['step' => 6])
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

        Route::get('save-setup', SaveSetupController::class)
            ->name('save-setup');
    });
