<div class="signin-page user-login show">
	<div class="left-content">
		<div class="login-text-wrap">
			<h2>Hello, Friend!</h2>
			<p>Enter your personal details and start your journey with us</p>
			<div class="slide-btn-wrap">
				<a href="javascript:void(0);">Sign UP</a>
			</div>
		</div>
	</div>
	<div class="right-content">

		<div class="account-content-wrap">
			<h2>Sign in to ProducersEye</h2>
			{{-- <ul class="social-media">
				<li><a href="{{ route('instagram') }}"><i class="fab fa-instagram"></i></a></li>
			</ul> --}}
			<span class="or-text">or use your email for registration:</span>
			@include("admin.include.message")
			<div class="form-errors"></div>
			<form method="POST" id="login-form">
				@csrf
				<div class="form-item">
					<input id="email" type="text" placeholder="Username or Email"
					class="form-control{{ $errors->has('username') || $errors->has('email') ? ' is-invalid' : '' }}"
					name="login" value="{{ old('username') ?: old('email') }}" required autofocus>
					<span class="icon"><i class="far fa-envelope"></i></span>

					@if ($errors->has('username') || $errors->has('email'))
					<span class="invalid-feedback">
						<strong>{{ $errors->first('username') ?: $errors->first('email') }}</strong>
					</span>
					@endif
					{{-- <input id="email" type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
					<span class="icon"><i class="far fa-envelope"></i></span>

					@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror --}}
				</div>
				<div class="form-item">
					<input id="password" type="password" placeholder="Password" class="@error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
					<span class="icon"><i class="fas fa-lock"></i></span>
					@error('password')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
					@enderror
				</div>
				<div class="forget-link">
					@if (Route::has('password.request'))
					<a href="{{ route('password.request') }}">
						{{ __('Forgot Your Password?') }}
					</a>
					@endif
				</div>
				<div class="form-action">
					<button class="btn" type="button" id="login-btn">Sign In</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	$('#login-form').keypress(function (e) {
		if(e.keyCode=='13')
		{
			$('#login-btn').click();
		}
	});
</script>
