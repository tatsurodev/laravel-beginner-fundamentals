<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 全posts取得
        $posts = App\BlogPost::all();
        // 全users取得
        $users = App\User::all();

        // post数が0の場合、commentを作成できなので処理を中止
        if ($posts->count() === 0 || $users->count() === 0) {
            $this->command->info('There are no blog posts or users, so no comments will be added');
            return;
        }
        // 必要なcomment数をask。
        $commentsCount = (int) $this->command->ask('How many comments would you like?', 150);
        // postに対するcommentsをmakeし、それぞれのcommentにpost_idを割り当ててsave
        factory(App\Comment::class, $commentsCount)->make()->each(function ($comment) use ($posts, $users) {
            $comment->commentable_id = $posts->random()->id;
            $comment->commentable_type = 'App\BlogPost';
            $comment->user_id = $users->random()->id;
            $comment->save();
        });

        // userに対するcomment
        factory(App\Comment::class, $commentsCount)->make()->each(function ($comment) use ($users) {
            $comment->commentable_id = $users->random()->id;
            $comment->commentable_type = 'App\User';
            $comment->user_id = $users->random()->id;
            $comment->save();
        });
    }
}
