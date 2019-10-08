<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // 子のblog_post_idと親のidの間にrelation作成
    public function blogPost()
    {
        // return $this->belongsTo('App\BlogPost', 'blog_post_id', 'id');
        return $this->belongsTo('App\BlogPost');
    }
}
