@extends('auth/layouts.app')

@section('content')
<a class="visually-hidden-focusable skip-link" href="#auth-form">Skip to recovery form</a>

    <main id="auth-form" class="login-wrap">
        <div class="login-content">
            <h1 class="auth-title">Reset your password</h1>
            <p class="auth-subtitle">Enter your email and we'll send you a link to reset it.</p>
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
            <form class="login-form" action="{{ route('password.email') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" class="au-input @error('email') is-invalid @enderror" value="{{ old('email') }}" type="email" name="email" placeholder="you@example.com" autocomplete="email" autofocus required>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <button class="au-btn au-btn--green" type="submit">Send reset link</button>
            </form>

            <div class="register-link">
                <p>Remembered it? <a href="{{ route('login') }}">Back to sign in</a></p>
            </div>
        </div>
    </main>   
@endsection




    

