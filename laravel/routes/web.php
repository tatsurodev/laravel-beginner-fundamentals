<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('home');
// });
// この上下は同値
Route::view('/', 'home')->name('home');

// Route::get('/contact', function () {
//     return view('contact');
// });

Route::view('/contact', 'contact')->name('contact');

// パラメーター名にハイフンは使えない
// 複数のパラメータは左から右へ順にクロージャへ渡されるのでパラメータ名とクロージャで受ける引数名は一致しなくてもおｋ
// 任意パラメータは?をつけ、クロージャの引数でデフォルトを指定
Route::get('/blog-post/{id}/{welcome?}', function ($id, $welcome = 1) {
    $pages = [
        1 => [
            'title' => 'from page 1'
        ],
        2 => [
            'title' => 'from page 2'
        ],
    ];

    $welcomes = [1 => '<b>Hello</b> <script>alert("xss")</script>', 2 => 'Welcome to '];

    // viewに渡す変数が複数あるので、配列で指定
    return view('blog-post', [
        'data' => $pages[$id],
        'welcome' => $welcomes[$welcome],
    ]);
})->name('blog-post');
