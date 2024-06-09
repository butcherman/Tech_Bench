<?php

use App\Http\Controllers\TechTips\TechTipsController;
use Illuminate\Support\Facades\Route;


/*******************************************************************************
 *                          Tech Tip Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {
    Route::resource('tech-tips', TechTipsController::class);
});