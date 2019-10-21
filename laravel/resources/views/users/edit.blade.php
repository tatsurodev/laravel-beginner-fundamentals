@extends('layout')

@section('content')
    <form action="{{ route('users.update', ['user' => $user->id]) }}" method="post" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        @method('put')
        <div class="row">
            <div class="col-4">
                <img src="{{ $user->image ? $user->image->url() : '' }}" class="img-thumbnail avatar">
                <div class="card mt-4">
                    <div class="card-body">
                        <h6>Upload a different photo</h6>
                        <input type="file" name="avatar" class="form-control-file">
                    </div>
                </div>
            </div>
            <div class="col-8">
                <div class="form-group">
                    <label for="">Name: </label>
                    <input type="text" value="" name="name" class="form-control">
                </div>
                @errors
                @enderrors
                <div class="form-group">
                    <input type="submit" value="Save Changes" class="btn btn-primary">
                </div>
            </div>
        </div>
    </form>
@endsection
