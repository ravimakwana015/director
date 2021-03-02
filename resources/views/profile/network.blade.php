@if(isset($userFriend) && $userFriend->status==0 && $userFriend->user_id==Auth::id())

<button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"
        id="add-friend-btn">
	<i class="fas fa-hourglass"></i> Request pending
</button>

@elseif(isset($userFriend) && $userFriend->status==0 && $userFriend->friend_id==Auth::id())
<button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"
        onclick="confirmFriend('{{ $user->id }}')" id="add-friend-btn">
	<i class="fas fa-user"></i> Confirm Request
</button>

@elseif(isset($userFriend) && $userFriend->status==1)

<button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"
        onclick="unFriend('{{ $user->id }}')" id="add-friend-btn">
	<i class="fas fa-trash"></i> Un-Friend
</button>

@elseif(isset($userFriend) && $userFriend->status==3)

<button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"
        id="add-friend-btn">
	<i class="fas fa-lock"></i> Blocked
</button>

@else

<button type="button" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3') bg-musician @endif"
        onclick="sendFriendRequest('{{ $user->id }}')" id="add-friend-btn">
	<i class="fas fa-user-plus"></i> Add Friend
</button>

@endif

{{-- @if(isset($userFriend))
<a href="javascript:;" title="Report User" class="btn @if($user->user_type=='1') bg-actor @endif @if($user->user_type=='2') bg-model @endif @if($user->user_type=='3')
 bg-musician @endif" onclick="@if(isset($userFriend) && $userFriend->status==3) alreadyReportModal() @else reportModal('{{ $user->id }}') @endif" id="report_newtwork_btn"><i class="fas fa-exclamation-triangle"></i></a>
@endif --}}
