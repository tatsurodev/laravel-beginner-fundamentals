<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <title>Document</title>
</head>

<body>
<!--
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('contact') }}">Contact</a></li>
        {{-- viewからrouteにパラメータを渡す --}}
        {{-- <li><a href="{{ route('blog-post', ['id' => 1]) }}">Blog Post 1</a></li> --}}
        <li><a href="{{ route('posts.index') }}">Blog Posts</a></li>
        <li><a href="{{ route('posts.create') }}">Add Blog Posts</a></li>
    </ul>
-->

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
        <h5 class="my-0 mr-md-auto font-weight-normal">Laravel Blog</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="{{ route('home') }}">{{ __('Home') }}</a>
            <a class="p-2 text-dark" href="{{ route('contact') }}">{{ __('Contact') }}</a>
            <a class="p-2 text-dark" href="{{ route('posts.index') }}">{{ __('Blog Posts') }}</a>
            <a class="p-2 text-dark" href="{{ route('posts.create') }}">{{ __('Add') }}</a>

            @guest
                {{-- B TO B向けのサイト等だとサイト管理者がユーザー登録を行い、ユーザー自体が登録できないような仕様がある。このような時、Route::has methodでregister routeがある場合のみ、register用リンクが表示されるようにする --}}
                @if (Route::has('register'))
                    <a class="p-2 text-dark" href="{{ route('register') }}">{{ __('Register') }}</a>
                @endif
                <a class="p-2 text-dark" href="{{ route('login') }}">{{ __('Login') }}</a>
            @else
                <a class="p-2 text-dark" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">{{ __('Logout') }} ({{ Auth::user()->name }})</a>
                <form id="logout-form" action="{{ route('logout') }}" method="post" style="display: none;">
                    @csrf
                </form>
            @endguest

        </nav>
    </div>

    <div class="container">
        @if(session()->has('status'))
        <p style="color: green">{{ session()->get('status') }}</p>
        @endif
        @yield('content')
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
</body>

</html>
