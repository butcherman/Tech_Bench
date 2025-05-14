<?php

use App\Http\Controllers\TechTip\SearchTipsController;
use App\Http\Controllers\TechTip\TechTipController;
use App\Http\Controllers\TechTip\UploadTipFileController;
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

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Service Routes
        | /tech-tips
        |-----------------------------------------------------------------------
        */
        Route::post('upload-file/{techTip?}', UploadTipFileController::class)
            ->name('upload-file');
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
                ->show('Tip Details')
                ->edit('Edit Tech Tip');
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

Route::get('admin-comments-show', function () {
    return 'something admin';
})->name('tech-tips.comments.index');
