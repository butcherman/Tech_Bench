<?php

use App\Http\Controllers\Admin\Config\BasicSettingsController;
use App\Http\Controllers\Admin\Config\EmailSettingsController;
use App\Http\Controllers\Admin\Config\SendTestEmailController;
use App\Http\Controllers\Admin\User\UserAdministrationController;
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

        Route::get('test-email', SendTestEmailController::class)->name('test-email');

        Route::put('secure-administrator-account/{user}', [
            UserAdministrationController::class, 'update',
        ])->name('step-1.submit');
        Route::put('basic-settings', [BasicSettingsController::class, 'update'])
            ->name('step-2.submit');
        Route::put('email-settings', [EmailSettingsController::class, 'update'])
            ->name('step-3.submit');
        Route::put('user-settings', StepFour::class)->name('step-4.submit');
        Route::put('finish', Finish::class)->name('finish.submit');
    });
