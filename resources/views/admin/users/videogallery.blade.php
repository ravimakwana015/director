<div class="timeline-item">
	<div class="timeline-body">
		@if(isset($user->videoGallery) && count($user->videoGallery))
		@foreach($user->videoGallery as $vkey=>$video)
		
		<a href="{{ asset('public/img/video_gallery/'.$video->video.'') }}" data-fancybox="video-preview">
			<video width="200" controls>
				<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/mp4">
					<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/ogg">
						Your browser does not support HTML5 video.
					</video>
				</a>
				
				@endforeach
				@endif
			</div>
		</div>