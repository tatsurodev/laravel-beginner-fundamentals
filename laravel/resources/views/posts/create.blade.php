@extends('layout')

@section('content')
    <form action="{{ route('posts.store') }}" method="post">
        @csrf
       <p>
            <label>Title</label>
            <input type="text" name="title">
       </p>
       <p>
            <label>Content</label>
            <input type="text" name="content">
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
       <button type="submit">Create!</button>
    </form>
@endsection
