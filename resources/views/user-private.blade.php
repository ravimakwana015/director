@extends('layouts.app')
@section('content')
@include('include.header')
<div class="main">
	<user-private-chat :user="{{auth()->user()}}" :friend_id="{{ $id }}"></user-private-chat>
</div>
@include('include.footer')
@endsection

