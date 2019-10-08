<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    // 下記で関連付けるテーブル名を指定、BlogPostモデルはデフォルトだとlaravelが自動的にblog_postsテーブルを参照する
    // protected $table = 'blogposts';

    protected $fillable = ['title', 'content'];

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }
}
