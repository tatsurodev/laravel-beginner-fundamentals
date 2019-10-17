<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\ActivityComposer;

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
        // componentにaliasを作成する、Blade::component(component path, alias name)
        Blade::component('components.badge', 'badge');
        Blade::component('components.updated', 'updated');
        Blade::component('components.card', 'card');
        Blade::component('components.tags', 'tags');

        // View Composerの作成方法
        // 1. View Composer classを作成、AppServiceProvider or 他のServiceProviderに登録
        // 2. AppServiceProvider or 他のServiceProviderに登録の際、view composerの処理をclosureで指定
        // 下記は1のやり方で、View::composer()でもおｋ
        view()->composer(['posts.index', 'posts.show'], ActivityComposer::class);
    }
}
