<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class LatestScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        // constのModel::created_atでtimestampsの'created_at'のfield nameを変更できるので、'created_at'をorderByに指定するのではなく定数自体を指定する
        $builder->orderBy($model::CREATED_AT, 'desc');
    }
}
