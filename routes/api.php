<?php

use Illuminate\Http\Request;
use slowdream\kit_laravel\Kit;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/iscity', function (Request $request, Kit $kit) {
    return response()->json(!!$kit->isCity($request->victim));
});


