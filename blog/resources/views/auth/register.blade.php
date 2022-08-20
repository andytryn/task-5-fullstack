@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">Register a new membership</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="name" type="text" placeholder="{{ __('Name') }}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>

            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="email" type="email" placeholder="{{ __('Email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="{{ __('Confirm Password') }}" name="password_confirmation" required autocomplete="new-password" />
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-8">
            </div>

            <!-- /.col -->
            <div class="col-4">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Register') }}
                </button>
            </div>
            <!-- /.col -->
        </div>
    </form>


    @if (Route::has('login'))
        <a href="{{ route('login') }}" class="text-center">
            {{ __('Login') }}
        </a>
    @endif
</div>
@endsection
