<?php

namespace App\Observers;

use App\Comment;
use App\BlogPost;
use Illuminate\Support\Facades\Cache;

class CommentObserver
{
    /**
     * Handle the comment "created" event.
     *
     * @param  \App\Comment  $comment
     * @return void
     */

    // comment作成時に、relationからcacheされたblog-postを削除、でないと新たなcommentが反映されない
    public function creating(Comment $comment)
    {
        // commentはuserに対するものもあるのでblog-postの時だけblog-postのキャッシュをクリア。Comment,BlogPost modelは同じnamespace上にあるのでApp\BlogPost::classは間違い
        if ($comment->commentable_type === BlogPost::class) {
            // dd("I'm created");
            Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
            Cache::tags(['blog-post'])->forget('mostCommented');
        }
    }
}
