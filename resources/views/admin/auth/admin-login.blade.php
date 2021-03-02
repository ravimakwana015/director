@extends('admin.layouts.app-login')
@section('content')
<div class="login-box">
	<div class="login-logo">
		<a href="#"><b>ProducersEye</b></a>
	</div>	
	<div class="login-box-body">
		<p class="login-box-msg">Sign in to start your session</p>

		@include("admin.include.message")
		
		<form name="loginform" id="loginform" method="POST" action="{{ route('admin.login') }}">
			{{ csrf_field() }}
			<div class="form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
				<input type="email" class="form-control" id="email" name="email" ame="email" value="{{ old('email') }}" placeholder="john@gmail.com">
				<span class=""><img src="{{ asset('public/img/mail.svg')  }}" class="user-image" alt="mail"></span>
				@if ($errors->has('email'))
				<span class="help-block"><strong>{{ $errors->first('email') }}</strong></span>
				@endif
			</div>
			<div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
				<input id="password" type="password" name="password" class="form-control" placeholder="Password">
				<span class=""><img src="{{ asset('public/img/lock.svg')  }}" class="user-image" alt="lock"></span>
				@if ($errors->has('password'))
				<span class="help-block"><strong>{{ $errors->first('password') }}</strong></span>
				@endif
			</div>
			<div class="row">
				<div class="col-xs-2"></div>
				<!-- /.col -->
				<div class="col-xs-8">
					<button type="submit" class="btn btn-danger btn-block">Sign In</button>
				</div>
				<div class="col-xs-2"></div>
				<!-- /.col -->
			</div>
		</form>
		{{-- <a href="#">I forgot my password</a><br> --}}
	</div>
	<!-- /.login-box-body -->
</div>
@endsection
