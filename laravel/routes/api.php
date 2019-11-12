<?php

use Illuminate\Http\Request;

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

// middleware('auth:api')は、auth middlewareのapi guardを使用という意味
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
