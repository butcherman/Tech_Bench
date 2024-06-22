<?php

use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\SearchTipsController;

use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;


/*******************************************************************************
 *                          Tech Tip Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {
    Route::resource('tech-tips', TechTipsController::class)->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
        $breadcrumbs->index('Tech Tips');
    });

    Route::post('tech-tips/search', SearchTipsController::class)->name('tech-tips.search');
});