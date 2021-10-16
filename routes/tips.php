<?php

use App\Http\Controllers\TechTips\GetTipDetailsController;
use App\Http\Controllers\TechTips\SearchTipsController;
use App\Http\Controllers\TechTips\TechTipBookmarkController;
use App\Http\Controllers\TechTips\TechTipsController;
use Illuminate\Support\Facades\Route;

/**
 * Tech Tips routes
 */
Route::middleware('auth')->group(function()
{


    Route::prefix('tech-tips')->name('tips.')->group(function()
    {
        Route::post('bookmark',     TechTipBookmarkController::class)->name('bookmark');
        Route::get('search',        SearchTipsController::class)->name('search');
        Route::get('{id}/get-details', GetTipDetailsController::class)->name('details');

        Route::get('{id}/download', function()
        {
            return 'download tip as pdf';
        })->name('download');
    });

    Route::resource('tech-tips', TechTipsController::class);
});
