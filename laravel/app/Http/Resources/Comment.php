<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CommentUser as CommentUserResource;

class Comment extends JsonResource
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
            'content' => $this->content,
            // string型にしないとcarbonが返ってくる
            'created_at' => (string) $this->created_at,
            'updated_at' => (string) $this->updated_at,
            // commen userのrelation用にresourceを作成、
            'user' => new CommentUserResource($this->user),
        ];
    }
}
