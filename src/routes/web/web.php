<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     // return view('welcome');
//     return Inertia::render('Home');
// });

Route::get('dashboard', function () {
    // return 'dashboard';
    return Inertia::render('Home/Dashboard');
})->name('dashboard');

Route::get('logout', [AuthenticatedSessionController::class, 'destroy']);
