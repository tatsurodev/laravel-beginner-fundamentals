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
// Route::view('/', 'home')->name('home');

Route::get('/', 'HomeController@home')
    // routeにmiddlewareを追加
    // ->middleware('auth')
    ->name('home');

// Route::get('/contact', function () {
//     return view('contact');
// });

Route::get('/contact', 'HomeController@contact')->name('contact');

// routeにcan middlewareのhome.secretを設定、第一引数にgate名、第二引数にgate, policyに渡したいroute param名
Route::get('/secret', 'HomeController@secret')->name('secret')->middleware('can:home.secret');

// パラメーター名にハイフンは使えない
// 複数のパラメータは左から右へ順にクロージャへ渡されるのでパラメータ名とクロージャで受ける引数名は一致しなくてもおｋ
// 任意パラメータは?をつけ、クロージャの引数でデフォルトを指定
/*
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
*/

// 上記ロジックをcontrollerへ移動
// Route::get('/blog-post/{id}/{welcome?}', 'HomeController@blogPost')->name('blog-post');

// resource methodを使用すると一気にURI, route parameter, named route等を設定してくれるので便利、使用するアクションのみonly、exceptで絞り込む
// onlyで使用するアクションのみ指定
// Route::resource('/posts', 'PostController')->only(['index', 'show', 'create', 'store', 'edit', 'update']);
// 上下は同値
// exceptで除外するactionを指定
// Route::resource('/posts', 'PostController')->except(['destroy']);

Route::resource('posts', 'PostController');
// tag検索
Route::get('/posts/tag/{tag}', 'PostTagController@index')->name('posts.tags.index');

Route::resource('posts.comments', 'PostCommentController')->only(['index', 'store']);
Route::resource('users.comments', 'UserCommentController')->only(['store']);

Route::resource('users', 'UserController')->only(['show', 'edit', 'update',]);

// email preview
Route::get('mailable', function () {
    $comment = App\Comment::find(1);
    return new App\Mail\CommentPostedMarkdown($comment);
});

Auth::routes();
