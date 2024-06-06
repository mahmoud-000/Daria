@extends('setup::layouts.setup')

@section('content')

    <div class="row justify-content-center">
        <div class="col-12 col-md-8">

            <div class="card">
                <div class="card-header">
                    @lang('setup::setup.account_setup')
                </div>
                <div class="card-body">

                    <p>@lang('setup::setup.account_setup.intro')</p>

                    @include('setup::partials.alerts')

                    <form action="{{ route('setup.save-account') }}" method="post">
                        @csrf

                        <div class="mb-3">
                            <label for="username">
                                @lang('setup::setup.account_setup.username')
                            </label>
                            <input type="text" name="username" id="username"
                                class="form-control {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                placeholder="@lang('setup::setup.placeholder.username')" aria-label="@lang('setup::linkace.username')"
                                value="{{ old('username') }}" required autofocus>

                            @if ($errors->has('username'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('username') }}
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="email">
                                @lang('setup::setup.account_setup.email')
                            </label>
                            <input type="email" name="email" id="email"
                                class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                placeholder="@lang('setup::setup.placeholder.email')" aria-label="@lang('setup::linkace.email')"
                                value="{{ old('email') }}" required autofocus>

                            @if ($errors->has('email'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password">
                                @lang('setup::setup.account_setup.password')
                            </label>
                            <input type="password" name="password" id="password"
                                class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                value="{{ old('password') }}" aria-label="@lang('setup::setup.password')">
                            @if ($errors->has('password'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </p>
                            @else
                                <p class="form-text text-muted small">
                                    @lang('setup::setup.account_setup.password_requirements')
                                </p>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation">
                                @lang('setup::setup.account_setup.password_confirmed')
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                value="{{ old('password_confirmation') }}" aria-label="@lang('setup::setup.password_confirmed')">
                            @if ($errors->has('password_confirmation'))
                                <p class="invalid-feedback" role="alert">
                                    {{ $errors->first('password_confirmation') }}
                                </p>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">@lang('setup::setup.account_setup.create')</button>

                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
