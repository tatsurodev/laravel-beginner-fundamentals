<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentUser extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            // when(condition, returned value)
            // userがadminならのemailも表示したいので、第一引数にAuth::user()->is_adminとしたいがapiでまだauthenticationを実装していないので便宜的にtrueとしておく
            'email' => $this->when(true, $this->email),
        ];
    }
}
