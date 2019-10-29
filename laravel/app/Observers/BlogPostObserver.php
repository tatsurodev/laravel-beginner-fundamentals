<?php

namespace App\Observers;

use App\BlogPost;

class BlogPostObserver
{
    // post更新時にcache削除
    public function updating(BlogPost $blogPost)
    {
        Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
    }

    // delete event前にclosureの中の処理(postに関連するcommentの削除)が実行される
    public function deleting(BlogPost $blogPost)
    {
        // dd("I'm deleted");
        // postとrelationのあるcommentが削除される
        // 取得したモデルをdeleteで削除(複数可)、idで削除する場合はdestoryを使用(複数可)
        $blogPost->comments()->delete();
        Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
    }

    // postのrestoreの前に関連するcommentもrestore
    public function restoring(BlogPost $blogPost)
    {
        $blogPost->comments()->restore();
    }
}
