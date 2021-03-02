@extends('layouts.app')

@section('content')

<div class="main-content contact-page forgot-pass-page">
    <div class="logo">
        <a href="{{ route('home') }}">PRODUCERS EYE</a>
    </div>
    <div class="contact-us">
        <div class="left-content">
            <div class="contact-text-wrap">
                <div class="contact-info">
                    <h2>{{ __('Reset Password') }} ??</h2>
                    {{-- <p>Don't worry, just fill up the form and we will get back to you</p> --}}
                </div>
            </div>
        </div>
        <div class="right-content">
            <div class="contact-form-wrap">
                <h2>{{ __('Reset Password') }}</h2>
                {{-- <p>we will send you a reset link to your email</p> --}}
                @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
                @endif 
                @if (session('error'))
                <div class="alert alert-danger" role="alert">
                    {{ session('error') }}
                </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-item">
                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                        <span class="icon"><i class="far fa-envelope"></i></span>

                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-item">
                        <input id="password" type="password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-item">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <span class="icon"><i class="fas fa-lock"></i></span>
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="form-action">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
