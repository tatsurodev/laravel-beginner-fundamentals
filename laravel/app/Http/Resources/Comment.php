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
            // whenLoadedはuser relationがeagear loadingされていないときに取り除いてくれる、これで不要なn+1問題を避けれる
            'user' => new CommentUserResource($this->whenLoaded('user')),
        ];
    }
}
