@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>
    {{-- diffForHumansで人にわかりやすい形式で表示 --}}
    <p>Added {{ $post->created_at->diffForHumans() }}</p>

    {{-- 現在時刻をnew Carbon\Carbon()で取得し、diffInMinutesメソッドの引数との差分が5以下ならNew!と表示 --}}
    @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 995)
        {{-- slotへdataの配列を渡す --}}
        {{-- @badge(['type' => 'primary']) --}}
        {{-- type slotを渡していないのでdefault値が使用される --}}
        @badge
            Brand new Post!
        @endbadge
        {{-- slot directiveを使ってslot変数を渡す --}}
        <!--
        @component('components.badge')
            Brand new Post!
            @slot('type')
                primary
            @endslot
        @endcomponent
        -->
    @endif

    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        <p class="text-muted">added {{ $comment->created_at->diffForHumans() }}</p>
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection
