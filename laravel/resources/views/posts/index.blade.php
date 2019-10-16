@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $post)
                <p>
                    <h3>
                        {{-- trashed postsはdel tagで囲む start --}}
                        @if($post->trashed())
                            <del>
                        @endif
                        {{-- 名前付きルートposts.showに渡すpostパラメータを指定 --}}
                        <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                        {{-- trashed postsはdel tagで囲む end --}}
                        @if($post->trashed())
                            </del>
                        @endif
                    </h3>

                    @updated(['date' => $post->created_at, 'name' => $post->user->name])
                    @endupdated

                    @tags(['tags' => $post->tags])
                    @endtags

                    @if($post->comments_count)
                        <p>{{ $post->comments_count }} comments</p>
                    @else
                        <p>No comments yet!</p>
                    @endif

                    {{-- userがadminかつabilityがupdate, deleteのみgate checkがtrueとなるので、そもそもauth userでなければgate checkの必要はないのでauth directiveを追加して処理を最適化 --}}
                    @auth
                        @can('update', $post)
                            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
                        @endcan
                    @endauth

                    {{-- @cannot('delete', $post)
                        <p>You can't delete this post.</p>
                    @endcannot --}}
                    @auth
                        {{-- delete buttonをtrashされていない状態でのみ表示 --}}
                        @if(!$post->trashed())
                            @can('delete', $post)
                                <form method="post" class="fm-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="Delete!" class="btn btn-primary">
                                </form>
                            @endcan
                        @endif
                    @endauth
                </p>
            @empty
                <p>No blog posts yet!</p>
            @endforelse
        </div>
        <div class="col-4">
            <div class="container">
                <div class="row">
                    {{-- slotとして意味の成さない変数はcomponentの第二引数として、もしくはcomponent aliasの第一引数としてslotに値を渡す --}}
                    {{-- 渡す値がhtml等の場合、配列で渡すと見にくいのでslotとして渡すのがベター --}}
                    @card(['title' => 'Most Commented',])
                        @slot('subtitle')
                            What people are currently talking about
                        @endslot
                        @slot('items')
                        {{-- items slotとしてhtmlが渡されることになるのでそのまま表示される --}}
                            @foreach($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                                </li>
                            @endforeach
                        @endslot
                    @endcard
                </div>
                <div class="row mt-4">
                    @card(['title' => 'Most Active',])
                        @slot('subtitle')
                            Writers with most posts written
                        @endslot
                        @slot('items', collect($mostActive)->pluck('name'))
                    @endcard
                </div>
                <div class="row mt-4">
                    @card(['title' => 'Most Active Last Month',])
                        @slot('subtitle')
                            Users with most posts written in the month
                        @endslot
                        @slot('items', $mostActiveLastMonth->pluck('name'))
                    @endcard
                </div>
            </div>
        </div>
    </div>
@endsection
