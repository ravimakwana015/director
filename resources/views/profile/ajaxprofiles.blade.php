<div class="career-listing profile-listing" >
	<div id="tag_container2" style="display: none;"></div>
	<div class="isotope items" id="tag_container">
		@foreach($searchResult as $user)
		@if($user->user_type=='1')
		<div class="item actors">
			@include('profile.profile_list_part')
		</div>
		@elseif($user->user_type=='2')
		<div class="item models">
			@include('profile.profile_list_part')
		</div>
		@elseif($user->user_type=='3')
		<div class="item musicians">
			@include('profile.profile_list_part')
		</div>
		@elseif($user->user_type=='4')
		<div class="item crew">
			@include('profile.profile_list_part')
		</div>
		@endif
		@endforeach
	</div>
</div>
<div class="forum-pagination">
	{!! $searchResult->appends(request()->except('page'))->links() !!}
</div>
