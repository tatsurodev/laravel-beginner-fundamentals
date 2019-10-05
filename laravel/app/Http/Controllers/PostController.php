<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('posts.index', ['posts' => BlogPost::all()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // findメソッドだと無効な$idを受け取った時にエラーとなるのでfindOrFailメソッドを使用する
        return view('posts.show', ['post' => BlogPost::findOrFail($id)]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $blogPost = new BlogPost();
        // inputメソッドの第2引数はデフォルト値を指定、$request->titleでもおｋ
        $blogPost->title = $request->input('title', 'Draft title');
        $blogPost->content = $request->input('content', 'Draft content');
        $blogPost->save();

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }
}
