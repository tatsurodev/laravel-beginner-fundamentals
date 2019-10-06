@extends('layout')

@section('content')
    <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="post">
        @csrf
        @method('put')
       <p>
            <label>Title</label>
            {{-- oldの第二引数でデフォルトの値を指定できる --}}
            <input type="text" name="title" value="{{ old('title', $post->title) }}">
       </p>
       <p>
            <label>Content</label>
            <input type="text" name="content" value="{{ old('content', $post->content) }}">
       </p>

       @if($errors->any())
        <div>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
       @endif
       <button type="submit">Update!</button>
    </form>
@endsection
