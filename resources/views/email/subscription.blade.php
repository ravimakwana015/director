@extends('email.layouts.app')

@section('content')
<div class="content">

	<p style="line-height: 24px; margin-bottom:15px;">
		Hi {{ $user['first_name'] }} {{ $user['last_name'] }}!
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		THANK YOU FOR SIGNING UP!
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		We are so happy to have you on board. There is nothing like a new talent joining our platform. We are excited for you to start your journey with Producers Eye.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		If you need any help getting started, please get in touch using our Contact Form or have a look out our feature list on your Dashboard!
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Your membership details are stated on your dashboard and you can cancel at any time. As you are receiving this email, you have accepted the User Agreement and Privacy Policy and we really do hope you treat everyone on this platform respectfully.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		This platform is a family of talent from all over the world and we are so thrilled to have you joining our family.
	</p>
	<p style="line-height: 24px; margin-bottom:15px;">
		Speak soon,
	</p>
	<p style="line-height: 24px">@yield('title', config('app.name')) Team.</p>
	@include('email.layouts.footer')
</div>
@endsection
