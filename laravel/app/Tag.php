<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // BlogPost modelとの多対多polymorphic relation
    public function blogPosts()
    {
        // withTimestampsでtimestamps追加、asで中間テーブル名をpivotから変更
        // return $this->belongsToMany('App\BlogPost')->withTimestamps()->as('tagged');
        return $this->morphedByMany('App\BlogPost', 'taggable')->withTimestamps()->as('tagged');
    }

    // Comment modelとの多対多polymorphic relation
    public function comments()
    {
        return $this->morphedByMany('App\Comment', 'taggable')->withTimestamps()->as('tagged');
    }
}
