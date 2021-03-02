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
					<h2>{{ __('Forgot Password') }} ??</h2>
					<p>Don't worry, just fill up the form and we will get back to you</p>
				</div>
			</div>
		</div>
		<div class="right-content">
			<div class="contact-form-wrap">
				<h2>Enter Your Email</h2>
				<p>we will send you a reset link to your email</p>
				@if (session('status'))
				<div class="alert alert-success" role="alert">
					{{ session('status') }}
				</div>
				@endif
				<form method="POST" action="{{ route('password.email') }}">
					@csrf
					<div class="form-item">
						<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
						<span class="icon"><i class="far fa-envelope"></i></span>

						@error('email')
						<span class="invalid-feedback" role="alert">
							<strong>{{ $message }}</strong>
						</span>
						@enderror
					</div>
					<div class="form-action">
						<button type="submit" class="btn btn-primary">
							{{ __('Send Password Reset Link') }}
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
