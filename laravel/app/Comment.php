<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    // 子のblog_post_idと親のidの間にrelation作成
    public function blogPost()
    {
        // return $this->belongsTo('App\BlogPost', 'blog_post_id', 'id');
        return $this->belongsTo('App\BlogPost');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }
    public static function boot()
    {
        parent::boot();
        // static::addGlobalScope(new LatestScope);
    }
}
