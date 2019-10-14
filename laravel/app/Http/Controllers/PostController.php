<?php

namespace App\Http\Controllers;

use App\User;
use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Gate;
// use Illuminate\Support\Facades\DB;

// actionとpolicyの対応
// action => policy
// [
//     'show' => 'view',
//     'create' => 'create',
//     'store' => 'create',
//     'edit' => 'update',
//     'update' => 'update',
//     'destroy' => 'delete',
// ]

class PostController extends Controller
{
    // controller内でのmiddlewareの設定
    public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // lazy loading vs eager loading performance check
        /*
        // 全queryをlogに記録
        DB::connection()->enableQueryLog();

        // lazy loading
        // $post = BlogPost::all();
        // eager loading
        $posts = BlogPost::with('comments')->get();
        foreach ($posts as $post) {
            foreach ($post->comments as $comment) {
                echo $comment->comment;
            }
        }
        // 全query logをdd
        dd(DB::getQueryLog());
        */

        // comments数も一緒に渡す
        // withCount('relation')で、relation数をrelation_count fieldをモデルに追加できる
        // local scope latestを使用
        return view('posts.index', [
            'posts' => BlogPost::latest()->withCount('comments')->get(),
            'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
            'mostActive' => User::withMostBlogPosts()->take(5)->get(),
        ]);
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
        return view('posts.show', ['post' => BlogPost::with(
            // eager loadにlatest scope追加
            // ['comments' => function ($query) {
            //     return $query->latest();
            // }]
            // BlogPost modelのcomments relation取得時にlocal scopeのlatestを適用している
            'comments'
        )->findOrFail($id)]);
    }

    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        // validatedでvalidation済のデータ取得
        $validatedData = $request->validated();

        // 現ユーザーのidをセット
        $validatedData['user_id'] = $request->user()->id;

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
        // if (Gate::denies('update-post', $post)) {
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        // gateでauthrization
        // if (Gate::denies('update-post', $post)) {
        //     // abort(status, msg)
        //     abort(403, "You can't edit this blog post!");
        // }
        $this->authorize($post);
        $validatedData = $request->validated();
        // 既にあるinstanceに対するmass assignmentはfill methodを使用
        $post->fill($validatedData);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');
        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        // model instanceを取得後削除するにはdelete method、複数可
        $post = BlogPost::findOrFail($id);
        // if (Gate::denies('delete-post', $post)) {
        //     abort(403, "You can't delete this blog post!");
        // }
        $this->authorize($post);
        $post->delete();

        // primary keyで削除するにはdestroy method、複数可
        // BlogPost::destroy($id);
        // BlogPost::destroy([$id1, $id2, $id3]);

        $request->session()->flash('status', 'Blog post was deleted!');
        return redirect()->route('posts.index');
    }
}
