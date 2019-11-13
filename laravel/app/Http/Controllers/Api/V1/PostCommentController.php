<?php

namespace App\Http\Controllers\Api\V1;

use App\Comment;
use App\BlogPost;
use Illuminate\Http\Request;
use App\Events\CommentPosted;
use App\Http\Requests\StoreComment;
use App\Http\Controllers\Controller;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    public function __construct()
    {
        // auth middlewareのapi guardを使用
        $this->middleware('auth:api')->only(['store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // api.v1.posts.comments.indexのroute paramとinstanceを格納する変数名を一致させる、URI api/v1/posts/{post}/comments
    public function index(BlogPost $post, Request $request)
    {
        $perPage = $request->input('per_page') ?? 15;
        // appends methodでlinkにquery追加
        return CommentResource::collection($post->comments()->with('user')->paginate($perPage))->appends([
            'per_page' => $perPage,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);
        event(new CommentPosted($comment));

        return new CommentResource($comment);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BlogPost $post, Comment $comment)
    {
        return new CommentResource($comment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BlogPost $post, Comment $comment, StoreComment $request)
    {
        $this->authorize($comment);
        $comment->content = $request->input('content');
        $comment->save();
        return new CommentResource($comment);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BlogPost $post, Comment $comment)
    {
        $this->authorize($comment);
        $comment->delete();
        // noContent methodでHTTPステータスコードの204 No Contentを返す
        return response()->noContent();
    }
}
