<?php

namespace App;

use App\Scopes\LatestScope;
use App\Traits\Taggable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes, Taggable;

    protected $fillable = ['user_id', 'content'];

    // $hiddenでjsonで返すfieldを制限する
    protected $hidden = ['deleted_at', 'commentable_type', 'commentable_id',];

    // 子のblog_post_idと親のidの間にrelation作成
    // public function blogPost()
    // {
    //     // return $this->belongsTo('App\BlogPost', 'blog_post_id', 'id');
    //     return $this->belongsTo('App\BlogPost');
    // }

    // BlogPost, User model間にone to manyのpolymorphic relation作成
    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    // eventをobserverに移動させたのでboot methodはもう不要
    // public static function boot()
    // {
    //     parent::boot();
    //     // static::addGlobalScope(new LatestScope);

    //     // comment作成時に、relationからcacheされたblog-postを削除、でないと新たなcommentが反映されない
    //     static::creating(function (Comment $comment) {
    //         // commentはuserに対するものもあるのでblog-postの時だけblog-postのキャッシュをクリア。Comment,BlogPost modelは同じnamespace上にあるのでApp\BlogPost::classは間違い
    //         if ($comment->commentable_type === BlogPost::class) {
    //             Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
    //             Cache::tags(['blog-post'])->forget('mostCommented');
    //         }
    //     });
    // }
}
