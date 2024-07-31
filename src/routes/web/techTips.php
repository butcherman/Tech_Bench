<?php

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
    });


    Route::resource('tech-tips', TechTipsController::class)
        ->breadcrumbs(function (ResourceBreadcrumbs $breadcrumbs) {
            $breadcrumbs->index('Tech Tips')
                ->create('Create Tech Tip')
                ->show(fn(TechTip $tech_tip) => 'Tip Details - ' . $tech_tip->subject)
                ->edit('Edit Tech Tip');
        });

    Route::post('tech-tips/search', SearchTipsController::class)->name('tech-tips.search');
    Route::post('tech-tips/upload-file', UploadTipFile::class)->name('tech-tips.upload');
});