<div class="container">
    <div class="row">
        {{-- slotとして意味の成さない変数はcomponentの第二引数として、もしくはcomponent aliasの第一引数としてslotに値を渡す --}}
        {{-- 渡す値がhtml等の場合、配列で渡すと見にくいのでslotとして渡すのがベター --}}
        @card(['title' => 'Most Commented',])
            @slot('subtitle')
                What people are currently talking about
            @endslot
            @slot('items')
            {{-- items slotとしてhtmlが渡されることになるのでそのまま表示される --}}
                @foreach($mostCommented as $post)
                    <li class="list-group-item">
                        <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                    </li>
                @endforeach
            @endslot
        @endcard
    </div>
    <div class="row mt-4">
        @card(['title' => 'Most Active',])
            @slot('subtitle')
                Writers with most posts written
            @endslot
            @slot('items', collect($mostActive)->pluck('name'))
        @endcard
    </div>
    <div class="row mt-4">
        @card(['title' => 'Most Active Last Month',])
            @slot('subtitle')
                Users with most posts written in the month
            @endslot
            @slot('items', collect($mostActiveLastMonth)->pluck('name'))
        @endcard
    </div>
</div>
