@extends('layouts.app')
@section('content')
@include('include.header')
<div class="main">
	<div class="chat-wrap-main">
		<audio id="ChatAudio">
			<source src="{{ asset('public/sounds/chat.mp3') }}">
			</audio>
			@include('chat.chat')
		</div>
	</div>
	@include('include.footer')
	@endsection
