@if(isset($friend))
<meta name="friendId" content="{{ $friend->id }}">
@endif

@if(isset($friends) && count($friends)>0)
<section class="chat-list-left">
	<div class="previous-chat">
		<div class="top-wrap">
			<div class="search-bar">
				<user-search></user-search>
			</div>
			{{-- <div class="edit-icon">
				<a href="#"><i class="far fa-edit"></i></a>
			</div> --}}
		</div>
		@forelse ($friends as $key=>$fri)
		{{-- @if($key<=5) --}}
		<div class="chat chat-1">
			<a href="{{ route('chat.show',$fri->id) }}" class="panel-block">
				<div class="img-wrap">
					@if(isset($fri->profile_picture) && !empty($fri->profile_picture))
					<img src="{{ asset('public/img/profile_picture/'.$fri->profile_picture.'') }}" width="50" height="50">
					@else
					<img src="{{ asset('public/front/images/196.jpg') }}" alt="Profile Picture" width="50" height="50">
					@endif
				</div>
				<div class="name @if($fri->id==$friend->id) active @endif">
					<h2>{{ $fri->username }}</h2>
					<span class="status"><onlineuser v-bind:friend="{{ $fri }}" v-bind:onlineusers="onlineUsers"></onlineuser>
					</span>
				</div>
				<div class="time">
					@if(count($fri->unreadNotifications->where('type','App\Notifications\MessageNotification')))
					<span class="number">
						{{ count($fri->unreadNotifications->where('type','App\Notifications\MessageNotification')) }} 
					</span>
					@endif
				</div>
			</a>
		</div>
		{{-- @endif --}}
		<div id="search_user"></div>
		@empty
		<div class="panel-block">
			You don't have any Message
		</div>
		@endforelse
	</div>
</section>
@if( isset($friend))
<section class="chat-section">
	<div class="chat-wrap">
		<div class="head-wrap">
			<div class="to-name">
				<p>To: {{ $friend->username }}</p>
				<a href="{{ route('chat.index') }}"><i class="fas fa-angle-right"></i></a>
			</div>
		</div>
		<div class="chat-messages">
			<chat v-bind:chats="chats" v-bind:userid="{{ Auth::user()->id }}" v-bind:friendid="{{ $friend->id }}"></chat>
		</div>
	</div>
</section>
<section class="my-profile-intro">
	<div class="person-img">
		@if(isset($friend->profile_picture) && $friend->profile_picture!='')
		<img src="{{ asset('public/img/profile_picture/'.$friend->profile_picture.'') }}" alt="profile-pic">
		@else
		<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic">
		@endif
	</div>
	<div class="information">
		<div class="info-img">
			@if(isset($friend->profile_picture) && $friend->profile_picture!='')
			<img src="{{ asset('public/img/profile_picture/'.$friend->profile_picture.'') }}" alt="profile-pic">
			@else
			<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic">
			@endif
		</div>
		<div class="info-wrap">
			<h3>{{ $friend->first_name }} {{ $friend->last_name }}</h3>
			<a href="#"><i class="fas fa-map-marker-alt"></i></a>
			<p>{{ $friend->city }},{{ $friend->country }}</p>
		</div>
		<div class="short-intro">
			<p>{{ $friend->caption }}</p>
		</div>
		<div class="leftbar-toggle">
			<span></span>
			<span></span>
			<span></span>
		</div>
	</div>
</section>
@endif
@else
<div class="row">
	<div class="col-md-12">
		<div class="chat-box">
			<div class="panel-block">
				<h1 class="no-PoachBox">You don't have any Message</h1>
			</div>
		</div>
	</div>
</div>
@endif