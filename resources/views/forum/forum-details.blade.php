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
  @if ($message = Session::get('error'))
  <div class="alert alert-danger alert-block">
   <button type="button" class="close" data-dismiss="alert">×</button>
   <strong>{{ $message }}</strong>
  </div>
  @endif
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
   <h2>Forums</h2>
   @if ($message = Session::get('success'))
   <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ $message }}</strong>
   </div>
   @endif
   <div class="topic-title">
    @if(isset($topicDetails->username))
    Discussion in '{!! $topicDetails->topic_subject !!}' started by {!! $topicDetails->username->first_name !!} {!! $topicDetails->username->last_name !!}
    - {{ date('M jS Y H:i a',strtotime($topicDetails->created_at)) }}.
    @endif
   </div>


   @if(isset($topicDetails->topic_subject) && $topicDetails->topic_subject!='')
   <div class="topic-title">
    {!! $topicDetails->topic_subject !!}
   </div>
   @endif
   @if(isset($topicDetails->topic) && $topicDetails->topic!='')
   <div class="topic-description">
    {!! $topicDetails->topic !!}
   </div>
   @endif
      <div class="comment-section">
    <ul class="list-comment">
     @foreach($comments as $listcomment)
     @if(isset($listcomment->usercomment))
     <li>
      <div class="comment-image">
       <span class="@if($listcomment->usercomment->user_type=='1')
        bg-actor
        @endif
        @if($listcomment->usercomment->user_type=='2')
        bg-model
        @endif
        @if($listcomment->usercomment->user_type=='3')
        bg-musician
        @endif
        @if($listcomment->usercomment->user_type=='4')
        bg-crew
        @endif">
        @if($listcomment->usercomment->private_user == 1)
        <a href="javascript:void">
         @if(!empty($listcomment->usercomment->profile_picture))
         <img src="{{ asset('public/img/profile_picture/'.$listcomment->usercomment->profile_picture.'') }}" alt="profile-pic" id="header_icon">
         @else
         <img src="{{ asset('public/front/images/196.jpg') }}" alt="profile-pic" id="header_icon">
         @endif

         <span class="user-name">{!! ucfirst($listcomment->usercomment->first_name) !!} {!! ucfirst($listcomment->usercomment->last_name) !!}</span>
        </a>
        @else
        <a href="{{ route('profile-details',str_replace(' ', '-', strtolower($listcomment->usercomment->username))) }}">
         @if(!empty($listcomment->usercomment->profile_picture))
         <img src="{{ asset('public/img/profile_picture/'.$listcomment->usercomment->profile_picture.'') }}" alt="profile-pic" id="header_icon">
         @else
         <img src="{{ asset('public/front/images/196.jpg') }}" alt="profile-pic" id="header_icon">
         @endif

         <span class="user-name">{!! ucfirst($listcomment->usercomment->first_name) !!} {!! ucfirst($listcomment->usercomment->last_name) !!}</span>
        </a>
        @endif
       </span>
      </div>
      <div class="user-commit-details">
       <span class="user-commets"> {!! $listcomment->comment !!} </span>
       <span class="user-commit-time"> {{ $listcomment->created_at->diffForHumans()}} </span>
      </div>
     </li>
     @endif
     @endforeach
    </ul>
   </div>
   @if(Auth::user())
   <form action="{{ route('add.comment') }}" method="post">
    @csrf
    <div class="form-group">
     <input type="hidden" name="topic_id" value="{!! $topicDetails->id !!}">
     <textarea class="form-control" name="comment" placeholder="Comment"></textarea>
     @if ($errors->has('comment'))
     <span class="text-danger">{{ $errors->first('comment') }}</span>
     @endif
    </div>
    <div class="form-group">
     <button type="submit" class="btn">Comment</button>
    </div>
   </form>
   @else
   <div class="card">
    <div class="card-body">
     For post a new comment. You need to login first.
     <a href="{{ route('login') }}" class="btn">Log In</a>
    </div>
   </div>
   @endif
  </div>
 </div>
</div>
@include('include.footer')

@endsection
