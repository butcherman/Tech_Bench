<?php

use App\Http\Controllers\TechTips\DeletedTipsController;
use App\Http\Controllers\TechTips\ShowDeletedTipController;
use App\Http\Controllers\TechTips\TechTipCommentController;
use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\SearchTipsController;

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

    Route::prefix('tech-tips')->name('tech-tips.')->group(function () {
        Route::post('search', SearchTipsController::class)->name('search');
        Route::post('upload-file', UploadTipFile::class)->name('upload');
        Route::resource('{tech_tip}/comments', TechTipCommentController::class);
    });


    Route::resource('tech-tips', TechTipsController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips')
                ->create('Create Tech Tip')
                ->show(fn(TechTip $tech_tip) => 'Tip Details - ' . $tech_tip->subject)
                ->edit('Edit Tech Tip');
        });

});