@extends('layout')

@section('content')
    <form action="{{ route('register') }}" method="post">
        @csrf
        <div class="form-group">
            <label>Name</label>
            <input name="name" value="{{ old('name') }}" required class="form-control">
        </div>
        <div class="form-group">
            <label>E-mail</label>
            <input name="email" value="{{ old('email') }}" required class="form-control">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input name="password" value="" required class="form-control">
        </div>
        <div class="form-group">
            <label>Retype Password</label>
            <input name="password_confirmation" value="" required class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register!</button>
    </form>
@endsection
