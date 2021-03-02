@extends('layouts.app')
@section('content')
@include('include.header')
<div class="main">
	<private-chat :user="{{auth()->user()}}"></private-chat>
</div>
@include('include.footer')
@endsection

