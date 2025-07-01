@extends('pipeline::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('pipeline.name') !!}</p>
@endsection
