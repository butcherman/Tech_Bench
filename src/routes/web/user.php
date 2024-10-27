<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth.secure')
    ->prefix('user')
    ->name('user.')
    ->group(function () {
        //
    });
