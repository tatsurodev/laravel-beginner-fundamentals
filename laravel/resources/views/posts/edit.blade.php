@extends('layout')

@section('content')
    <form action="{{ route('posts.update', ['post' => $post->id]) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        @include('posts._form')
        <button type="submit" class="btn btn-primary btn-block">Update!</button>
    </form>
@endsection
