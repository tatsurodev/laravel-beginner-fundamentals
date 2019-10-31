<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = null;
        // login userでlocale keyのsessionなしの場合、locale(言語)の値をsessionに格納
        if (Auth::check() && !Session::has('locale')) {
            $locale = $request->user()->locale;
            Session::put('locale', $locale);
        }
        // request paramsにlocaleの値がある場合、session(言語)の値をsessionに格納し直し。formで言語設定を更新しようとした場合など
        if ($request->has('locale')) {
            $locale = $request->get('locale');
            Session::put('locale', $locale);
        }
        // locale keyのsessionを変数に格納
        $locale = Session::get('locale');
        // userがloginしていない、言語設定のform requestを受けていない場合、defaultの言語設定を使用
        if ($locale === null) {
            $locale = config('app.fallback_locale');
        }
        // appの言語設定を変更
        App::setLocale($locale);

        return $next($request);
    }
}
