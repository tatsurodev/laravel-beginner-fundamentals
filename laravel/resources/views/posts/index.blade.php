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

                    @if($post->comments_count)
                        <p>{{ $post->comments_count }} comments</p>
                    @else
                        <p>No comments yet!</p>
                    @endif

                    @can('update', $post)
                        <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
                    @endcan

                    {{-- @cannot('delete', $post)
                        <p>You can't delete this post.</p>
                    @endcannot --}}

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

                </p>
            @empty
                <p>No blog posts yet!</p>
            @endforelse
        </div>
        <div class="col-4">
            <div class="container">
                <div class="row">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Commented</h5>
                            <h6 class="card-subtitle mb-2 text-muted">What people are currently talking about</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($mostCommented as $post)
                                <li class="list-group-item">
                                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Users with most posts written</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($mostActive as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="card" style="width: 100%;">
                        <div class="card-body">
                            <h5 class="card-title">Most Active Last Month</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Users with most posts written in the month</h6>
                        </div>
                        <ul class="list-group list-group-flush">
                            @foreach($mostActiveLastMonth as $user)
                                <li class="list-group-item">
                                    {{ $user->name }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
