<?php

namespace App\Http\Controllers;

use App\User;
use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
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

        // remember(cache_name, minites, function(){})
        $mostCommented = Cache::remember('blog-post-commented', now()->addSeconds(60), function () {
            return BlogPost::mostCommented()->take(5)->get();
        });
        $mostActive = Cache::remember('users-most-active', now()->addSeconds(60), function () {
            return User::withMostBlogPosts()->take(5)->get();
        });
        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(60), function () {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });
        // comments数も一緒に渡す
        // withCount('relation')で、relation数をrelation_count fieldをモデルに追加できる
        // local scope latestを使用
        return view('posts.index', [
            'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
            'mostCommented' => $mostCommented,
            'mostActive' => $mostActive,
            'mostActiveLastMonth' => $mostActiveLastMonth,
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
        $blogPost = Cache::remember("blog-post-{$id}", 60, function () use ($id) {
            // findメソッドだと無効な$idを受け取った時にエラーとなるのでfindOrFailメソッドを使用する
            return BlogPost::with(
                // eager loadにlatest scope追加
                // ['comments' => function ($query) {
                //     return $query->latest();
                // }]
                // BlogPost modelのcomments relation取得時にlocal scopeのlatestを適用している
                'comments'
            )->findOrFail($id);
        });

        // session idを取得
        $sessionId = session()->getId();
        // 閲覧中user数を保存するキー
        $counterKey = "blog-post-{$id}-counter";
        // 閲覧中のusersを配列に保存するキー
        $usersKey = "blog-post-{$id}-users";
        // usersをキャッシュから復元、キーがなければ初アクセスなので空配列セット
        $users = Cache::get($usersKey, []);
        // アクセスしてきたuserを保存する配列で、一定時間(この場合1分)を超えていたら削除する
        $usersUpdate = [];
        // counterの増減
        $diffrence = 0;
        // 現時刻
        $now = now();

        // usersを一定時間超えていたらdiffrenceを-1、そうでなければcacheに保存する用の変数にsession idと現時間格納
        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $diffrence--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        // 初アクセス or アクセス済だが一定時間を超えていた場合、diffrenceを+1
        if (!array_key_exists($sessionId, $users) || $now->diffInMinutes($users[$sessionId]) >= 1) {
            $diffrence++;
        }

        // 現ユーザーのアクセス時間を更新
        $usersUpdate[$sessionId] = $now;

        // usersをcacheに保存
        Cache::forever($usersKey, $usersUpdate);

        // counterをcacheに保存
        if (!Cache::has($counterKey)) {
            Cache::forever($counterKey, 1);
        } else {
            Cache::increment($counterKey, $diffrence);
        }

        // 現counterの値を取得
        $counter = Cache::get($counterKey);

        return view('posts.show', ['post' => $blogPost, 'counter' => $counter,]);
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
