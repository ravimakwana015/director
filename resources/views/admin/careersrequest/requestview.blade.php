@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Careers Request Details
		</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				@include("admin.include.message")
				<div class="box box-info">
					<div class="box-body qa-ans">
						
						<div class="input-group mb-3">
							<div class="input-group-prepend">
								<span class="input-group-text">Careers Title - </span>
								<span class="input-group-text answer">{!! $careerdetail->career->title !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Careers Description - </span>
								<span class="input-group-text answer">{!! $careerdetail->career->description !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Careers Location - </span>
								<span class="input-group-text answer">{!! $careerdetail->career->location !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Profile Name - </span>
								<span class="input-group-text answer">{!! $careerdetail->profile_name !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text">Cover Letter - </span>
								<span class="input-group-text answer">{!! $careerdetail->cover_letter !!}</span>
							</div>
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								{{-- <span class="input-group-text">CV</span> --}}
								<span class="input-group-text answer">
									<a target="_blank"  class="btn btn-info" href="{{asset('/public/documents/career_cv/'.$careerdetail->cv.'')}}">CV</a></span>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection