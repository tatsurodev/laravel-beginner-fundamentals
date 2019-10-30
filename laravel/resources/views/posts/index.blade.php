@extends('layout')

@section('content')
    <div class="row">
        <div class="col-8">
            @forelse ($posts as $post)
                <p>
                    <h3>
                        {{-- trashed postsはdel tagで囲む start --}}
                        @if($post->trashed())
                            <del>
                        @endif
                        {{-- 名前付きルートposts.showに渡すpostパラメータを指定 --}}
                        <a class="{{ $post->trashed() ? 'text-muted' : '' }}" href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                        {{-- trashed postsはdel tagで囲む end --}}
                        @if($post->trashed())
                            </del>
                        @endif
                    </h3>

                    @updated(['date' => $post->created_at, 'name' => $post->user->name, 'userId' => $post->user->id])
                    @endupdated

                    @tags(['tags' => $post->tags])
                    @endtags

                    {{-- @if($post->comments_count)
                        <p>{{ $post->comments_count }} comments</p>
                    @else
                        <p>No comments yet!</p>
                    @endif --}}

                    {{ trans_choice('messages.comments', $post->comments_count) }}

                    {{-- userがadminかつabilityがupdate, deleteのみgate checkがtrueとなるので、そもそもauth userでなければgate checkの必要はないのでauth directiveを追加して処理を最適化 --}}
                    @auth
                        @can('update', $post)
                            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">{{ __('Edit') }}</a>
                        @endcan
                    @endauth

                    {{-- @cannot('delete', $post)
                        <p>You can't delete this post.</p>
                    @endcannot --}}
                    @auth
                        {{-- delete buttonをtrashされていない状態でのみ表示 --}}
                        @if(!$post->trashed())
                            @can('delete', $post)
                                <form method="post" class="fm-inline" action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" value="{{ __('Delete!') }}" class="btn btn-primary">
                                </form>
                            @endcan
                        @endif
                    @endauth
                </p>
            @empty
                <p>{{ __('No blog posts yet!') }}</p>
            @endforelse
        </div>
        <div class="col-4">
            @include('posts._activity')
        </div>
    </div>
@endsection
