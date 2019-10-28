<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<p>Hi {{ $comment->commentable->user->name }}</p>

<p>Someone has commented on your blog post
    <a href="{{ route('posts.show', ['post' => $comment->commentable->id]) }}">
        {{ $comment->commentable->title }}
    </a>
</p>

<hr>

<p>
     {{-- Swift_IoException Unable to open file for reading   --}}
    {{-- <img src="{{ $message->embed($comment->user->image->url()) }}"> --}}
    <img src="{{ $comment->user->image->url() }}">
    <a href="{{ route('users.show', ['user' => $comment->user->id]) }}">
        {{ $comment->user->name }}
    </a> said:
</p>

<p>"{{ $comment->content }}"</p>
