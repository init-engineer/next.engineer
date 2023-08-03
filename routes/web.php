<?php

use App\Http\Controllers\LocaleController;
use Illuminate\Support\Facades\Route;

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

/**
 * Switch between the included languages.
 */
Route::get('lang/{lang}', [LocaleController::class, 'change'])->name('locale.change');

/**
 * Frontend Routes
 */
Route::group([
    'as' => 'frontend.',
], function () {
    includeRouteFiles(__DIR__.'/frontend/');
});

/**
 * Backend Routes
 *
 * These routes can only be accessed by users with type `admin`
 */
Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'admin',
], function () {
    includeRouteFiles(__DIR__.'/backend/');
});
