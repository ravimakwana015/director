<div class="profile-like-wrap ">
	<div class="grid">
		@if(Auth::user() && Auth::user()->id!=$user->id)
		<div class="profile-like-chat">
			<div class="Like">
				<form action="">
					<input type="hidden" name="profile_id" value="{{ $user->id }}" id="profile_id">
				</form>
				@if(empty($likeusers))
				<div class="like-icon">
					<button class="likeprofile" type="button" onclick="likeprofile()">
						@if($user->user_type=='1')
						<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='2')
						<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='3')
						<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='4')
						<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
						@endif
					</button>
					<span class="profile-like-count">({{ likeCount($user->id) }})</span>
				</div>
				@else
				<div class="like-icon">
					<button class="likeprofile" type="button" onclick="unlikeprofile()">
						@if($user->user_type=='1')
						<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='2')
						<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='3')
						<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
						@endif
						@if($user->user_type=='4')
						<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
						@endif
					</button>
					<span class="profile-like-count">({{ likeCount($user->id) }})</span>
				</div>
				@endif

			</div>
		</div>
		@else
		<div class="profile-like-chat">
			<div class="Like">
				@if(empty($likeusers))
				<div class="like-icon">
					@if($user->user_type=='1')
					<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='2')
					<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='3')
					<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='4')
					<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">
					@endif
					<span class="profile-like-count">({{ likeCount($user->id) }})</span>
				</div>
				@else
				<div class="like-icon">
					@if($user->user_type=='1')
					<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='2')
					<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='3')
					<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
					@endif
					@if($user->user_type=='4')
					<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">
					@endif
					<span class="profile-like-count">({{ likeCount($user->id) }})</span>
				</div>
				@endif
			</div>
		</div>
		@endif
	</div>
</div>
@if(Auth::user() && Auth::user()->id!=$user->id)
	<button type="button" class="btn" data-toggle="modal" data-target="#exampleModalCenter"> Inquire for Availability
	</button>
	@else
	@if(isset(Auth::user()->id) && Auth::user()->id==$user->id)
	@else
	<button type="button" class="btn" id="messageContact"> Inquire for Availability
	</button>
	@endif
	@endif