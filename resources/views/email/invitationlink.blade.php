@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello , Admin Send You a Registration link.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		<a href="{{ route('user-register-from',[$user['code']]) }}" target="_blank">Register</a>
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection