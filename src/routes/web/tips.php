<?php

use App\Exceptions\TechTip\TechTipNotFoundException;
use App\Http\Controllers\TechTip\DownloadTipController;
use App\Http\Controllers\TechTip\FlagTipController;
use App\Http\Controllers\TechTip\SearchTipsController;
use App\Http\Controllers\TechTip\TechTipBookmarkController;
use App\Http\Controllers\TechTip\TechTipCommentController;
use App\Http\Controllers\TechTip\TechTipController;
use App\Http\Controllers\TechTip\UploadTipFileController;
use App\Models\TechTip;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Tech Tip Based routes
|-------------------------------------------------------------------------------
*/

Route::middleware('auth.secure')->group(function () {

    Route::prefix('tech-tips')->name('tech-tips.')->group(function () {

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Searching and Verification
        | /tech-tips
        |-----------------------------------------------------------------------
        */
        Route::post('search', SearchTipsController::class)->name('search');
        Route::post('bookmark/{tech_tip}', TechTipBookmarkController::class)
            ->name('bookmark');

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Service Routes
        | /tech-tips
        |-----------------------------------------------------------------------
        */
        Route::inertia('not-found', 'TechTip/NotFound')->name('not-found');
        Route::post('upload-file/{techTip?}', UploadTipFileController::class)
            ->name('upload-file');
        Route::get('download/{tech_tip}', DownloadTipController::class)
            ->name('download');

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Comments
        |-----------------------------------------------------------------------
        */
        Route::prefix('{tech_tip}')->group(function () {
            Route::get('flag/{comment}', FlagTipController::class)
                ->scopeBindings()
                ->name('comments.flag');
            Route::apiResource('comments', TechTipCommentController::class)
                ->scoped(['comments' => 'tip_id'])
                ->except(['index', 'show']);
        });
    });

    /*
    |---------------------------------------------------------------------------
    | Tech Tip Resource Routes
    | /tech-tips
    |---------------------------------------------------------------------------
    */
    Route::resource('tech-tips', TechTipController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips')
                ->create('New Tech Tip')
                ->show(fn(TechTip $tech_tip) => $tech_tip->subject)
                ->edit('Edit Tech Tip');
        })->missing(function () {
            throw new TechTipNotFoundException;
        });
});

Route::get('public-tips-index', function () {
    return 'public tips';
})->name('publicTips.index');

Route::get('public-tips-show', function () {
    return ' show public tip';
})->name('publicTips.show');

Route::get('admin-tips', function () {
    return 'something admin';
})->name('admin.tech-tips.settings.edit');

Route::get('admin-tips-tip-type-index', function () {
    return 'something admin';
})->name('admin.tech-tips.tip-types.index');

Route::get('admin-deleted-tips', function () {
    return 'something admin';
})->name('admin.tech-tips.deleted-tips');

Route::get('admin-flagged-comments', function () {
    return 'admin flagged comments';
})->name('admin.tech-tips.flagged-comments.index');
