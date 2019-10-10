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
        // commentsをmakeし、それぞれのcommentにpost_idを割り当ててsave
        $comments = factory(App\Comment::class, 150)->make()->each(function ($comment) use ($posts) {
            $comment->blog_post_id = $posts->random()->id;
            $comment->save();
        });
    }
}
