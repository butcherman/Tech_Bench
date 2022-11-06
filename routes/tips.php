<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TechTips\TechTipsController;
use App\Http\Controllers\TechTips\SearchTipsController;
use App\Http\Controllers\TechTips\DownloadTipController;
use App\Http\Controllers\TechTips\TechTipTypesController;
use App\Http\Controllers\TechTips\GetTipDetailsController;
use App\Http\Controllers\TechTips\ShowDeletedTipController;
use App\Http\Controllers\TechTips\TechTipBookmarkController;
use App\Http\Controllers\TechTips\DeletedTechTipsController;
use App\Http\Controllers\TechTips\TechTipCommentsController;

Route::get('{tip}', function() { return 'tech tips'; })->name('tech-tips.show');

/**
 * Tech Tips routes
 */
// Route::middleware('auth')->group(function()
// {
//     Route::prefix('tech-tips')->name('tips.')->group(function()
//     {
//         Route::post('bookmark',             TechTipBookmarkController::class)->name('bookmark');
//         Route::get( 'search',               SearchTipsController::class)     ->name('search');
//         Route::get( '{id}/get-details',     GetTipDetailsController::class)  ->name('details');
//         Route::get( '{id}/download',        DownloadTipController::class)    ->name('download');
//         Route::get( '{id}/show-deleted',    ShowDeletedTipController::class) ->name('show-deleted')->breadcrumb('Tip Details', 'admin.tips.deleted');

//         Route::prefix('comments')->name('comments.')->group(function()
//         {
//             Route::get('/',              [TechTipCommentsController::class, 'index'])  ->name('index')->breadcrumb('Flagged Comments', 'admin.index');
//             Route::post('/',             [TechTipCommentsController::class, 'store'])  ->name('store');
//             Route::get('{comment}',      [TechTipCommentsController::class, 'show'])   ->name('show');
//             Route::get('{comment}/edit', [TechTipCommentsController::class, 'edit'])   ->name('edit');
//             Route::put('{comment}/edit', [TechTipCommentsController::class, 'update']) ->name('update');
//             Route::delete('{comment}',   [TechTipCommentsController::class, 'destroy'])->name('destroy');
//         });
//     });

//     // Route::resource('tech-tips',            TechTipsController::class);
//     Route::prefix('tech-tips')->name('tech-tips.')->group(function()
//     {
//         Route::get('/',               [TechTipsController::class, 'index'])  ->name('index') ->breadcrumb('Tech Tips');
//         Route::get('/create',         [TechTipsController::class, 'create']) ->name('create')->breadcrumb('New Tip', '.index');
//         Route::post('/',              [TechTipsController::class, 'store'])  ->name('store');
//         Route::get('{tech_tip}',      [TechTipsController::class, 'show'])   ->name('show')  ->breadcrumb('Details', '.index');
//         Route::get('{tech_tip}/edit', [TechTipsController::class, 'edit'])   ->name('edit')  ->breadcrumb('Edit Tip', '.show');
//         Route::put('{tech_tip}/edit', [TechTipsController::class, 'update']) ->name('update');
//         Route::delete('{tech_tip}',   [TechTipsController::class, 'destroy'])->name('destroy');
//     });

//     //  Tech Tip Administration Routes
//     Route::prefix('administration/tips')->name('admin.tips.')->group(function()
//     {
//         Route::get('deleted-tips',          DeletedTechTipsController::class)         ->name('deleted')->breadcrumb('Deleted Tech Tips', 'admin.index');
//         Route::get('{id}/restore',         [TechTipsController::class, 'restore'])    ->name('restore');
//         Route::delete('{id}/force-delete', [TechTipsController::class, 'forceDelete'])->name('force-delete');

//         Route::prefix('tip-types')->name('tip-types.')->group(function()
//         {
//             Route::get('/',               [TechTipTypesController::class, 'index'])  ->name('index')->breadcrumb('Tech Tip Types', 'admin.index');
//             Route::post('/',              [TechTipTypesController::class, 'store'])  ->name('store');
//             Route::put('{tip_type}/edit', [TechTipTypesController::class, 'update']) ->name('update');
//             Route::delete('{tip_type}',   [TechTipTypesController::class, 'destroy'])->name('destroy');
//         });
//     });
// });
