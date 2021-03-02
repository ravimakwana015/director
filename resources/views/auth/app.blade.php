<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<title>{{ config('app.name', 'Producers Eye') }}</title>
	<meta charset="utf-8">
	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
	<!-- Font-Awesome -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
	<link href="{{ asset('public/front/css-thirdparty/bootstrap.min.css?v=9.1.0') }}" rel="stylesheet">
	<link href="{{ asset('public/front/css-thirdparty/owl.carousel.css?v=9.1.0') }}" rel="stylesheet">
	<!-- Main CSS -->
	<link rel="stylesheet" href="{{ asset('public/front/css/main.css?v=9.1.0') }}">
	<link rel="stylesheet" href="{{ asset('public/front/css/custom.css?v=9.1.0') }}">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="{{ asset('public/front/js/thirdparty/jquery-3.2.1.min.js?v=9.1.0') }}"></script>
	<script src="{{ asset('public/front/js/thirdparty/isotop.min.js?v=9.1.0') }}"></script>
	<script type="text/javascript" src="{{ asset('public/front/js/thirdparty/bootstrap.min.js?v=9.1.0') }}"></script>
	<script type="text/javascript" src="{{ asset('public/front/js/thirdparty/owl.carousel.min.js?v=9.1.0') }}"></script>
	<!-- App JS -->
	<script type="text/javascript" src="{{ asset('public/front/js/app.min.js?v=9.1.0') }}"></script>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			$('#login-btn').click(function(event) {
				$('#loading').show();
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: '{{ route('login') }}',
					type: 'POST',
					dataType: 'json',
					data: $('#login-form').serialize(),
				})
				.done(function(res) {
					$('#loading').hide();
					if(res.status==false){
						$('.form-errors').html('');
						$( '.form-errors' ).html('<div class="alert alert-danger">'+res.msg+'</div>');
						setTimeout(function(){
							$('.form-errors').html('');
						}, 3000);
					}else{
						$( '.form-errors' ).html('');
						$('.form-errors').html('<div class="alert alert-success">'+res.msg+'</div>');
						setTimeout(function(){
							window.location.href='{{ route('dashboard') }}';
						}, 700);
					}
				});
			});
			$('#register-btn').click(function(event) {
				$('#loading').show();
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					url: '{{ route('user-register') }}',
					type: 'POST',
					dataType: 'json',
					data: $('#register-form').serialize(),
				})
				.done(function(res) {
					$('#loading').hide();
					if(res.status==false){
						var errorString = '<ul>';
						$.each(res.msg, function( key, value) {
							errorString += '<li>' + value + '</li>';
						});
						errorString += '</ul>';
						$('.register-form-errors').html('');
						$( '.register-form-errors' ).html('<div class="alert alert-danger">'+errorString+'</div>');
						// setTimeout(function(){
						// 	$('.form-errors').html('');
						// }, 4000);
					}else{
						$( '.register-form-errors' ).html('');
						$('.register-form-errors').html('<div class="alert alert-success">'+res.msg+'</div>');
						setTimeout(function(){
							window.location.href='{{ route('thankyou') }}';
							// window.location.href='{{ route('plans') }}';
						}, 1500);
					}
				});
			});

			$('select[name="user_type"]').change(function(event) {
				if(this.value=='4'){
					$('#crew_type').show('slow/900/slow')
				}else{
					$('#crew_type').hide('slow/600/fast');
				}
			});
		});
	</script>

</head>
<body>
	<div id="loading" style="display: none">
		<img src="{{ URL::asset('public/front/images/loading-profile.gif') }}" style=" z-index: +1;" width="150" height="150" alt="loader" />
	</div>
	<div id="app">
		<div class="main-content user-login-page">
			<div class="logo black">
				<a href="{{ route('home') }}">PRODUCERS EYE</a>
			</div>

			@include('auth.include.register')
			@include('auth.include.login')

			<div class="green-bg slide">
			</div>
			<div class="slide-btn-wrap slide">
				<a href="javascript:void(0);">Sign Up</a>
			</div>
		</div>
	</div>
</body>
</html>
