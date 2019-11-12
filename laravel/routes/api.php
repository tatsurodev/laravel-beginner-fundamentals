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

// prefixの前にRouteServiceProviderのmapApiRoutes methodで指定されているprefixが使用される
// name methodでこのグループのname prefixを指定
// namespace methodでcontrollerのnamespaceを指定、RouteServiceProviderの$namespaceからの階層を指定
Route::prefix('v1')->name('api.v1.')->namespace('Api\V1')->group(function () {
    Route::get('/status', function () {
        return response()->json(['status' => 'OK']);
    })->name('status');
    // apiResource methodでwebと同じようなresourcefulなrouteを指定できる、webとの違いはcreate, editがapiには不要なのでない
    // route nameはこのグループのapi.v1.とposts.commentsを足したapi.v1.posts.commentsとなる
    // controllerの場所は Api/V1/PostCommentController
    Route::apiResource('posts.comments', 'PostCommentController');
});

Route::prefix('v2')->name('api.v2.')->group(function () {
    Route::get('/status', function () {
        return response()->json(['status' => true]);
    })->name('status');
});
