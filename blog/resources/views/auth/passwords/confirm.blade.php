@extends('layouts.auth')

@section('content')
<div class="card-body">
    <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="password" type="password" placeholder="{{ __('Password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
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

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Confirm Password') }}
                </button>

                @if (Route::has('password.request'))
                    <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
            <!-- /.col -->
        </div>
    </form>

</div>
@endsection
