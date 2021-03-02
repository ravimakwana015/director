@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, Admin.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{{ $user['profile_name'] }} Send You A Career Request, Please Check in Admin.
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection