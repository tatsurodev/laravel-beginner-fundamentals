<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Mail\CommentPosted;
use Illuminate\Http\Request;
use App\Http\Requests\StoreComment;
use App\Mail\CommentPostedMarkdown;
use Illuminate\Support\Facades\Mail;

class PostCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    public function store(BlogPost $post, StoreComment $request)
    {
        $comment = $post->comments()->create([
            'content' => $request->input('content'),
            'user_id' => $request->user()->id,
        ]);
        // mail送信
        Mail::to($post->user)->send(
            // 作成したcomment instanceをCommentPostedのconstructorに渡してcomment propertyにセット
            // new CommentPosted($comment)
            new CommentPostedMarkdown($comment)
        );
        // with('status', 'Comment was created!')とwithStatus('Comment was created!')は同値
        return redirect()->back()->withStatus('Comment was created!');
    }
}
