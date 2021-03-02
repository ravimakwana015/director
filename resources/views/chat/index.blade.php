@extends('layouts.app')
@section('content')
@include('include.header')
<div class="main">
	<div class="chat-wrap-main">
		@include('chat.chat')
	</div>
</div>
@include('include.footer')
@endsection
