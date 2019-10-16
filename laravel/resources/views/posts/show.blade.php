@extends('layout')

@section('content')
    <h1>
        {{ $post->title }}
        {{-- slotへdataの配列を渡す --}}
        {{-- @badge(['type' => 'primary']) --}}
        {{-- type slotを渡していないのでdefault値が使用される --}}
        {{-- 現在時刻をnew Carbon\Carbon()で取得し、diffInMinutesメソッドの引数との差分が5以下ならNew!と表示 --}}
        @badge(['show' => now()->diffInMinutes($post->created_at) < 5])
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
    </h1>
    <p>{{ $post->content }}</p>
    {{-- diffForHumansで人にわかりやすい形式で表示 --}}
    @updated(['date' => $post->created_at, 'name' => $post->user->name])
    @endupdated
    @updated(['date' => $post->created_at,])
        Updated
    @endupdated

    <p>Currently read by {{ $counter }} people</p>

    <h4>Comments</h4>
    @forelse ($post->comments as $comment)
        <p>{{ $comment->content }}</p>
        @updated(['date' => $comment->created_at])
        @endupdated
    @empty
        <p>No comments yet!</p>
    @endforelse
@endsection
