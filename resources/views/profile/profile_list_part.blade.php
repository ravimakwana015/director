<a href="{{ route('profile-details',str_replace(' ', '-', strtolower($user->username))) }}" class="">
	<span class="loader-1">
		<span  class="loading-bar"></span>
	</span>
	<div class="job-logo">
		@if(file_exists('public/img/profile_picture/225/' . $user->profile_picture) && isset($user->profile_picture)  && $user->profile_picture!='')
		<img src="{{ asset('public/img/profile_picture/225/'.$user->profile_picture.'') }}"  data-src="" alt="profile-pic" class="lazy"  id="profile_img">
		@elseif(file_exists('public/img/profile_picture/' . $user->profile_picture) && isset($user->profile_picture)  && $user->profile_picture!='')
		<img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}"  data-src="" alt="profile-pic" class="lazy"  id="profile_img">
		@else
		<img src="{{ asset('public/front/images/240.jpg') }}" data-src="" alt="profile-pic" class="lazy" id="profile_img">
		@endif
	</div>
	<div class="job-detail @if($user->user_type=='1')
		bg-actor-9
		@endif
		@if($user->user_type=='2')
		bg-model-9
		@endif
		@if($user->user_type=='3')
		bg-musician-9
		@endif
		@if($user->user_type=='4')
		bg-crew-9
		@endif">
		<div class="job-detail-inner">
			<span class="job-title">{{ $user->first_name }} {{ $user->last_name }}</span>
			<div class="type">
				@if(isset($user->caption) && $user->caption!='') "{!! str_limit($user->caption, 50) !!}" @else N/A @endif
			</div>
			<div class="type">
				@if($user->user_type=='1')
				<img src="{{ asset('public/front/images/actor_list.svg') }}" height="20" width="20">
				@endif
				@if($user->user_type=='2')
				<img src="{{ asset('public/front/images/actor_list.svg') }}"  height="20" width="20">
				@endif
				@if($user->user_type=='3')
				<img src="{{ asset('public/front/images/actor_list.svg') }}"  height="20" width="20">
				@endif
				@if($user->user_type=='4')
				<img src="{{ asset('public/front/images/actor_list.svg') }}"  height="20" width="20">
				@endif
				@if(isset($user->likes))
				{{ likeCount($user->id) }}
				@else
				0
				@endif
				@if($user->verify=='verify')
				<img src="{{ asset('public/front/images/correct.svg') }}" height="20" width="20">
				@endif
			</div>
		</div>
	</div>
</a>
