<div class="mb-2 mt-2">
    @auth
        <form action="#" method="post">
            @csrf

            <div class="form-group">
                <textarea type="text" name="content" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Add comment!</button>
        </form>
    @else
        <a href="{{ route('login') }}">Sign-in</a> to post comments!
    @endauth
</div>
<hr>
