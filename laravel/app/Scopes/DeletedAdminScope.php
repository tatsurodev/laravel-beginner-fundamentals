<?php

namespace App\Scopes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class DeletedAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            // admin userのみ削除済のsoftdeletesされたposts閲覧可にする
            // $builder->withTrashed();
            // SoftDeletes traitからsoftdeletes用のglobal scopeを取り除く
            $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
        }
    }
}
