@extends('email.layouts.app')

@section('content')
<div class="content">
	<p style="line-height: 24px; margin-bottom:15px;">
		Hello,
		{{ $touser->first_name }} {{ $touser->last_name }}.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{{ $user['name'] }} want To connect With you
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{{ $user['message'] }}
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection