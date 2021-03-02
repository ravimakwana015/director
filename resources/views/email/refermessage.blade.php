@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, {!! $user['name'] !!}.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Your amazing colleague has given you the details below to help you get started on Producers Eye. 
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{!! $user['message'] !!}
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Your Access Code Is : {{ $user['refer_code'] }}
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Register Link : <a href="{{ route('login') }}" class="btn">Register</a>
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		We look forward to seeing you on our platform. If any queries at all, please do get in touch. 
	</p>

	<p style="line-height: 24px">Have a lovely day,</br>@yield('title', config('app.name')) Team.</p>
	@include('email.layouts.footer')
</div>
@endsection