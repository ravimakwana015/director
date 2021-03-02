@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, Admin {!! $user['name'] !!} Send You a message.
	</p>
    <p style="line-height: 24px; margin-bottom:15px;">
        <a href="mailto:{!! $user['email'] !!}">{!! $user['email'] !!}</a>
    </p>
    <p style="line-height: 24px; margin-bottom:15px;">
        <a href="tel:{!! $user['contact_number'] !!}">{!! $user['contact_number'] !!}</a>
    </p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{!! $user['message'] !!}
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection
