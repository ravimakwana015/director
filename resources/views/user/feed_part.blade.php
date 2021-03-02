@if(isset($feeds) && count($feeds)>0)

@foreach($feeds as $feed)
<div class="feed-post @if(userPostLike($feed->id)) liked @endif" id="feed_div_{{ $feed->id }}">
    <div class="pro-detail">
        <div class="pro_img">
            @if(isset($feed->postOwner->profile_picture) && $feed->postOwner->profile_picture!='' && file_exists(public_path('img/profile_picture/').$feed->postOwner->profile_picture))
            <img src="{{ asset('public/img/profile_picture/'.$feed->postOwner->profile_picture.'') }}" alt="profile-pic" id="profile_img">
            @else
            <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
            @endif
        </div>
        <div class="pro_name">
            <a href="{{ route('profile-details',str_replace(' ', '-', strtolower($feed->postOwner->username))) }}">
                <h3>{{ $feed->postOwner->first_name }} {{ $feed->postOwner->last_name }}</h3></a>
                <span class="desig">{{ getUserTypeValue($feed->postOwner->user_type) }}</span>
                @if(dayDiff($feed->created_at)<=24)
                @php
                $diff = $feed->created_at->diffForHumans(null, true, true, 1);
                @endphp
                <span class="date-time">{{ str_replace(['h', 'm'], ['hrs', ' mins'], $diff) }}</span>
                @elseif(dayDiff($feed->created_at)>24 && dayDiff($feed->created_at)<=48)
                <span class="date-time">Yesterday at {{ date('H:i A',strtotime($feed->created_at)) }}</span>
                @else
                @php
                $year = date('Y', strtotime($feed->created_at));
                @endphp
                @if($year== date('Y'))
                <span class="date-time">{{ date('M d',strtotime($feed->created_at)) }} at {{ date('H:i A',strtotime($feed->created_at)) }}</span>
                @else
                <span class="date-time">{{ date('M d, Y',strtotime($feed->created_at)) }} at {{ date('H:i A',strtotime($feed->created_at)) }}</span>
                @endif
                @endif
            </div>
            {{-- $feed->feed_type=='status' &&  --}}
            @if($feed->postOwner->id==Auth::id())
            <div class="open-action-box" onclick="openActionBox('{{ $feed->id }}')">
                <span>···</span>
                <div class="action-box action-box_{{ $feed->id }}" style="display: none;">
                    <a href="javascript:;" onclick="editPost('{{ $feed->id }}')">Edit</a>
                    <a href="javascript:;" onclick="deletePostModal('{{ $feed->id }}')">Delete</a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($feed->properties) && $feed->properties!='')
        @php
        $img=json_decode($feed->properties);
        @endphp
        <div class="post_wrap @if(isset($img->profile_pic)) profile_pic @elseif(isset($img->imagegallery)) more_imgs @endif">

            <p id="feed_p_{{ $feed->id }}">{!! $feed->feed !!}</p>

            @if(isset($img->image) && $img->image !='' && file_exists(public_path('img/feed_images/').$img->image))
            <img src="{{ asset('public/img/feed_images/'.$img->image.'') }}" alt="profile-pic" id="profile_img">
            @elseif(isset($img->profile_pic) && $img->profile_pic !='' && file_exists(public_path('img/profile_picture/').$img->profile_pic))
            <img src="{{ asset('public/img/profile_picture/'.$img->profile_pic.'') }}" alt="profile-pic" id="profile_img">
            @elseif(isset($img->imagegallery))
            @foreach($img as $key=>$gallery)
            @foreach($gallery as $key=>$image)
            @if(isset($image) && $image !='' && file_exists(public_path('img/user_gallery/').$image))
            <div class="grid">
                <img src="{{ asset('public/img/user_gallery/'.$image.'') }}">
            </div>
            @endif
            @endforeach
            @endforeach
            @elseif(isset($img->video))
            @if($img->video !='' && file_exists(public_path('img/video_gallery/').$img->video))
            <video controls>
                <source src="{{ asset('public/img/video_gallery/'.$img->video.'') }}" type="video/mp4"/>
                    <source src="{{ asset('public/img/video_gallery/'.$img->video.'') }}" type="video/ogg"/>
                        Your browser does not support HTML5 video.
                    </video>
                    @endif
                    @elseif(isset($img->forum_topic))
                    Add new Forum Topic <a href="{!! route('topic',$img->forum_topic->id) !!}">Take a look</a>
                    @elseif(isset($img->comment))
                    left a comment on Forum Topic <a href="{!! route('topic',$img->comment->topic_id) !!}">Take a look</a>
                    @elseif(isset($img->enter))
                    @php
                    $parm=str_replace(' ', '-', strtolower($img->enter->enter));
                    @endphp
                    Apply on Enter <a href="{!! route('discover.form', $parm)!!}" title="{!! $img->enter->enter !!}">Take a look</a>
                    @elseif(isset($img->develop))
                    @php
                    $parm=str_replace(' ', '-', strtolower($img->develop->develop));
                    @endphp
                    Sent enquiry on <a href="{!! route('explore.form', $parm)!!}" title="{!! $img->develop->develop !!}">Take a look</a>
                    @endif

                </div>
                @else
                <div class="post_wrap">
                    <p id="feed_p_{{ $feed->id }}">{!! $feed->feed !!}</p>
                </div>
                @endif
                <div class="like_here">
                    <div class="like-comment">
                        @if(userPostLike($feed->id))
                        <span class="icon like_icon_{{ $feed->id }}" onclick="disLikePost('{{ $feed->id }}')">
                           <i class="fas fa-heart"></i>
                       </span>
                       @else
                       <span class="icon like_icon_{{ $feed->id }}" onclick="likePost('{{ $feed->id }}')">
                           <i class="far fa-heart"></i>
                       </span>
                       @endif
                       <span class="like_count_{{ $feed->id }}">{{ count($feed->postLike) }}</span>
                   </div>
                   <div class="like-comment">
                    <span class="icon comment_icon_{{ $feed->id }}" onclick="openCommentBox('{{ $feed->id }}')">
                       <i class="far fa-comment-dots"></i>
                   </span>
                   <span class="comment_count_{{ $feed->id }}">{{ count(userPostComments($feed->id)) }}</span>
               </div>


               <div class="comment-box_{{ $feed->id }}" style="display: none;margin-top: 10px;">

                <div class="comment-wrapper">
                    <div class="panel panel-info">
                        <div class="panel-body">
                            <ul class="media-list_{{ $feed->id }}">
                                @if(userPostComments($feed->id) && count(userPostComments($feed->id))>0)
                                @foreach(userPostComments($feed->id) as $comment)
                                @if(isset($comment->commentOwner))
                                <li class="media" id="comment_li_{{ $comment->id }}">
                                    <a href="#" class="pull-left">
                                        @if(isset($comment->commentOwner->profile_picture) && $comment->commentOwner->profile_picture!='')
                                        <img src="{{ asset('public/img/profile_picture/'.$comment->commentOwner->profile_picture.'') }}" alt="profile-pic"
                                        id="profile_img" width="80" height="80">
                                        @else
                                        <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img" width="80"
                                        height="80">
                                        @endif
                                    </a>
                                    <div class="media-body">
                                        <strong>{{ $comment->commentOwner->first_name }} {{ $comment->commentOwner->last_name }}</strong>
                                        <span class="text-muted pull-right">
                                            @php
                                            $diff = $comment->created_at->diffForHumans(null, true, true, 1);
                                            @endphp
                                            <small class="text-muted">{{ str_replace(['h', 'm'], ['hrs', ' mins'], $diff) }}</small>
                                        </span>
                                        <p>{!! $comment->comment !!}</p>
                                    </div>
                                    @if(Auth::user() && $comment->commentOwner->id==Auth::user()->id)
                                    <div class="open-action-box" onclick="openActionBox('{{ $comment->id }}')">
                                        <span>···</span>
                                        <div class="action-box action-box_{{ $comment->id }}" style="display: none;">
                                            <a href="javascript:;" onclick="deletePostComment('{{ $comment->id }}')">Delete</a>
                                        </div>
                                    </div>
                                    @endif
                                </li>
                                @endif
                                @endforeach
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <textarea placeholder="Write a comment..." id="comment_{{ $feed->id }}"></textarea>
                <div class="comment-error_{{ $feed->id }}" style="color:red;margin-bottom: 10px;"></div>
                <div class="btn-wrap">
                    <button class="btn" id="comment_btn" onclick="postComment('{{ $feed->id }}')">Comment</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    {{-- @include('user.feed_empty') --}}
    @endif
