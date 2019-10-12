<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // PostController@update用gate
        // Gate::define('gate_name', function ($user, $instance) { return bool })
        // Gate::define('update-post', function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });

        // PostController@destroy用gate
        // Gate::define('delete-post', function ($user, $post) {
        //     return $user->id == $post->id;
        // });

        // policyの登録
        // Gate::define('posts.update', 'App\Policies\BlogPostPolicy@update');
        // Gate::define('posts.delete', 'App\Policies\BlogPostPolicy@delete');

        // resource methodでposts.create, posts.view, posts.update, posts.deleteを登録
        Gate::resource('posts', 'App\Policies\BlogPostPolicy');

        // admin userに特定のabilityを付与
        // gate checkがcallされる前にこの処理が呼ばれる
        // Gate::before(function ($user, $ability) {
        //     // userがadminかつ、リストの中にあるabilityは、gateをpassできる
        //     if ($user->is_admin && in_array($ability, ['posts.update',])) {
        //         return true;
        //     }
        // });

        // gete check後にafterが呼ばれ、gate checkの結果が第三引数$resultに格納され、通常のgate checkが終わった後でもこのGate::afterで結果をまだ変えることができる
        // Gate::after(function ($user, $ability, $result) {
        //     if ($user->is_admin) {
        //         return true;
        //     }
        // });
    }
}
