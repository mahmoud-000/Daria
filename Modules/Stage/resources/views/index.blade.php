@extends('stage::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('stage.name') !!}</p>
@endsection
