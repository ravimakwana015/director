<div class="signup-page user-login">
	<div class="left-content">
		<div class="login-text-wrap">
			<h2>Welcome Back!</h2>
			<p>To keep connected with us please login with your personal info</p>
			<div class="slide-btn-wrap">
				<a href="javascript:void(0);">Sign In</a>
			</div>
		</div>
	</div>
	<div class="right-content">
		<div class="account-content-wrap">
			<h2>Create Account</h2>
			{{-- <ul class="social-media">
				<li><a href="{{ route('instagram') }}"><i class="fab fa-instagram"></i></a></li>
			</ul> --}}
			<span class="or-text">or use your email for registration:</span>
			<div class="register-form-errors"></div>
			<form method="POST" id="register-form">
				@csrf
				{{-- @if(!empty(request()->segment(2)))
				<input type="hidden" name="token_data" value="{{ request()->segment(2) }}">
				@endif --}}
				<div class="form-item">
					{{ Form::text('first_name', null, ['placeholder' => 'First Name*']) }}
					<span class="icon"><i class="far fa-user"></i></span>
					@if ($errors->has('first_name'))
					<span class="text-danger">{{ $errors->first('first_name') }}</span>
					@endif
				</div>
				<div class="form-item">
					{{ Form::text('last_name', null, ['placeholder' => 'Last Name*']) }}
					<span class="icon"><i class="far fa-user"></i></span>
					@if ($errors->has('last_name'))
					<span class="text-danger">{{ $errors->first('last_name') }}</span>
					@endif
				</div>
				<div class="form-item">
					{{ Form::text('username', null, ['placeholder' => 'Username*']) }}
					<span class="icon"><i class="far fa-user"></i></span>
					@if ($errors->has('username'))
					<span class="text-danger">{{ $errors->first('username') }}</span>
					@endif
				</div>
				<div class="form-item">
					{{ Form::text('email', null, ['placeholder' => 'Email*']) }}
					<span class="icon"><i class="far fa-envelope"></i></span>
					@if ($errors->has('email'))
					<span class="text-danger">{{ $errors->first('email') }}</span>
					@endif
				</div>
				<div class="form-item">
					{{ Form::password('password', ['placeholder' => 'Password*']) }}
					<span class="icon"><i class="fas fa-lock"></i></span>
					@if ($errors->has('password'))
					<span class="text-danger">{{ $errors->first('password') }}</span>
					@endif
				</div>
				{{-- <input type="hidden" name="token_data" value="{{ request()->segment(2) }}"> --}}
				<div class="form-item">
					{{ Form::text('token_data', null, ['placeholder' => 'Access Code*']) }}
					<span class="icon"><i class="fas fa-share-alt"></i></span>
					@if ($errors->has('token_data'))
					<span class="text-danger">{{ $errors->first('token_data') }}</span>
					@endif
				</div>
				<div class="form-item">
					{{ Form::select('user_type', [""=>"Select User Type*","1"=>"Actor","2"=>"Model","3"=>"Musician","4"=>"Creator"],null) }}
					<span class="icon"><i class="fas fa-user"></i></span>
					@if ($errors->has('user_type'))
					<span class="text-danger">{{ $errors->first('user_type') }}</span>
					@endif
				</div>
				<div class="form-item" id="crew_type" style="display: none;">
					{{ Form::select('crew_type', [""=>"Select Sector*","Artist"=>"Artist","Choreographer"=>"Choreographer", "Cinematographer"=>"Cinematographer", "Composer"=>"Composer", "Director"=>"Director","Editor"=>"Editor", "Make Up Artist"=>"Make Up Artist","Photographer"=>"Photographer", "Sound, Light, Effects, Design"=>"Sound, Light, Effects, Design","Writer"=>"Writer"],null) }}
					<span class="icon"><i class="fas fa-user"></i></span>
					@if ($errors->has('crew_type'))
					<span class="text-danger">{{ $errors->first('crew_type') }}</span>
					@endif
				</div>
				<div class="form-item checkboxs">
					<label>
						{{Form::hidden('user_agreement',0)}}
						{{Form::checkbox('user_agreement')}}
						<a href="{{ route('page','user-agreement') }}" target="_blank">I have read and agree to the User Agreement*</a>
					</label>
				</div>
				<div class="form-item checkboxs">
					<label>
						{{Form::hidden('privacy_policy',0)}}
						{{Form::checkbox('privacy_policy')}}
						<a href="{{ route('page','privacy-policy') }}" target="_blank">I have read and agree to the Privacy Policy*</a>
					</label>
				</div>
				<div class="form-action">
					<!-- <button class="btn">Sign Up</button> -->
					{{-- <a href="discount.html" class="btn">Sign Up</a> --}}
					<button type="button" class="btn" id="register-btn">
						{{ __('Sign Up') }}
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
