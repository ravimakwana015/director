<a href="{{ route('apply.form',$career->slug) }}">
	<div class="job-logo">
		@if(isset($career->icon) && $career->icon!='')
		<img src="{{ asset('public/img/career_icon/'.$career->icon.'') }}" alt="profile-pic" id="profile_img">
		@else
		<img src="{{ asset('public/front/images/240.jpg') }}" alt="profile-pic" id="profile_img">
		@endif
	</div>
	<div class="job-detail @if($career->job_type=='Actor')bg-actor-9
		 @endif @if($career->job_type=='Models') bg-model-9 @endif @if($career->job_type=='Musicians')bg-musician-9 @endif">
		<div class="job-detail-inner">
			<span class="job-title">{!! ucfirst($career->title) !!}</span>
			<div class="type">{!! $career->location !!}</div>
			<div class="type">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($career->created_at))->diffForHumans() }}
			</div>
		</div>
	</div>
</a>
