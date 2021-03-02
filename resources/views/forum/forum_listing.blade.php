@extends('layouts.app')
@section('title','Forum')
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
                    <span class="title">Search Forums</span>
                    <form>
                        <div class="form-item">
                            <input type="search" name="search" placeholder="Search Topic" value="{{ request()->get('search') }}"
                                   onchange="ajaxLoad('{{route('forumlist')}}?search='+this.value,'ser_cont')">
                        </div>
                    </form>
                    <span class="title">Recent Topics</span>
                    <ul>
                        @foreach($forumRecentTopics as $forum_recent_topics)
                            <li><a href="{!! route('topic',$forum_recent_topics->id) !!}">{!! $forum_recent_topics->topic_subject !!}</a></li>
                        @endforeach
                    </ul>
                    <div class="topic-btn">
                        <a href="{{ route('forum') }}" class="btn">Add Topic</a>
                    </div>

                    <span class="title">Forum Categories</span>
                    <ul>
                        @foreach(forumCategory() as $category)
                            <li><a href="{!! route('form-category',$category->id) !!}">{!! substr($category->title,0,50) !!}</a></li>
                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="right-content">
                <h2>Forums</h2>
                <ul class="breadcrumbs">
                    <li><a href="{{ route('home') }} " class="active">Home Pages</a></li>
                    <li><a href="javascript:void(0)">Forum</a></li>
                </ul>
                <div class="form-table">
                    <div class="table-header">
                        <div class="forum-head">Forum</div>
                        <div class="topics-head">Comments</div>
                        <div class="freshness-head">Latest</div>
                        <div class="posts-head">Action</div>
                    </div>
                    <div class="table-body">
                        @if(!empty($forum_topics))
                            @foreach($forum_topics as $forum_details)
                                <div class="table-row">
                                    <div class="forum">
                                        <span class="title"><a href="javascript:void(0)">{!! $forum_details->forumCategory->title !!}</a></span>
                                        {{--                                        @if(isset($forum_details->topic_subject) && $forum_details->topic_subject!='')--}}
                                        {{--                                            <span class="topic-subject">{!! $forum_details->topic_subject !!}</span>--}}
                                        {{--                                        @endif--}}
                                        <p><a href="{!! route('topic',$forum_details->id) !!}">{!! $forum_details->topic_subject !!}</a></p>
                                    </div>
                                    <div class="topics">
                                        <span class="label">Topics</span>
                                        <div class="comment"><span class="icon"><i class="far fa-comment"></i></span>{!! count($forum_details->comments) !!}</div>
                                    </div>
                                    <div class="freshness">
                                        <span class="label">Latest</span>
                                        <div class="time">{{ $forum_details->created_at->diffForHumans()}}</div>
                                    </div>
                                    <div class="posts">
                                        <span class="label">View</span>
                                        <div class="time"><a class="btn" href="{!! route('topic',$forum_details->id) !!}">View</a></div>
                                    </div>
                                </div>
                            @endforeach
                        @else

                            <div class="table-row">
                                No Topic Available
                            </div>
                        @endif

                    </div>
                </div>
                <div class="forum-pagination">
                    {{ $forum_topics->links() }}
                </div>
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
