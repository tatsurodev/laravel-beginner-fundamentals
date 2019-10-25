<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    // relationの命名規則はmethodと一緒でキャメルケース(単数モデル名でキャメルケース or 複数モデル名キャメルケース)
    // hasOne, hasMany, belongsTo引数共通
    // 第２引数に子テーブルの外部キー、第３引数に親テーブルの主キー
    public function profile()
    {
        return $this->hasOne('App\Profile');
    }
}
