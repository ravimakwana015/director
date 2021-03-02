@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, Admin.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{{ $user['profile_name'] }} Send You A Explore Request, Please Check in Admin.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Sender Name : {{ $user['name'] }} 
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Sender Phone Number : {{ $user['phone'] }} 
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection