<?php

use App\Http\Controllers\Admin\Config\BasicSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('basic-settings', [BasicSettingsController::class, 'show'])
    ->name('basic-settings.show');
Route::put('basic-settings', [BasicSettingsController::class, 'update'])
    ->name('basic-settings.update');
