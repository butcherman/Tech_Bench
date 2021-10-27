<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\SearchTipsController;
use App\Http\Controllers\TechTips\TechTipTypesController;
use App\Http\Controllers\TechTips\GetTipDetailsController;
use App\Http\Controllers\TechTips\TechTipBookmarkController;
use App\Http\Controllers\TechTips\TechTipCommentsController;

/**
 * Tech Tips routes
 */
Route::middleware('auth')->group(function()
{
    Route::resource('tech-tips', TechTipsController::class);

    Route::prefix('tech-tips')->name('tips.')->group(function()
    {
        Route::post('bookmark',     TechTipBookmarkController::class)->name('bookmark');
        Route::get('search',        SearchTipsController::class)->name('search');
        Route::get('{id}/get-details', GetTipDetailsController::class)->name('details');

        //  TODO - Finish this
        Route::get('{id}/download', function()
        {
            return 'download tip as pdf';
        })->name('download');

        Route::resource('comments', TechTipCommentsController::class);
    });

    //  Tech Tip Administration Routes
    Route::prefix('administration/tips')->name('admin.tips.')->group(function()
    {
        Route::resource('tip-types', TechTipTypesController::class);
    });
});
