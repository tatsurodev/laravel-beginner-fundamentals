<?php

namespace App;

use App\Scopes\LatestScope;
use App\Scopes\DeletedAdminScope;
use App\Traits\Taggable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    // 命名規則通りなら自動的にmodelとtableは紐付けられる
    // model名: 単数、アッパーキャメルケース(パスカルケース) ex.BlogPost, AccessRanking
    // table名: 複数、スネークケース ex. blog_posts, access_rankings
    // 命名規則外の場合、下記で関連付けるテーブル名を指定できる
    // protected $table = 'blogposts';

    // softdelete使用
    use SoftDeletes, Taggable;

    // Model::create()で複数代入を行うときは、$fillable(ホワイトリスト、追加して良いカラム) or $guarded(ブラックリスト、追加してはいけないカラム)の指定が必須
    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        // one to manyのpolymorphic relation
        // local scope使用
        return $this->morphMany('App\Comment', 'commentable')->latest();
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    // one to one polymorphic relation作成、morphOne('子クラス名', '子クラスでリンクするid fieldのprefix')
    public function image()
    {
        return $this->morphOne('App\Image', 'imageable');
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

    public function scopeLatestWithRelations(Builder $query)
    {
        return $query->latest()->withCount('comments')->with('user')->with('tags');
    }

    // model events
    public static function boot()
    {
        // bootより前に追加
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // global scopeにLatestScope登録
        // static::addGlobalScope(new LatestScope);

        // recordのdeleteにはdb的に２つの意味がある、softdeletとcascade
        // softdelete: recordにdeleted_atをつけただけで物理的には削除されていない
        // cascade: dbの子tableの外部キーにcascadeをつけて参照先の親のrecordが削除された時に共に物理的にも削除する
        // このprojectでは、最終的にsoftDeletesされた時は、下記のevent listenerでpostのdelete, restore時に関連するcommentをsofltdelete, restoreし、またforcedeleteされた時は、comments tableのmigration fileで設定されたcascadeで関連するcommentを物理的に削除している

        // 下3つのeventはobserverに移動
        // delete event前にclosureの中の処理(postに関連するcommentの削除)が実行される
        // staticで遅延静的束縛(https://note.mu/gallu/n/n3da09e4ce43e)
        // $this-> : インスタンス化したオブジェクトへの参照、self:: : 使われた場所の自クラスへの参照(親クラスのmethodでselfが使われた時、子クラスで呼び出すと親クラスの参照が得られる)、static: 呼び出し元への参照(親クラスのmethodでselfが使われた時、子クラスで呼び出すと子クラスの参照が得られる)
        // static::deleting(function (BlogPost $blogPost) {
        //     // postとrelationのあるcommentが削除される
        //     // 取得したモデルをdeleteで削除(複数可)、idで削除する場合はdestoryを使用(複数可)
        //     $blogPost->comments()->delete();
        //     Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        // });

        // postのrestoreの前に関連するcommentもrestore
        // static::restoring(function (BlogPost $blogPost) {
        //     $blogPost->comments()->restore();
        // });

        // post更新時にcache削除
        // static::updating(function (BlogPost $blogPost) {
        //     Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        // });
    }
}
