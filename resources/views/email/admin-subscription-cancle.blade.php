@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hello, Admin
		<br/> {{ $user['first_name'] }} {{ $user['last_name'] }} Cancle his/her Subscription.
	</p>

	<p style="line-height: 24px">Warm Regards,</br>Team @yield('title', config('app.name'))</p>
	@include('email.layouts.footer')
</div>
@endsection