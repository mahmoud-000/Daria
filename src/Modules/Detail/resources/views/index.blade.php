@extends('detail::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('detail.name') !!}</p>
@endsection
