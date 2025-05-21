<?php

use App\Exceptions\TechTip\TechTipNotFoundException;
use App\Http\Controllers\TechTip\DisabledTipViewController;
use App\Http\Controllers\TechTip\DisabledTipController;
use App\Http\Controllers\TechTip\DownloadTipController;
use App\Http\Controllers\TechTip\FlaggedCommentRestoreController;
use App\Http\Controllers\TechTip\FlaggedCommentsController;
use App\Http\Controllers\TechTip\FlagTipController;
use App\Http\Controllers\TechTip\SearchTipsController;
use App\Http\Controllers\TechTip\TechTipBookmarkController;
use App\Http\Controllers\TechTip\TechTipCommentController;
use App\Http\Controllers\TechTip\TechTipController;
use App\Http\Controllers\TechTip\TechTipSettingsController;
use App\Http\Controllers\TechTip\TechTipTypeController;
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
    /*
    |---------------------------------------------------------------------------
    | Tech Tip Administration
    | /administration/tech-tips
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration/tech-tips')->name('admin.tech-tips.')->group(function () {
        Route::controller(TechTipSettingsController::class)->name('settings.')->group(function () {
            Route::get('settings', 'edit')
                ->name('edit')
                ->breadcrumb('Tech Tip Settings', 'admin.index');
            Route::put('settings', 'update')->name('update');
        });
        Route::prefix('disabled-tips')->group(function () {
            Route::get('/', DisabledTipController::class)
                ->name('deleted-tips')
                ->breadcrumb('Deleted Tech Tips', 'admin.index');

            Route::controller(TechTipController::class)->group(function () {
                Route::get('restore-tip/{tech_tip}', 'restore')
                    ->name('restore')
                    ->withTrashed();
                Route::delete('force-delete/{tech_tip}', 'forceDelete')
                    ->name('force-delete')
                    ->withTrashed();
            });

            Route::get('flagged-comments', FlaggedCommentsController::class)
                ->name('flagged-comments.index')
                ->breadcrumb('Flagged Tech Tip Comments', 'admin.index');

            Route::get('flagged-comments/restore/{comment}', FlaggedCommentRestoreController::class)
                ->name('flagged-comments.restore');

            Route::get('{tech_tip}', DisabledTipViewController::class)
                ->withTrashed()
                ->name('deleted-tips.show')
                ->breadcrumb(
                    fn(TechTip $tech_tip) => $tech_tip->subject,
                    'admin.tech-tips.deleted-tips'
                );
        });

        Route::apiResource('tip-types', TechTipTypeController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Tech Tip Types', 'admin.index');
            })->except('show');
    });

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
    return 'public tips index';
})->name('publicTips.index');

Route::get('public-tips-show', function () {
    return ' show public tip';
})->name('publicTips.show');


// Route::get('admin-deleted-tips', function () {
//     return 'Deleted Tips';
// })->name('admin.tech-tips.deleted-tips');

// Route::get('admin-flagged-comments', function () {
//     return 'admin flagged comments';
// })->name('admin.tech-tips.flagged-comments.index');
