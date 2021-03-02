@extends('layouts.app')
@section('title','Refer Code')
@section('content')
@include('include.header')

<div class="main-content contact-page main">
	<div class="contact-us">
		<div class="right-content refer-page">
			<div class="contact-form-wrap">
				<h2>Refer Your Friend</h2>
				@if ($message = Session::get('success'))
				<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
				@endif
				<form method="post" action="{{ route('refer.message') }}">
					@csrf
					<div class="form-item">
						<input type="text" name="name" placeholder="Enter Your Friend's Name" value="{{ old('name') }}">
						<span class="icon"><i class="far fa-user"></i></span>
						@if ($errors->has('name'))
						<span class="text-danger">{{ $errors->first('name') }}</span>
						@endif
					</div>
					<div class="form-item">
						<input type="email" name="email" placeholder="Enter Your Friend's Email" value="{{ old('email') }}">
						<span class="icon"><i class="far fa-envelope"></i></span>
						@if ($errors->has('email'))
						<span class="text-danger">{{ $errors->first('email') }}</span>
						@endif
					</div>
					<div class="form-item">
						<input type="text" name="refer_code" placeholder="Enter Your Access Code" value="{{ AUTH::user()->refer_code }}">
						<span class="icon"><i class="fas fa-share-alt"></i></span>
						@if ($errors->has('refer_code'))
						<span class="text-danger">{{ $errors->first('refer_code') }}</span>
						@endif
					</div>
					<div class="form-item">
						<textarea placeholder="Enter Message here..." name="message" ></textarea>
						@if ($errors->has('message'))
						<span class="text-danger">{{ $errors->first('message') }}</span>
						@endif
					</div>
					<div class="form-action">
						<button class="btn">Send Message</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@include('include.footer')
@endsection
