@extends('layouts.app')

@section('content')
    @include('include.header')

    <div class="main feed-page profile-page">
        <div class="custom-container">
            <div class="feed-main-wrap">
                <div class="name-header">
                    <h2>
                        {{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }} -
                        @if($user->user_type!=4)
                            @if(isset($user->other_professions) && !empty($user->other_professions))
                                @php
                                    $profession=[];
                                @endphp
                                @foreach(json_decode($user->other_professions) as $key=>$prof)
                                    @php
                                        $profession[] = $prof;
                                    @endphp
                                @endforeach
                                @php
                                    $profession[] = getUserTypeValue($user->user_type);
                                @endphp
                            @else
                                @php
                                    $profession[] = getUserTypeValue($user->user_type);
                                @endphp
                            @endif
                        @else
                            @if(isset($user->other_professions) && !empty($user->other_professions))
                                @php
                                    $profession=[];
                                @endphp
                                @foreach(json_decode($user->other_professions) as $key=>$prof)
                                    @php
                                        $profession[] = $prof;
                                    @endphp
                                @endforeach
                            @endif
                            @php
                                $profession[] = $user->crew_type;
                            @endphp
                        @endif
                        @php
                            sort($profession);
                        @endphp
                        <span><i>{{ join(' & ', array_filter(array_merge(array(join(', ', array_slice(array_unique($profession), 0, -1))), array_slice(array_unique($profession), -1)), 'strlen')) }}</i></span>
                    </h2>
                </div>
                <div class="profile-wrap actor">
                    <div class="grid">
                        <div class="img-bio">
                            <div class="img-wrap">
                                @if(isset($user->profile_picture) && $user->profile_picture!='' && file_exists(public_path('img/profile_picture/').$user->profile_picture))
                                    <img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="profile-pic" id="profile_img">
                                @else
                                    <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
                                @endif
                            </div>
                        </div>
                        @if(isset($user->caption) && $user->caption!='')
                            <div class="self-bio">
                                <p>"{!! $user->caption  !!}"</p>
                            </div>
                        @endif
                        @if(isset($user->cv) && $user->cv!='')
                            <div class="skill-wrap">
                                <a href="{{ asset('public/cv/'.$user->cv.'') }}" alt="cv" id="sc" target="_blank" style="color: #606060;"><b>Download CV</b> <i
                                        class="fas fa-download"></i></a>
                            </div>
                        @endif
                        <div class="self-detail">
                            <div class="type">Gender:
                                <span class="ans">
								@if(isset($user->gender) && !empty($user->gender))
                                    @if($user->gender=='no_to_ans')
                                        Prefer not to answer
                                    @else
                                        {{ ucfirst($user->gender) }}
                                    @endif
                                @else
                                    N/A
                                @endif
							</span>
                            </div>
                            @if($user->user_type!=4)
                            <div class="type">Age:
                                <span class="ans">
								@if(isset($user->playing_age) && !empty($user->playing_age)){{ $user->playing_age }}
                                    @else N/A @endif
							</span>
                            </div>
                            @endif
                            <div class="type">Eye colour:
                                <span class="ans">
								@if(isset($user->eye_colour) && !empty($user->eye_colour)){{ ucfirst($user->eye_colour) }}
                                    @else N/A @endif
							</span>
                            </div>
                            <div class="type">Height:
                                <span class="ans">
								@if(isset($user->height) && !empty($user->height)){{ $user->height }}
                                    @else N/A @endif
							</span>
                            </div>
                            <div class="type">Genre:
                                <span class="ans">
								@if(isset($user->genre) && $user->genre!='') {{ implode(', ', json_decode($user->genre)) }}
                                    @else N/A  @endif
							</span>
                            </div>
                            @if($user->user_type!=4)
                            <div class="type">Role types:
                                <span class="ans">
								@if(isset($user->role_type) && $user->role_type!='') {{ implode(', ', json_decode($user->role_type)) }}
                                    @else N/A  @endif
							</span>
                            </div>
                            @endif
                            <div class="type">Language:
                                <span class="ans">
								@if(isset($user->languages) && $user->languages!='')
                                        {{ implode(', ', json_decode($user->languages)) }}
                                    @else N/A  @endif
							</span>
                            </div>
                            @if($user->user_type==1 || $user->user_type==2)
                            <div class="type">Accents:
                                <span class="ans">
								@if(isset($user->acents) && $user->acents!='')
                                        {{ implode(', ', json_decode($user->acents)) }}
                                    @else N/A  @endif
							</span>
                            </div>
                            @endif
                            <div class="type">Skills:
                                <span class="ans">
								@if(isset($user->skills) && $user->skills!='')
                                        {{ implode(', ', json_decode($user->skills)) }}
                                    @else N/A  @endif
							</span>
                            </div>
                        </div>
                    </div>

                    <div class="network-friend">
                        <h3>My Network</h3>
                        <ul>
                            @foreach($user->networkFriends() as $friend)
                                <li>
                                    <a href="{{ route('profile-details',str_replace(' ', '-', strtolower($friend->username))) }}">
                                        @if(isset($friend->profile_picture) && $friend->profile_picture!='' && file_exists(public_path('img/profile_picture/').$friend->profile_picture))
                                            <img src="{{ asset('public/img/profile_picture/'.$friend->profile_picture.'') }}" alt="profile-pic">
                                        @else
                                            <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic">
                                        @endif
                                        <div>{{ $friend->first_name }} {{ $friend->last_name }}</div>
                                    </a>
                                </li>

                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="feed-wrap">
                    @if(Auth::user() && Auth::id()==$user->id)
                        <div class="create-post-wrap">
                            <h2>Create Post</h2>
                            <div class="write-post">
                                <div class="pro_img">
                                    @if(isset($user->profile_picture) && $user->profile_picture!='')
                                        <img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="profile-pic" id="profile_img">
                                    @else
                                        <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
                                    @endif
                                </div>
                                <form method="post" id="upload_status_form" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <textarea placeholder="Write something here..." name="feed" na id="user_status"></textarea>
                                    <div id="post_image_div"></div>
                                    <label class="custom-file">
                                        <input type="file" name="user_feed_image" id="user_feed_image" class="custom-file-input">
                                        <span class="custom-file-control">Choose file</span>
                                    </label>
                                    <div class="custom-container status-error" style="color:red;"></div>
                                    <div class="btn-wrap">
                                        <button type="submit" class="btn" id="status_update_btn">Post</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                    <div id="user_feed">
                        @include('user.feed_part')
                    </div>
                    <div id="empty-feed" style="text-align: center;margin-top: 20px;"></div>
                </div>
            </div>
        </div>

        <div class="modal fade profile-contact-form" id="editPostCenter" tabindex="-1" role="dialog" aria-labelledby="editPostCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostCenterTitle">Edit Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-errors"></div>
                        <form id="status_form" method="post">
                            <div class="custom-container status-edit-error" style="color:red;"></div>
                            <input type="hidden" id="feed_id" name="feed_id" value="{{ $user->id }}">
                            <div class="form-item">
                                <textarea id="feed_message" name="feed" placeholder="Write something here..."></textarea>
                            </div>
                            <div id="image_div"></div>
                            <br/>
                            <div class="form-action">
                                <button class="btn" type="button" id="status_edit_btn">Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade show" id="deletePostModal">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Delete Post</h4>
                    </div>
                    <div class="modal-body">
                        Are you sure you want delete this post ?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-small cancle-membership" data-dismiss="modal">Go Back</button>
                        <button type="button" class="btn btn-small cancle-membership" id="delete-post-btn">Yes I’m Sure</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // global app configuration object
            var config = {
                routes: {
                    like_route: '{{ route('like.post') }}',
                    dis_like_route: '{{ route('dis.like.post') }}',
                    comment_route: '{{ route('post.comment') }}',
                    delete_post_route: '{{ route('delete.post') }}',
                    delete_post_comment_route: '{{ route('delete.post.comment') }}',
                    get_post_route: '{{ route('get.post') }}',
                    update_post_route: '{{ route('update.post') }}',
                    feed_image_route: '{{ asset('public/img/feed_images/') }}/',
                }
            };
        </script>
        <script src="{{ asset('public/front/js/feed.js?v=5.0.0') }}"></script>
        <script>
            $('#upload_status_form').on('submit', function (event) {
                event.preventDefault();
                $('.status-error').html('');
                $('#loading').show();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('add.user.status') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    data: new FormData(this),
                })
                    .done(function (res) {
                        $('#loading').hide();
                        if (res.status == false) {
                            var errorString = '<ul>';
                            $.each(res.msg, function (key, value) {
                                errorString += '<li>' + value + '</li>';
                            });
                            errorString += '</ul>';
                            $('.status-error').html('');
                            $('.status-error').html('<div class="alert alert-danger">' + errorString + '</div>');
                            $('#user_status').focus();

                        } else {
                            if (res.feed.feed) {
                                var imagefeed = res.feed.feed;
                            } else {
                                var imagefeed = '';
                            }
                            if (res.image != '') {
                                var img = '<img src="{{ asset('public/img/feed_images') }}/' + res.image + '" alt="profile-pic" >'
                            } else {
                                var img = '';
                            }
                            var feed = '<div class="feed-post" id="feed_div_' + res.feed.id + '">'
                                + '<div class="pro-detail">'
                                + '<div class="pro_img">'
                                @if(isset($user->profile_picture) && $user->profile_picture!='')
                                + '<img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="profile-pic" id="profile_img">'
                                @else
                                + '<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">'
                                @endif
                                + '</div>'
                                + '<div class="pro_name">'
                                + '<h3>{{ $user->first_name }} {{ $user->last_name }}</h3>'
                                + '<span class="desig">{{ getUserTypeValue($user->user_type) }}</span>'
                                + '<span class="date-time">1s</span>'
                                + '</div>'
                                + '<div class="open-action-box" onclick="openActionBox(' + res.feed.id + ')">'
                                + '<span>···</span>'
                                + '<div class="action-box action-box_' + res.feed.id + '" style="display: none;">'
                                + '<a href="javascript:;" onclick="editPost(' + res.feed.id + ')">Edit</a>'
                                + '<a href="javascript:;" onclick="deletePostModal(' + res.feed.id + ')">Delete</a>'
                                + '</div>'
                                + '</div>'
                                + '</div>'
                                + '<div class="post_wrap">'
                                + '<p id="feed_p_' + res.feed.id + '">' + imagefeed + '</p>'
                                + img
                                + '</div>'
                                + '<div class="like_here">'
                                + '<div class="like-comment">'
                                + '<span class="icon like_icon_' + res.feed.id + '" onclick="likePost(' + res.feed.id + ')"><i class="far fa-heart"></i> '
                                + '</span>'
                                + '<span class="like_count_' + res.feed.id + '">0</span>'
                                + '</div>'
                                + '<div class="like-comment">'
                                + '<span class="icon comment_icon_' + res.feed.id + '" onclick="openCommentBox(' + res.feed.id + ')">'
                                + '<i class="far fa-comment-dots"></i> '
                                + '</span>'
                                + '<span class="comment_count_' + res.feed.id + '">0</span>'
                                + '</div>'
                                + '<div class="comment-box_' + res.feed.id + '" style="display: none;margin-top: 10px;">'
                                + '<div class="comment-wrapper">'
                                + '<div class="panel panel-info">'
                                + '<div class="panel-body">'
                                + '<ul class="media-list_' + res.feed.id + '">'
                                + '</ul>'
                                + '</div>'
                                + '</div>'
                                + '</div>'
                                + '<textarea placeholder="Write a comment..." id="comment_' + res.feed.id + '" class=""></textarea>'
                                + '<div class="comment-error_' + res.feed.id + '" style="color:red;margin-bottom: 10px;"></div>'
                                + '<div class="btn-wrap">'
                                + '<button class="btn" id="comment_btn" onclick="postComment(' + res.feed.id + ')">Comment</button>'
                                + '</div>'
                                + '</div>'
                                + '</div>'
                                + '</div>';
                            $('#user_feed').prepend(feed);
                            $('#user_status').val('');
                            $('#no-fedd').html('');
                            $('#post_image_div').html('');
                        }
                    });
            });
        </script>
    </div>
    @include('include.footer')
@endsection
