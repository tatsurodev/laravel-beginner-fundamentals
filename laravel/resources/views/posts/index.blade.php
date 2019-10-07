@extends('layout')

@section('content')
    @forelse ($posts as $post)
        <p>
            <h3>
                {{-- 名前付きルートposts.showに渡すpostパラメータを指定 --}}
                <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
            </h3>
            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>

            <form method="post" class="fm-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                @csrf
                @method('delete')
                <input type="submit" value="Delete!" class="btn btn-primary">
            </form>

        </p>
    @empty
        <p>No blog posts yet!</p>
    @endforelse
@endsection
