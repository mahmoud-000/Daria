@extends('setup::layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup::setup.complete')
                </div>
                <div class="card-body">

                    <p>@lang('setup::setup.outro')</p>

                    <a href="{{ route('dashboard', ['any' => '/login']) }}" class="btn btn-primary">
                        @lang('setup::setup.go_to_login')
                    </a>
                </div>
            </div>

        </div>
    </div>

@endsection
