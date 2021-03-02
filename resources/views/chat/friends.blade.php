@extends('layouts.app')
@section('content')
@include('include.header')

<div class="main profile-page">
	<div class="custom-container">
		<div class="name-header">
			<h2>List of all Friends</h2>
		</div>
		<div class="friends-list-wrap">
			@include('admin.include.message')
			<div class="card">
				<div class="card-body">
					@if(isset($friends) && count($friends)>0)
					<ul class="list-group">
						@forelse ($friends as $friend)
						<li class="list-group-item d-flex justify-content-between align-items-center">
							<a href="{{ route('chat.show', $friend->id) }}" style="justify-content: space-between;">
								<div class="user-details">
									<span>{{ $friend->username }}</span>
									<onlineuser v-bind:friend="{{ $friend }}" v-bind:onlineusers="onlineUsers"></onlineuser>
								</div>
							</a>
							<a href="{{ route('friend.delete', $friend->id) }}"><i class="fas fa-trash"></i></a>
						</li>
						@endforeach
					</ul>
					@else
					<div class="panel-block">
						<h3>You don't have any friends</h3>
					</div>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@include('include.footer')

@endsection
