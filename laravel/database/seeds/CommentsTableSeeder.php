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

        // post数が0の場合、commentを作成できなので処理を中止
        if ($posts->count() === 0) {
            $this->command->info('There are no blog posts, so no comments will be added');
            return;
        }
        // 必要なcomment数をask。
        $commentsCount = (int) $this->command->ask('How many comments would you like?', 150);
        // commentsをmakeし、それぞれのcommentにpost_idを割り当ててsave
        factory(App\Comment::class, $commentsCount)->make()->each(function ($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
