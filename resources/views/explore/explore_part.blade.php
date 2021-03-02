<a href="{{ route('explore.form',$explores->slug) }}">
	<div class="job-logo">
		@if(isset($explores->icon) && $explores->icon!='')
		<img src="{{ asset('public/img/explore/'.$explores->icon.'') }}" alt="profile-pic" id="profile_img">
		@else
		<img src="{{ asset('public/front/images/240.jpg') }}" alt="profile-pic" id="profile_img">
		@endif
	</div>
	<div class="job-detail @if($explores->job_type=='Actor')bg-actor-9
		@endif @if($explores->job_type=='Models') bg-model-9 @endif @if($explores->job_type=='Musicians')bg-musician-9 @endif">
		<div class="job-detail-inner">
			<span class="job-title">{!! ucfirst($explores->title) !!}</span>
			<div class="type">{!! $explores->location !!}</div>
			{{-- <div class="type">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($explores->created_at))->diffForHumans() }} </div> --}}
		</div>
	</div>
</a>
