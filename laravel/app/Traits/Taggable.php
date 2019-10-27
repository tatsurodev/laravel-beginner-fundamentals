<?php

namespace App\Traits;

use App\Tag;

trait Taggable
{
    // Taggable traitを使用するmodelでfindTagsInContent methodをmodelのupdating, created時に使用しようとすると使用するmodelで毎回static::update, static::created methodを作成しないといけないが、boot+Trait_nameのfunctionを定義することで、このtraitを使用する全modelのboot methodに自動的にセットされるのでメンテナンスしやすい
    protected static function bootTaggable()
    {
        // modelのupdating, created時にmodelのcontent field内にtag modelのnameと同じものがあれば自動的にtagとのrelationを作成する
        static::updating(function ($model) {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
        static::created(function ($model) {
            $model->tags()->sync(static::findTagsInContent($model->content));
        });
    }
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable')->withTimestamps();
    }

    private static function findTagsInContent($content)
    {
        // preg_match_all(パターン, 検索文字列, 結果を格納した配列), preg_matchだと最初にマッチした部分だけreturn
        preg_match_all('/@([^@]+)@/m', $content, $tags);
        // matchした部分をTag modelのnameに含んでいるtag instanceを返す
        return Tag::whereIn('name', $tags[1] ?? [])->get();
    }
}
