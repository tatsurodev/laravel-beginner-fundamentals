<?php

use Illuminate\Database\Seeder;
// use  Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // UserFactoryにadmin stateとして作成
        // DB::table('users')->insert([
        //     'name' => 'John Doe',
        //     'email' => 'contacts@tatsuro.dev',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10)
        // ]);

        // login用ユーザー
        $admin = factory(App\User::class)->states('admin')->create();

        // その他ダミーユーザー
        $else = factory(App\User::class, 20)->create();

        // dd(get_class($admin)); //"App\User"
        // dd(get_class($else); // "Illuminate\Database\Eloquent\Collection"

        // 作成した全ユーザーのcollection作成
        $users = $else->concat([$admin]);
        // dd($users->count());

        // postsをmakeし、それぞれのpostにuser_idを割り当ててsave。postsをsaveだといきなりdataを作成することになり、user_idがないのでエラーとなってしまうので、メモリー上に一時的に作成するmakeを使う
        // eachの中のclosureは外の変数にアクセスできないのでuse ($variable)でアクセスできるようにする
        $posts = factory(App\BlogPost::class, 50)->make()->each(function ($post) use ($users) {
            $post->user_id = $users->random()->id;
            $post->save();
        });

        // commentsをmakeし、それぞれのcommentにpost_idを割り当ててsave
        $comments = factory(App\Comment::class, 150)->make()->each(function ($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
