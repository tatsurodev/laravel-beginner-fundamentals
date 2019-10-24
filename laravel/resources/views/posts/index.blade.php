@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $post)
                <p>
                    <h3>
                        {{-- 名前付きルートposts.showに渡すpostパラメータを指定 --}}
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                    </h3>

                    <p class="text-muted">
                        Added {{ $post->created_at->diffForHumans() }}
                        by {{ $post->user->name }}
                    </p>

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

                    @can('delete', $post)
                        <form method="post" class="fm-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                            @csrf
                            @method('delete')
                            <input type="submit" value="Delete!" class="btn btn-primary">
                        </form>
                    @endcan

                </p>
            @empty
                <p>No blog posts yet!</p>
            @endforelse
        </div>
        <div class="col-4">
            <div class="card" style="width: 18rem;">
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
    </div>
@endsection
