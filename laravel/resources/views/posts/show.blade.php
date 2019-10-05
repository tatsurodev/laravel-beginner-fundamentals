@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>
    {{-- diffForHumansで人にわかりやすい形式で表示 --}}
    <p>Added {{ $post->created_at->diffForHumans() }}</p>

    {{-- 現在時刻をnew Carbon\Carbon()で取得し、diffInMinutesメソッドの引数との差分が5以下ならNew!と表示 --}}
    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5)
        <strong>New!</strong>
    @endif
@endsection
