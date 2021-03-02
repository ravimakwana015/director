@extends('layouts.app')
@section('title','Forum')
<?php
if (count(generalpage()) > 0) {
	$generalpage = generalpage()[0];
}
?>
@if(count(generalpage())>0)
@section('meta-title',strip_tags($generalpage['seo_title']))
@section('meta-keywords',strip_tags($generalpage['seo_keyword']))
@section('meta-description',strip_tags($generalpage['seo_description']))
@endif
@section('content')
@include('include.header')

<div class="main forum-page">
	<div class="custom-container">
		<div class="left-content">
			<div class="list-content">
				<div class="text-left">
					<a href="{{ route('forumlist') }}" class="btn"><i class="fas fa-arrow-left"></i> Back</a>
				</div>
				<span class="title">Search Forums</span>
				<form action="{{route('forumlist')}}" id="forum-detail">
					<div class="form-item">
						<input type="search" name="search" placeholder="Search Topic" value="{{ request()->get('search') }}" onchange="$('#forum-detail').submit();">
					</div>
				</form>
				<span class="title">Recent Topics</span>
				<ul>
					@foreach($forumRecentTopics as $forum_recent_topics)
					<li><a href="{!! route('topic',$forum_recent_topics->id) !!}">{!! $forum_recent_topics->topic_subject !!}</a></li>
					@endforeach
				</ul>
			</div>
		</div>
		<div class="right-content">
			<h2>Forum</h2>
			@if ($message = Session::get('success'))
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>{{ $message }}</strong>
			</div>
			@endif
			<form action="{{ route('add.topic') }}" method="post" >
				@csrf
				<div class="form-group">
					<select name="forum_category" class="form-control">
						<option value="">Select Forum Category</option>
						@foreach($forum_category as $category)
						<option value="{!! $category->id !!}">{!! $category->title !!}</option>
						@endforeach
					</select>
					@if ($errors->has('forum_category'))
					<span class="text-danger">{{ $errors->first('forum_category') }}</span>
					@endif
				</div>
				<div class="form-group">
					<input class="form-control" name="topic_subject" placeholder="Topic Subject">
					@if ($errors->has('topic_subject'))
					<span class="text-danger">{{ $errors->first('topic_subject') }}</span>
					@endif
				</div>
				<div class="form-group">
					<textarea class="form-control" name="topic" placeholder="Add Topic Description" id="editor1"></textarea>
					@if ($errors->has('topic'))
					<span class="text-danger">{{ $errors->first('topic') }}</span>
					@endif
				</div>
				<div class="form-group">
					<button type="submit" class="btn" >Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="https://cdn.ckeditor.com/4.13.1/standard/ckeditor.js"></script>

<script>
	CKEDITOR.editorConfig = function (config) {
		config.language = 'es';
		config.uiColor = '#F7B42C';
		config.height = 300;
		config.toolbarCanCollapse = true;

	};
	CKEDITOR.replace('editor1');
</script>
@include('include.footer')
@endsection
