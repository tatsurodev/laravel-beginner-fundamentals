<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    // 下記で関連付けるテーブル名を指定、BlogPostモデルはデフォルトだとlaravelが自動的にblog_postsテーブルを参照する
    // protected $table = 'blogposts';

    // softdelete使用
    use SoftDeletes;

    protected $fillable = ['title', 'content'];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    // model events
    public static function boot()
    {
        parent::boot();

        // delete event前にclosureの中の処理が実行される
        // staticで遅延静的束縛
        // static::deleting(function (BlogPost $blogPost) {
        //     // postとrelationのあるcommentが削除される
        //     $blogPost->comments()->delete();
        // });
    }
}
