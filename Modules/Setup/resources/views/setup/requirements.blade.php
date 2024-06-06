@extends('setup::layouts.setup')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup::setup.check_requirements')
                </div>
                <div class="card-body">
                    <ul>
                        @foreach ($results as $key => $successful)
                            <li>
                                @lang('setup::setup.requirements.' . $key)
                                @if ($successful)
                                    <x-setup::icon.check class="text-success" />
                                @else
                                    <x-setup::icon.ban class="text-danger" />
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('setup.database') }}"
                        class="btn {{ $success ? 'btn-primary' : ' btn-danger disabled' }}">
                        @lang('setup::setup.continue')
                    </a>

                </div>
            </div>

        </div>
    </div>
@endsection
