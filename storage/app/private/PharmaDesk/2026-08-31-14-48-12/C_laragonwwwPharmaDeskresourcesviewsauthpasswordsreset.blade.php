@extends('auth/layouts.app')

@section('content')
<a class="visually-hidden-focusable skip-link" href="#auth-form">Skip to recovery form</a>

    <main id="auth-form" class="login-wrap">
        <div class="login-content">
            <h1 class="auth-title">Reset your password</h1>
    
            <form class="login-form" action="{{ route('password.update') }}" method="post">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" class="au-input @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" type="email" name="email" autocomplete="email" autofocus required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" class="au-input @error('password') is-invalid @enderror" type="password" name="password" autocomplete="new-password" autofocus required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Confirm Password</label>
                    <input id="password-confirm" class="au-input @error('password-confirm') is-invalid @enderror" type="password" name="password_confirmation" autocomplete="new-password" autofocus required>
                    @error('password-confrim')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button class="au-btn au-btn--green" type="submit">Reset Password</button>
            </form>
        </div>
    </main>   
@endsection

