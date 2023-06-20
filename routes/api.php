<?php

use Illuminate\Support\Facades\Route;

/*
 |--------------------------------------------------------------------------
 | API Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register API routes for your application. These
 | routes are loaded by the RouteServiceProvider and all of them will
 | be assigned to the "api" middleware group. Make something great!
 |
 */

/**
 * Api Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {
    includeRouteFiles(__DIR__ . '/api/');
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
// });
