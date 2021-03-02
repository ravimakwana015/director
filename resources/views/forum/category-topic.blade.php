@extends('layouts.app')
@section('title','Forum')
@section('content')
    @include('include.header')
    <div class="main forum-page">
        <div class="custom-container">
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
                            <li><a href="{!! route('topic',$forum_recent_topics->id) !!}">{!! str_limit($forum_recent_topics->topic,50) !!}</a></li>
                        @endforeach
                    </ul>
                    <div class="topic-btn">
                        <a href="{{ route('forum') }}" class="btn">Add Topic</a>
                    </div>

                    <span class="title">Forum Category</span>
                    <ul>
                        @foreach(forumCategory() as $form_category)
                            <li><a href="{!! route('form-category',$form_category->id) !!}">{!! substr($form_category->title,0,50) !!}</a></li>
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
                        @if(!empty($category->categoryTopics))
                            @foreach($category->categoryTopics as $category_topic)
                                <div class="table-row">
                                    <div class="forum">
                                        <span class="title"><a href="javascript:void(0)">{!! $category->title !!}</a></span>
                                        @if(isset($category_topic->topic_subject) && $category_topic->topic_subject!='')
                                            <span class="topic-subject">{!! $category_topic->topic_subject !!}</span>
                                        @endif
                                        <p><a href="{!! route('topic',$category_topic->id) !!}">{!! str_limit($category_topic->topic,50) !!}</a></p>
                                    </div>
                                    <div class="topics">
                                        <span class="label">Topics</span>
                                        <div class="comment"><span class="icon"><i class="far fa-comment"></i></span>{!! count($category_topic->comments) !!}</div>
                                    </div>
                                    <div class="freshness">
                                        <span class="label">Latest</span>
                                        <div class="time">{{ $category_topic->created_at->diffForHumans()}}</div>
                                    </div>
                                    <div class="posts">
                                        <span class="label">View</span>
                                        <div class="time"><a class="btn" href="{!! route('topic',$category_topic->category_id) !!}">View</a></div>
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
{{--                    {{ $forum_topics->links() }}--}}
                </div>
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
