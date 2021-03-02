@extends('layouts.app')
@section('title','Refer Code')
@section('content')
@include('include.header')

<div class="main-content contact-page main">
	<div class="contact-us">
		<div class="right-content refer-page">
			<div class="contact-form-wrap">
				<h2>Change Username and Password</h2>
				@if ($message = Session::get('success'))
				<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
				@endif
                @if ($message = Session::get('error'))
				<div class="alert alert-danger alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
				@endif
				<form id="change-form" method="post" action="{{ route('changepassword.update') }}">
					@csrf
					<input type="hidden" name="id" value="{{ Auth::user()->id }}">
					<div class="form-item">
						<input type="text" name="username" placeholder="Enter Your User Name" value="{{ old('username') }}">
						<span class="icon"><i class="far fa-user"></i></span>
						@if ($errors->has('username'))
						<span class="text-danger">{{ $errors->first('username') }}</span>
						@endif
					</div>
                    <div class="form-item">
						<input type="password" name="currentpassword" placeholder="Enter your Current Password">
						<span class="icon"><i class="far fa-user"></i></span>
						@if ($errors->has('currentpassword'))
						<span class="text-danger">{{ $errors->first('currentpassword') }}</span>
						@endif
					</div>
					<div class="form-item">
						<input type="password" name="newpassword" placeholder="Enter Your New Password">
						<span class="icon"><i class="fas fa-lock"></i></span>
						@if ($errors->has('newpassword'))
						<span class="text-danger">{{ $errors->first('newpassword') }}</span>
						@endif
					</div>
					<div class="form-item">
						<input type="password" name="confirmpassword" placeholder="Confirm Your New Password" >
						<span class="icon"><i class="fas fa-lock"></i></span>
						@if ($errors->has('confirmpassword'))
						<span class="text-danger">{{ $errors->first('confirmpassword') }}</span>
						@endif
					</div>
					<div class="form-action">
						<button class="btn">Update</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@include('include.footer')
@endsection
