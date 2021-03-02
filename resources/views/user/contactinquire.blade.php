@extends('layouts.app')

@section('content')
@include('include.header')

<div class="main dashboard-page notifications-page">
	<div class="custom-container">
		<div class="dashboard-wrap">
			<div class="left-sidebar">
				@include('user.left-sidebar')
			</div>
			<div class="right-side">
				<div class="main-dashboard">
					<div class="accounts-wrap">

						<div class="my-account">
							<div class="account-header">
								<h3 class="dash-title">Inquiry Messages</h3>
							</div>
							<div class="account-body">
								@if ($message = Session::get('success'))
								<div class="alert alert-success alert-block">
									<button type="button" class="close" data-dismiss="alert">×</button> 
									<strong>{{ $message }}</strong>
								</div>
								@endif
								@if(isset($contactinquires))
								@foreach($contactinquires as $contactinquire)
								<div class="bill">
									<h4>
										<div class="status bg-actor"></div> 
										{{ $contactinquire['name'] }} want To connect With you 
									</h4>
									<button onclick="expander( {{ $contactinquire->id }} );" class="btn btn-small" type="button" id="expander">View</button>
									<a href="{{ route('delete.contactinquire',[$contactinquire->id]) }}" class="btn btn-small"><i class="fa fa-trash"></i></a>
									<div class="contact-main" id="TableData_{{ $contactinquire->id }}" style="display: none;">
										<div class="content-inquire">
											<div class="name">
												Name : {{ $contactinquire['name'] }} 
											</div>
											<div class="company">
												Company : {{ $contactinquire['company'] }} 
											</div>
											<div class="email">
												Email: <a href="mailto:{{ $contactinquire['email'] }}">{{ $contactinquire['email'] }}</a> 
											</div>
											<div class="subject">
												Subject : {{ $contactinquire['subject'] }} 
											</div>
											<div class="message">
												Message: {{ $contactinquire['message'] }} 
											</div>
											<div class="facebook">
												Facebook : {{ $contactinquire['facebook'] }} 
											</div>
											<div class="linkedin">
												LinkedIn : {{ $contactinquire['linkedin'] }} 
											</div>
											<div class="instagram">
												Instagram : {{ $contactinquire['instagram'] }} 
											</div>
											@if (file_exists( public_path() . '/img/inquiry/' . $contactinquire['photo']) && $contactinquire['photo']!='')
											<div class="instagram">
												Photo of User : <img src="{{ asset('public/img/inquiry/'.$contactinquire['photo'].'') }} " class="rounded" height="350"/>
											</div>
											@endif
										</div>
									</div>
								</div>
								@endforeach
								{{ $contactinquires->links() }}
								@else
								<div class="bill">
									<h4>
										@php
										echo "Inquiry Messages Not Available";
										@endphp
									</h4>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function expander($id)
	{
		var id = $id;	
		$('#TableData_'+id+'').slideToggle('slow');
	}
	
</script>
@include('include.footer')
@endsection	