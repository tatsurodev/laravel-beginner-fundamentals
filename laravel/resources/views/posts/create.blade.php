@extends('layout')

@section('content')
    <form action="{{ route('posts.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('posts._form')
       <button type="submit" class="btn btn-primary btn-block">Create!</button>
    </form>
@endsection
