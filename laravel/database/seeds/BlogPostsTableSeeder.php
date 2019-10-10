<?php

use Illuminate\Database\Seeder;

class BlogPostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全ユーザー取得
        $users = App\User::all();
        // postsをmakeし、それぞれのpostにuser_idを割り当ててsave。postsをsaveだといきなりdataを作成することになり、user_idがないのでエラーとなってしまうので、メモリー上に一時的に作成するmakeを使う
        // eachの中のclosureは外の変数にアクセスできないのでuse ($variable)でアクセスできるようにする
        $posts = factory(App\BlogPost::class, 50)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });
    }
}
