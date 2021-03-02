@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello {{ $user['first_name'] }} {{ $user['last_name'] }}, Admin add you in system.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Login With {{ $user['email'] }} and Password is  {{ $password }}.
		
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection