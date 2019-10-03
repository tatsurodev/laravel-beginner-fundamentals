@extends('layout')

@section('content')
{{-- {{ }}で出力されるデータは自動的にescapesされるので、escapeしたくない場合は、{!! !!}を使用する --}}
{!! $welcome !!}{{ $data['title'] }}
@endsection
