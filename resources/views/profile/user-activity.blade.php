<ul>
	@foreach($userActivity as $activity)
	<li>
		@if($activity->subject_type =='App\Models\Forums\Forums')
		{{ $activity->description }} <a href="{!! route('topic',$activity->subject_id) !!}" title="{!! json_decode($activity->properties)->topic !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\Comment\Comment')
		{{ $activity->description }} on Forum Topic <a href="{!! route('topic',json_decode($activity->properties)->topic_id) !!}" title="{!! json_decode($activity->properties)->topic !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\CareerRequest\CareerRequest')
		@php
		$parm=str_replace(' ', '-', strtolower(json_decode($activity->properties)->career));
		@endphp
		{{ $activity->description }} <a href="{!! route('apply.form', $parm)!!}" title="{!! json_decode($activity->properties)->career !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\DiscoversRequests\DiscoversRequests')
		@php
		$parm=str_replace(' ', '-', strtolower(json_decode($activity->properties)->enter));
		@endphp
		{{ $activity->description }} <a href="{!! route('discover.form', $parm)!!}" title="{!! json_decode($activity->properties)->enter !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\ExploreRequest\ExploreRequest')
		@php
		$parm=str_replace(' ', '-', strtolower(json_decode($activity->properties)->explore));
		@endphp
		{{ $activity->description }} <a href="{!! route('explore.form', $parm)!!}" title="{!! json_decode($activity->properties)->explore !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\Like\Like')
		@php
		$parm=json_decode($activity->properties)->username;
		@endphp
		<a href="{!! route('profile-details', $parm)!!}" title="{!! json_decode($activity->properties)->name !!}">{{ json_decode($activity->properties)->name }}</a> {{ $activity->description }}
		<br/>
		@elseif($activity->subject_type =='App\Models\UsersPersonalityTraits\UsersPersonalityTraits')
		@php
		$parm=json_decode($activity->properties)->username;
		@endphp
		<a href="{!! route('profile-details', $parm)!!}" title="{!! json_decode($activity->properties)->name !!}">{{ json_decode($activity->properties)->name }}</a> {{ $activity->description }}
		<br/>
		@elseif($activity->subject_type =='App\Models\UserFeedsLikes\UserFeedsLikes')
		@php
		$parm=json_decode($activity->properties)->username;
		@endphp
		<a href="{!! route('profile-details', $parm)!!}" title="{!! json_decode($activity->properties)->name !!}">{{ json_decode($activity->properties)->name }}</a> {{ $activity->description }} <a href="{{ route('my-network',[str_replace(' ', '-', strtolower($user->username))]) }}" title="{!! $activity->description !!}">Take a look</a>
		<br/>
		@elseif($activity->subject_type =='App\Models\UserFeedComments\UserFeedComments')
		@php
		$parm=json_decode($activity->properties)->username;
		@endphp
		<a href="{!! route('profile-details', $parm)!!}" title="{!! json_decode($activity->properties)->name !!}">{{ json_decode($activity->properties)->name }}</a> {{ $activity->description }} <a href="{{ route('my-network',[str_replace(' ', '-', strtolower($user->username))]) }}" title="{!! $activity->description !!}">Take a look</a>
		<br/>
		@else
		{{ $activity->description }}
		<br/>
		@endif
		<i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
	</li>
	<hr/>
	@endforeach
</ul>
