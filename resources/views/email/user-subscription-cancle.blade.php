@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello {{ $user['first_name'] }} {{ $user['last_name'] }}!
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		We are so sorry to see you leave. I do hope your stay with us has been great and you are able to continue to use our service until stated, which is within your dashboard. 
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		You can reply back to this email with any feedback you suggest and what would help bring you back to the platform. 
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Hopefully will see you back on @yield('title', config('app.name')) soon.  
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		All the best,  
	</p>

	<p style="line-height: 24px">@yield('title', config('app.name')) Team.</p>
	
	@include('email.layouts.footer')
</div>
@endsection