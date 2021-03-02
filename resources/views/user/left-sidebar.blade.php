	<ul>
		<li>
			<a href="{{ route('dashboard') }}" class="@if(\Request::route()->getName()=='dashboard') active @endif"><span class="icon"><i class="fas fa-user"></i></span>Profile</a>
		</li>
		<li>
			<a href="{{ route('my-network',[str_replace(' ', '-', strtolower(Auth::user()->username))]) }}"><span class="icon"><i class="fas fa-eye"></i></span>My Network</a>
		</li>
		<li>
			<a href="{{route('chat.index')}}" ><span class="icon"><i class="fas fa-comment-dots"></i></span>Messages @if(count(auth()->user()->unreadNotifications->where('type','App\Notifications\MessageNotification')))({{ count(auth()->user()->unreadNotifications->where('type','App\Notifications\MessageNotification')) }})@endif</a>
		</li>
		<li>
			<a href="{{ route('contactinquires') }}" class="@if(\Request::route()->getName()=='contactinquires') active @endif"><span class="icon"><i class="far fa-question-circle"></i></span>Inquires @if(count(auth()->user()->unreadNotifications->where('type','App\Notifications\ContactProfileNotification')))({{ count(auth()->user()->unreadNotifications->where('type','App\Notifications\ContactProfileNotification')) }})@endif</a>
		</li>
		<li>
			<a href="{{ route('career') }}"><span class="icon"><i class="far fa-money-bill-alt"></i></span>Careers</a>
		</li>
		<li>
			<a href="{{ route('forumlist') }}"><span class="icon"><i class="fas fa-mobile-alt"></i></span>Forum</a>
		</li>
		<li>
			<a href="{{ route('referfriend') }}"><span class="icon"><i class="far fa-paper-plane"></i></span>Refer to a Friend</a>
		</li>
		<li>
			<a href="{{ route('changepassword') }}"><span class="icon"><i class="fas fa-question"></i></span>Change Password</a>
		</li>
	</ul>