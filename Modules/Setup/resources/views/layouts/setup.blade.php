<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<style>
   *, body {
        background-color: #272b33 !important;
        color: #cccccc !important;
    }
</style>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Daria') }} - {{ $title }}</title>

    <link href="{{ Module::asset('setup:css/app.css') }}"  rel="stylesheet">

    @include('setup::partials.favicon')
</head>

<body class="setup">
    <div id="app">

        <main class="main container">
            <div class="mb-5 h1 text-center text-primary">
                <img src="{{ Module::asset('setup:images/logo.png') }}" alt="installer.png">
            </div>

            @yield('content')
        </main>

        {{-- <script src="{{ Module::asset('setup:js/dependencies.js') }}"></script>
        <script src="{{ Module::asset('setup:js/app.js') }}"></script> --}}
        @stack('scripts')

    </div>
</body>

</html>
