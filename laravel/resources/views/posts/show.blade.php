@extends('layout')

@section('content')
<div class="row">
    <div class="col-8">
        @if($post->image)
            <div style="background-image: url('{{ $post->image->url() }}'); min-height: 500px; color: white; text-align:center; background-attachment: fixed;">
                <h1 style="padding-top: 100px; text-shadow: 1px 2px #000">
        @else
            <h1>
        @endif
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
        @if($post->image)
                </h1>
            </div>
        @else
            </h1>
        @endif

        <p>{{ $post->content }}</p>

        {{--
        <img src="http://laravel.test/storage/{{ $post->image->path }}">
        <img src="{{ asset($post->image->path) }}"> <!-- js, css等でよく使用するapp_root/publicへのリンク -->
        <!-- 上記の２つはdisk('public')等保存場所を変更した場合影響を受けるが、下２つの方法は.envのFILESYSTEM_DRIVERの変更、もしくはStorage::disk('変更場所')->url()で変更できるため変更に強い -->
        <img src="{{ Storage::url($post->image->path) }}">
        <img src="{{ $post->image->url() }}">
        --}}

        {{-- diffForHumansで人にわかりやすい形式で表示 --}}
        @updated(['date' => $post->created_at, 'name' => $post->user->name, 'userId' => $post->user->id])
        @endupdated
        @updated(['date' => $post->created_at,])
            Updated
        @endupdated

        @tags(['tags' => $post->tags])
        @endtags

        <p>Currently read by {{ $counter }} people</p>

        <h4>Comments</h4>

        @include('comments._form')

        @forelse ($post->comments as $comment)
            <p>{{ $comment->content }}</p>
            @updated(['date' => $comment->created_at, 'name' => $comment->user->name])
            @endupdated
        @empty
            <p>No comments yet!</p>
        @endforelse
    </div>
    <div class="col-4">
        @include('posts._activity')
    </div>
</div>

@endsection
