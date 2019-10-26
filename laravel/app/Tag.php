<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function blogPosts()
    {
        // withTimestampsでtimestamps追加、asで中間テーブル名をpivotから変更
        // return $this->belongsToMany('App\BlogPost')->withTimestamps()->as('tagged');
        return $this->morphedByMany('App\BlogPost', 'taggable')->withTimestamps()->as('tagged');
    }
}
