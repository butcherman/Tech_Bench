<?php

use App\Http\Controllers\TechTips\DeletedTechTipsController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\SearchTipsController;
use App\Http\Controllers\TechTips\DownloadTipController;
use App\Http\Controllers\TechTips\TechTipTypesController;
use App\Http\Controllers\TechTips\GetTipDetailsController;
use App\Http\Controllers\TechTips\ShowDeletedTipController;
use App\Http\Controllers\TechTips\TechTipBookmarkController;
use App\Http\Controllers\TechTips\TechTipCommentsController;

/**
 * Tech Tips routes
 */
Route::middleware('auth')->group(function()
{
    Route::prefix('tech-tips')->name('tips.')->group(function()
    {
        Route::post('bookmark',             TechTipBookmarkController::class)->name('bookmark');
        Route::get( 'search',               SearchTipsController::class)->name('search');
        Route::get( '{id}/get-details',     GetTipDetailsController::class)->name('details');
        Route::get( '{id}/download',        DownloadTipController::class)->name('download');
        Route::get( '{id}/show-deleted',    ShowDeletedTipController::class)->name('show-deleted');

        Route::resource('comments',         TechTipCommentsController::class);
    });

    Route::resource('tech-tips',            TechTipsController::class);

    //  Tech Tip Administration Routes
    Route::prefix('administration/tips')->name('admin.tips.')->group(function()
    {
        Route::get('deleted-tips',          DeletedTechTipsController::class)->name('deleted');
        Route::get('{id}/restore',         [TechTipsController::class, 'restore'])->name('restore');
        Route::delete('{id}/force-delete', [TechTipsController::class, 'forceDelete'])->name('force-delete');

        Route::resource('tip-types',        TechTipTypesController::class);
    });
});
