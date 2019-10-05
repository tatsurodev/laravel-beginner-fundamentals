<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
        {{-- viewからrouteにパラメータを渡す --}}
        {{-- <li><a href="{{ route('blog-post', ['id' => 1]) }}">Blog Post 1</a></li> --}}
        <li><a href="{{ route('posts.index') }}">Blog Posts</a></li>
        <li><a href="{{ route('posts.create') }}">Add Blog Posts</a></li>
    </ul>
    @if(session()->has('status'))
    <p style="color: green">{{ session()->get('status') }}</p>
    @endif
    @yield('content')
</body>

</html>
