@extends('auth/layouts.app')

@section('content')
<a class="visually-hidden-focusable skip-link" href="#auth-form">Skip to sign-up form</a>

    <main id="auth-form" class="login-wrap">
        <div class="login-content">
            <h1 class="auth-title">Create your account</h1>
            <form class="login-form" action="{{ route('register') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="name">Name</label>
                    <input id="name" class="au-input @error('mame') is-invalid @enderror" type="text" name="name"
                        placeholder="janedoe" {{old('name')}} autocomplete="name" autofocus required>

                   @error('name')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                     </span>
                   @enderror 
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input id="email" class="au-input @error('email') is-invalid @enderror" type="email" name="email"
                        placeholder="you@example.com" {{old('email')}} autocomplete="email" autofocus required>
                    @error('email')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                     </span>
                   @enderror     
                </div>
    
                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" class="au-input @error('password') is-invalid @enderror" type="password" name="password"
                        placeholder="At least 8 characters" autocomplete="new-password" autofocus minlength="8" required>
                    @error('password')
                     <span class="invalid-feedback" role="alert">
                        <strong>{{$message}}</strong>
                     </span>
                   @enderror     
                </div>

                <div class="form-group">
                    <label for="password-confirm">Confirm Password</label>
                    <input id="password-confirm" class="au-input" type="password" name="password_confirmation"
                        placeholder="Confirm password should match" autocomplete="new-password" autofocus required>
                        
                </div>

                <button class="au-btn au-btn--green" type="submit">Create account</button>
            </form>
            @if (Route::has('login'))
                <div class="register-link">
                    <p>Already have an account? <a href="{{route('login')}}">Sign in</a></p>
                </div>
            @endif
        </div>
    </main>
@endsection

