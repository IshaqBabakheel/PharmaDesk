@extends('auth/layouts.app')

@section('content')
<a class="visually-hidden-focusable skip-link" href="#auth-form">Skip to sign-inform</a>
    <main class="login-wrap" id="auth-form">
        <div class="login-content"> 
            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to continue to your dashboard.</p>

            <form class="login-form" action="{{ route('login') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" class="au-input @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" type="email" name="email" placeholder="you@example.com"
                        autocomplete="email" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" class="au-input @error('password') is-invalid @enderror" type="password"
                        name="password" placeholder="••••••••" autocomplete="current-password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                <div class="login-checkbox">
                    <label>
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">Forgot password?</a>
                    @endif
                </div>
                <button class="au-btn au-btn--green" type="submit">Sign in</button>
            </form>
            <div class="register-link">
                <p>Don't have an account? <a href="{{ 'register' }}">Create one</a></p>
            </div>

        </div>
    </main>
@endsection


