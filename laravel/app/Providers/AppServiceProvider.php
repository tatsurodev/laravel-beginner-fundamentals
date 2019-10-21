<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\ActivityComposer;
use Illuminate\Support\Facades\Schema;

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

        // View Composerの作成方法
        // 1. View Composer classを作成、AppServiceProvider or 他のServiceProviderに登録
        // 2. AppServiceProvider or 他のServiceProviderに登録の際、view composerの処理をclosureで指定
        // 下記は1のやり方で、View::composer()でもおｋ
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
        // view()->composer('*', ActivityComposer::class);
    }
}
