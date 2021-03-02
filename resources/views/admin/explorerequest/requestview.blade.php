@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Develop Request Details
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
								<span class="input-group-text">Develop Title - </span>
								<span class="input-group-text answer">{{ $careerdetail->explore->title }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Develop Description - </span>
								<span class="input-group-text answer">{!! $careerdetail->explore->description !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Develop Location - </span>
								<span class="input-group-text answer">{{ $careerdetail->explore->location }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Profile Name -</span>
								<span class="input-group-text answer">{{ $careerdetail->profile_name }}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Message -</span>
								<span class="input-group-text answer">{!! $careerdetail->cover_letter !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Phone Number -</span>
								<span class="input-group-text answer">{{ $careerdetail->phone }}</span>
							</div>
						</div>
						{{-- <div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">CV</span>
								<span class="input-group-text answer"><a class="btn btn-info" target="_blank"  href="{{asset('/public/documents/explore_cv/'.$careerdetail->cv.'')}}">CV</a></span>
							</div>
						</div> --}}
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection