@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, {{ $user['name'] }}
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		{{ $user['name'] }} 
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Your Code : {{ $user['admin_refer_code'] }} 
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection