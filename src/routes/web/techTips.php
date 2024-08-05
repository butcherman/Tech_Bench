<?php

use App\Http\Controllers\Public\PublicTechTipController;
use App\Http\Controllers\TechTips\DeletedTipsController;
use App\Http\Controllers\TechTips\DownloadTipController;
use App\Http\Controllers\TechTips\FlagCommentController;
use App\Http\Controllers\TechTips\SearchTipsController;
use App\Http\Controllers\TechTips\ShowDeletedTipController;
use App\Http\Controllers\TechTips\ShowFlaggedCommentsController;
use App\Http\Controllers\TechTips\TechTipBookmarkController;
use App\Http\Controllers\TechTips\TechTipCommentController;
use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\TechTipsSettingsController;
use App\Http\Controllers\TechTips\TechTipTypesController;
use App\Http\Controllers\TechTips\UploadTipFile;
use App\Models\TechTip;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*******************************************************************************
 *                          Tech Tip Based Routes                              *
 *******************************************************************************/
Route::middleware('auth.secure')->group(function () {
    /**
     * Tech Tip Administration Routes
     */
    Route::prefix('administration/tech-tips')->name('admin.tech-tips.')->group(function () {
        Route::get('tech-tips/settings', [TechTipsSettingsController::class, 'edit'])
            ->name('settings.edit')
            ->breadcrumb('Tech Tip Settings', 'admin.index');
        Route::put('tech-tips/settings', [TechTipsSettingsController::class, 'update'])
            ->name('settings.update');
        Route::resource('tip-types', TechTipTypesController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Tech Tip Types', 'admin.index');
            })->except(['create', 'edit', 'show']);
        Route::get('deleted-tips', DeletedTipsController::class)
            ->name('deleted-tips')
            ->breadcrumb('Deleted Tech Tips', 'admin.index');
        Route::get('deleted-tips/{techTip}', ShowDeletedTipController::class)
            ->withTrashed()
            ->name('deleted-tips.show')
            ->breadcrumb(fn(TechTip $techTip) => 'Tip Details - ' . $techTip->subject, 'admin.tech-tips.deleted-tips');
        Route::get('restore-tip/{techTip}', [TechTipsController::class, 'restore'])
            ->name('restore')
            ->withTrashed();
        Route::delete('force-delete/{techTip}', [TechTipsController::class, 'forceDelete'])
            ->name('force-delete')
            ->withTrashed();
    });

    /**
     * Tech Tip Service Routes
     */
    Route::prefix('tech-tips')->name('tech-tips.')->group(function () {
        Route::post('search', SearchTipsController::class)->name('search');
        Route::post('upload-file', UploadTipFile::class)->name('upload');
        Route::get('download/{techTip}', DownloadTipController::class)
            ->name('download');
        Route::post('bookmark/{techTip}', TechTipBookmarkController::class)
            ->name('bookmark');
        /**
         * Tech Tip Comments Routes
         */
        Route::prefix('{tech_tip}')->group(function () {
            Route::post('flag-comment/{comment}', FlagCommentController::class)
                ->name('comments.flag');
            Route::resource('comments', TechTipCommentController::class)
                ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                    $breadcrumbs->index('Flagged Comments', 'tech-tips.show');
                })->except(['create', 'show', 'edit', 'destroy']);
        });
        Route::prefix('comments')->name('comments.')->group(function () {
            Route::get('flagged-comments', ShowFlaggedCommentsController::class)
                ->name('show-flagged');
            Route::get('restore/{comment}', [TechTipCommentController::class, 'restore'])
                ->name('restore');
            Route::delete('destroy/{comment}', [TechTipCommentController::class, 'destroy'])
                ->name('destroy');
        });
    });

    Route::resource('tech-tips', TechTipsController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips')
                ->create('Create Tech Tip')
                ->show(fn(TechTip $tech_tip) => 'Tip Details - ' . $tech_tip->subject)
                ->edit('Edit Tech Tip');
        });

});

/*******************************************************************************
 *                      Public Tech Tip Based Routes                           *
 *******************************************************************************/
Route::prefix('knowledge-base')->name('publicTips.')->group(function () {
    Route::get('/', [PublicTechTipController::class, 'index'])
        ->name('index');
    Route::post('/', [PublicTechTipController::class, 'search'])
        ->name('search');
    Route::get('{tip_slug}', [PublicTechTipController::class, 'show'])
        ->name('show');
});