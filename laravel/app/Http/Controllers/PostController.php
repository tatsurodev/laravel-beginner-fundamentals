<?php

namespace App\Http\Controllers;

use App\BlogPost;
use App\Http\Requests\StorePost;
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
    public function show(Request $request, $id)
    {
        // sessionの復元, session()->reflash()でもおｋ
        // $request->session()->reflash();
        // findメソッドだと無効な$idを受け取った時にエラーとなるのでfindOrFailメソッドを使用する
        return view('posts.show', ['post' => BlogPost::findOrFail($id)]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        // validatedでvalidation済のデータ取得
        $validatedData = $request->validated();

        // instanceを作成してレコード挿入する方法
        // $blogPost = new BlogPost();
        // // inputメソッドの第2引数はデフォルト値を指定、$request->titleでもおｋ
        // $blogPost->title = $request->input('title', 'Draft title');
        // $blogPost->content = $request->input('content', 'Draft content');
        // $blogPost->save();

        // static methodのcreateでmass assignment
        $blogPost = BlogPost::create($validatedData);

        // sessionでメッセージ格納
        $request->session()->flash('status', 'Blog post was created!');
        // 上下は同値
        // session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
        // 上下は同値
        // return redirect(route('posts.show', ['post' => $blogPost->id]));
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('posts.edit', ['post' => $post]);
    }

    public function update()
    { }
}
