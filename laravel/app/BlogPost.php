<?php

namespace App;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    // 下記で関連付けるテーブル名を指定、BlogPostモデルはデフォルトだとlaravelが自動的にblog_postsテーブルを参照する
    // protected $table = 'blogposts';

    // softdelete使用
    use SoftDeletes;

    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        // local scope使用
        return $this->hasMany('App\Comment')->latest();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // local scope作成
    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // comments_count
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }

    // model events
    public static function boot()
    {
        parent::boot();

        // global scopeにLatestScope登録
        // static::addGlobalScope(new LatestScope);

        // recordのdeleteにはdb的に２つの意味がある、softdeletとcascade
        // softdelete: recordにdeleted_atをつけただけで物理的には削除されていない
        // cascade: dbの子tableの外部キーにcascadeをつけて参照先の親のrecordが削除された時に共に物理的にも削除する
        // このprojectでは、最終的に下記のevent listenerでpostのdelete, restore時に関連するcommentをsofltdeleteし、またforcedeleteされた場合は、comments tableのmigration fileで設定されたcascadeで関連するcommentを物理的に削除している
        // delete event前にclosureの中の処理(postに関連するcommentの削除)が実行される
        // staticで遅延静的束縛
        static::deleting(function (BlogPost $blogPost) {
            // postとrelationのあるcommentが削除される
            $blogPost->comments()->delete();
        });

        // postのrestoreの前に関連するcommentもrestore
        static::restoring(function (BlogPost $blogPost) {
            $blogPost->comments()->restore();
        });
    }
}
