@extends('layout')

@section('content')
{{-- __() helper method or @lang directive --}}
<h1>{{ __('messages.welcome') }}</h1>
<h1>@lang('messages.welcome')</h1>

{{-- 第二引数の配列で変数を渡す --}}
<p>{{ __('messages.example_with_value', ['name' => 'John']) }}</p>

{{-- trans_choice methodで数に応じて単語を変化させる --}}
<p>{{ trans_choice('messages.plural', 0, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 1, ['a' => 1]) }}</p>
<p>{{ trans_choice('messages.plural', 2, ['a' => 1]) }}</p>

<p>This is the ontent of the main page!</p>
@endsection
