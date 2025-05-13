<?php

use App\Http\Controllers\TechTip\SearchTipsController;
use App\Http\Controllers\TechTip\TechTipController;
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
    });

    /*
    |---------------------------------------------------------------------------
    | Tech Tip Resource Routes
    | /tech-tips
    |---------------------------------------------------------------------------
    */
    Route::resource('tech-tips', TechTipController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips');
        });
});

Route::get('public-tips', function () {
    return 'public tips';
})->name('publicTips.index');

Route::get('public-tips', function () {
    return ' show public tip';
})->name('publicTips.show');

Route::get('admin-tips', function () {
    return 'something admin';
})->name('admin.tech-tips.settings.edit');

Route::get('admin-tipsd', function () {
    return 'something admin';
})->name('admin.tech-tips.tip-types.index');

Route::get('admin-tipsss', function () {
    return 'something admin';
})->name('admin.tech-tips.deleted-tips');

Route::get('admin-sdftips', function () {
    return 'something admin';
})->name('tech-tips.comments.index');
