@extends('layouts.app')
@section('title','Profiles Details')
@section('content')
@include('include.header')


<div class="main profile-page user-activity">
	<div class="custom-container">
		<div class="name-header">
			<h2>{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }}</h2>
		</div>
		@include('admin.include.message')

		<div class="profile-wrap @if($user->user_type=='1') actor @endif @if($user->user_type=='2') model @endif @if($user->user_type=='3') musician @endif
        @if($user->user_type=='4') crew @endif">
			<div class="grid">

				<div class="img-bio">
					<div class="img-wrap">
						@if(isset($user->profile_picture) && $user->profile_picture!='')
						<img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="profile-pic" id="profile_img">
						@else
						<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
						@endif
					</div>
				</div>

				{{-- @include('profile.profile-like-unlike') --}}

				@if(isset($user->caption) && $user->caption!='')
				<div class="self-bio">
					<p>"{!! $user->caption  !!}"</p>
				</div>
				@endif

			</div>
			<div class="grid">
				<div class="shadow-box">
					<h3 class="title">Recent Activity</h3>
					<p>
						@if(isset($user->userAllActivity) && count($user->userAllActivity)>0)
						@include('profile.user-activity',['userActivity'=>$user->userAllActivity])
						@else N/A  @endif
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
@include('include.footer')
@endsection
