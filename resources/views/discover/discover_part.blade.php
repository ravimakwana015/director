<a href="{{ route('discover.form',str_replace(' ', '-', strtolower($discover->title))) }}">
	<div class="job-logo">
		@if(isset($discover->icon) && $discover->icon!='')
		<img src="{{ asset('public/img/discover/'.$discover->icon.'') }}" alt="profile-pic" id="profile_img">
		@else
		<img src="{{ asset('public/front/images/240.jpg') }}" alt="profile-pic" id="profile_img">
		@endif
	</div>
	<div class="job-detail @if($discover->competitions=='Writing competitions')bg-actor @endif @if($discover->competitions=='Filming competitions')bg-model @endif @if($discover->competitions=='Singing competitions')bg-musician @endif @if($discover->competitions=='Best photo competitions')bg-grey @endif">
		<div class="job-detail-inner">
			<span class="job-title">{!! ucfirst($discover->title) !!}</span>
			<div class="type">{!! $discover->location !!}</div>
			<div class="type">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discover->created_at))->diffForHumans() }}
			</div>
		</div>
	</div>
</a>
