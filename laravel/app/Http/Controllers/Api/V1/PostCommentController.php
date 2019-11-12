<?php

namespace App\Http\Controllers\Api\V1;

use App\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Comment as CommentResource;

class PostCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // api.v1.posts.comments.indexのroute paramとinstanceを格納する変数名を一致させる、URI api/v1/posts/{post}/comments
    public function index(BlogPost $post, Request $request)
    {
        $perPage = (int) $request->input('per_page') ?? 15;
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
