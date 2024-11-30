<?php

use App\Exceptions\TechTip\TechTipNotFoundException;
use App\Http\Controllers\TechTip\DisabledTechTipController;
use App\Http\Controllers\TechTip\DownloadTechTipController;
use App\Http\Controllers\TechTip\SearchTipsController;
use App\Http\Controllers\TechTip\ShowDisabledTipController;
use App\Http\Controllers\TechTip\TechTipBookmarkController;
use App\Http\Controllers\TechTip\TechTipController;
use App\Http\Controllers\TechTip\TechTipSettingsController;
use App\Http\Controllers\TechTip\TechTipTypeController;
use App\Http\Controllers\TechTip\UploadTechTipFileController;
use App\Models\TechTip;
use Glhd\Gretel\Routing\ResourceBreadcrumbs;
use Illuminate\Support\Facades\Route;

/*
|-------------------------------------------------------------------------------
| Tech Tip Routes
|-------------------------------------------------------------------------------
*/
Route::middleware('auth.secure')->group(function () {
    /*
    |---------------------------------------------------------------------------
    | Tech Tip Searching and Verification
    | /tech-tips
    |---------------------------------------------------------------------------
    */
    Route::prefix('tech-tips')->name('tech-tips.')->group(function () {
        Route::post('search', SearchTipsController::class)->name('search');
        Route::post('upload-file', UploadTechTipFileController::class)
            ->name('upload');
        Route::get('download/{techTip}', DownloadTechTipController::class)
            ->name('download');
        Route::post('bookmark/{techTip}', TechTipBookmarkController::class)
            ->name('bookmark');
    });

    /*
    |---------------------------------------------------------------------------
    | Tech Tip Administration
    | /administration/tech-tips
    |---------------------------------------------------------------------------
    */
    Route::prefix('administration/tech-tips')->name('admin.tech-tips.')->group(function () {
        /*
        |-----------------------------------------------------------------------
        | Tech Tip Settings
        | /administration/tech-tips/settings
        |-----------------------------------------------------------------------
        */
        Route::controller(TechTipSettingsController::class)
            ->prefix('settings')
            ->name('settings.')
            ->group(function () {
                Route::get('/', 'edit')
                    ->name('edit')
                    ->breadcrumb('Tech Tip Settings', 'admin.index');
                Route::put('/', 'update')->name('update');
            });

        /*
        |-----------------------------------------------------------------------
        | Tech Tip Types
        | /administration/tech-tips/tip-types
        |-----------------------------------------------------------------------
        */
        Route::resource('tip-types', TechTipTypeController::class)
            ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
                $breadcrumbs->index('Tech Tip Types', 'admin.index');
            })->except(['create', 'edit', 'show']);

        /*
        |-----------------------------------------------------------------------
        | Disabled Tech Tips
        | /administration/tech-tips/deleted-tips
        |-----------------------------------------------------------------------
        */
        Route::prefix('disabled-tips')->group(function () {
            Route::get('/', DisabledTechTipController::class)
                ->name('deleted-tips')
                ->breadcrumb('Deleted Tech Tips', 'admin.index');

            Route::get('{techTip}', ShowDisabledTipController::class)
                ->withTrashed()
                ->name('deleted-tips.show')
                ->breadcrumb(fn (TechTip $techTip) => 'Tip Details - '.$techTip->subject, 'admin.tech-tips.deleted-tips');

            Route::controller(TechTipController::class)->group(function () {
                Route::get('restore-tip/{tech_tip}', 'restore')
                    ->name('restore')
                    ->withTrashed();
                Route::delete('force-delete/{tech_tip}', 'forceDelete')
                    ->name('force-delete')
                    ->withTrashed();
            });
        });
    });

    /*
    |---------------------------------------------------------------------------
    | Tech Tip Service Routes
    | /tech-tips
    |---------------------------------------------------------------------------
    */
    Route::inertia('tech-tips/not-found', 'TechTips/NotFound')
        ->name('tech-tips.not-found');
    Route::resource('tech-tips', TechTipController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips')
                ->create('Create Tech Tip')
                ->show(
                    fn (TechTip|string $tech_tip) => gettype($tech_tip) === 'object' ? $tech_tip->subject : $tech_tip
                )
                ->edit('Edit Tech Tip');
        })->missing(function () {
            throw new TechTipNotFoundException;
        });
});
