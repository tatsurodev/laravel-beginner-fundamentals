<?php

namespace App\Providers;

use App\Comment;
use App\BlogPost;
use App\Services\Counter;
use App\Observers\CommentObserver;
use App\Observers\BlogPostObserver;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\ActivityComposer;
use App\Services\DummyCounter;
use App\Http\Resources\Comment as CommentResource;
use Illuminate\Http\Resources\Json\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // indexをつける時、defaultのstringだと255文字数*4bytes = 計1020bytesでindexにつけられる767 bytesの上限を超えてしまうので、「max key length is 767 bytes」のエラーが発生する。よってdefault stringの文字列数を767bytes以内になるようにstringの文字数を変更する
        Schema::defaultStringLength(191);
        // componentにaliasを作成する、Blade::component(component path, alias name)
        Blade::component('components.badge', 'badge');
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card');
        Blade::component('components.tags', 'tags');
        Blade::component('components.errors', 'errors');
        Blade::component('components.comment-form', 'commentForm');
        Blade::component('components.comment-list', 'commentList');

        // View Composerの作成方法
        // 1. View Composer classを作成、AppServiceProvider or 他のServiceProviderに登録
        // 2. AppServiceProvider or 他のServiceProviderに登録の際、view composerの処理をclosureで指定
        // 下記は1のやり方で、View::composer()でもおｋ
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
        // view()->composer('*', ActivityComposer::class);

        // observerの登録
        BlogPost::observe(BlogPostObserver::class);
        Comment::observe(CommentObserver::class);

        // service containerの登録
        $this->app->singleton(Counter::class, function ($app) {
            return new Counter(
                $app->make('Illuminate\Contracts\Cache\Factory'),
                $app->make('Illuminate\Contracts\Session\Session'),
                env('COUNTER_TIMEOUT')
            );
        });
        // bindを使ったservice containerの初期化方法
        // $this->app->when(Counter::class)->needs('$timeout')->give(env('COUNTER_TIMEOUT'));

        $this->app->bind(
            'App\Contracts\CounterContract',
            Counter::class
        );

        // $this->app->bind(
        //     'App\Contracts\CounterContract',
        //     DummyCounter::class
        // );

        // withoutWrapping methodでdata keyなしのjsonを返す
        // CommentResource::withoutWrapping();
        // Resource::withoutWrappingで全resource classをdata keyなしで返す
        Resource::withoutWrapping();
    }
}
