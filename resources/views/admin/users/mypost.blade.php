<ul class="timeline timeline-inverse">
	@foreach($feeds as $key => $UserFeedValue)
	<li>
		<i class="fa fa-comments bg-yellow"></i>
		<div class="timeline-item">
			<div class="timeline-body">
				@if(isset($UserFeedValue->properties) && $UserFeedValue->properties!='')
				@php
				$img=json_decode($UserFeedValue->properties);
				@endphp

				@if(isset($img->image))
				<img src="{{ asset('public/img/feed_images/'.$img->image.'') }}" alt="profile-pic" id="profile_img" height="150" width="150px"> - {!! $UserFeedValue->created_at !!}
				@elseif(isset($img->profile_pic))
				<img src="{{ asset('public/img/profile_picture/'.$img->profile_pic.'') }}" alt="profile-pic" id="profile_img" height="150" width="150px"> - {!! $UserFeedValue->created_at !!}
				@elseif(isset($img->imagegallery))
				@foreach($img as $key=>$gallery)
				<div class="row">
					@foreach($gallery as $key=>$image)
					<div class="col-sm-4 grid">
						<img src="{{ asset('public/img/user_gallery/'.$image.'') }}" height="150" width="150px"> <div class="date">- {!! $UserFeedValue->created_at !!}</div>
					</div>
					@endforeach
				</div>
				@endforeach
				@elseif(isset($img->video))
				<video  controls height="250px" width="400px"><source src="{{ asset('public/img/video_gallery/'.$img->video.'') }}" type="video/mp4" /><source src="{{ asset('public/img/video_gallery/'.$img->video.'') }}" type="video/ogg" />Your browser does not support HTML5 video.</video> - {!! $UserFeedValue->created_at !!}
					@elseif(isset($img->forum_topic))
					Add new Forum Topic <a href="{!! route('topic',$img->forum_topic->id) !!}">Take a look</a> - {!! $UserFeedValue->created_at !!}
					@elseif(isset($img->comment))
					Left a comment on Forum Topic <a href="{!! route('topic',$img->comment->topic_id) !!}">Take a look</a> - {!! $UserFeedValue->created_at !!}
					@elseif(isset($img->enter))
					@php
					$parm=str_replace(' ', '-', strtolower($img->enter->enter));
					@endphp
					Applied to a Competition on Enter. <a href="{!! route('discover.form', $parm)!!}" title="{!! $img->enter->enter !!}">Take a look</a> - {!! $UserFeedValue->created_at !!}
					@elseif(isset($img->develop))
					@php
					$parm=str_replace(' ', '-', strtolower($img->develop->develop));
					@endphp
					Sent an Enquiry <a href="{!! route('explore.form', $parm)!!}" title="{!! $img->develop->develop !!}">Take a look</a> - {!! $UserFeedValue->created_at !!}
					@endif
					@else

					{!! $UserFeedValue->feed !!} - {!! $UserFeedValue->created_at !!}
					@endif
				</div>
				@foreach($UserFeedValue->postcomment as $key => $viewcomment)
                 @if(isset($viewcomment->commentOwner))
				<div class="timeline-footer">
					{!! $viewcomment->commentOwner->first_name !!} {!! $viewcomment->commentOwner->last_name !!} -
					<a class="btn btn-warning btn-flat btn-xs">{!! $viewcomment->comment !!}</a> - {!! $viewcomment->created_at !!}
				</div>
                @endif
				@endforeach
			</div>
		</li>
		@endforeach
	</ul>
