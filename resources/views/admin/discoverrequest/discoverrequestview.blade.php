@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Enter Request Details
		</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				@include("admin.include.message")
				<div class="box box-info content-request">
					<div class="box-body qa-ans">
						
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Competitions - </span>
								<span class="input-group-text answer">{{ $discoverdetail->discovers->competitions }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Competitions Title - </span>
								<span class="input-group-text answer">{{ $discoverdetail->discovers->title }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Competitions Description - </span>
								<span class="input-group-text answer">{!! $discoverdetail->discovers->description !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Profile Name - </span>
								<span class="input-group-text answer">{{ $discoverdetail->profile_name }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Cover Letter - </span>
								<span class="input-group-text answer">{!! $discoverdetail->cover_letter !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								{{-- <span class="input-group-text">CV - </span> --}}
								<span class="input-group-text answer"><a class="btn btn-info" target="_blank"  href="{{asset('/public/documents/discover_cv/'.$discoverdetail->cv.'')}}">CV</a></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection