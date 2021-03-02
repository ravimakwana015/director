@extends('layouts.app')

@section('content')
@include('include.header')
<link rel="stylesheet" href="{{ asset('public/front/css/select2.min.css?v=4.0.0') }}">
<link rel="stylesheet" href="{{ asset('public/front/css/croppie.min.css?v=4.0.0') }}">
<div class="profile-image-preview">
	<div class="content">
		<div class="image-header">
			<h4 class="title">Upload Image</h4>
			<button type="button" id="close_image_crop" class="close">&times;</button>
		</div>
		<div class="image-wrap">
			<div id="upload-demo"></div>
			<div id="preview-crop-image"></div>
		</div>
		<div class="image-footer">
			<button class="btn btn-primary upload-image">Upload Image</button>
		</div>
	</div>
</div>

<div class="main edit-profile-page">
	<div class="custom-container">
		<div class="name-header">
			<h2>Edit Profile</h2>
		</div>
		@include('admin.include.message')
		<div id="error-msg"></div>
		<div class="profile-wrap actor">
			<ul class="nav nav-tabs">
				<li>
					<a href="{{ route('profile') }}" id="profile_add" class="btn">Profile</a>
				</li>
				<li>
					<a href="{{ route('photo_gallery') }}" id="photo_gallery_adds" class="btn">Photo Gallery</a>
				</li>
				<li  class="active">
					<a href="{{ route('video_gallery') }}" class="btn">Video Gallery</a>
				</li>
			</ul>

			<div class="tab-content">
				<div id="video_gallery" class="tab-pane fade in active show">
					<div class="row">
						<div class="col-md-8  personal-info">
							<form role="form" action="{{ route('upload-videos') }}" method="post" enctype="multipart/form-data">
								@csrf
								<h3>Video Gallery</h3>
								<hr />
								<div class="form-group row">
									<h6>Upload Video</h6>
									<label class="custom-file">
										<input type="file" name="video" accept="video/mp4" class="custom-file-input" id="add-video">
										<span class="custom-file-control">Choose file</span>
									</label>
								</div>
								<div id="v-errro"></div>
							</form>
						</div>
					</div>
					<div class="row">
						@foreach(Auth::user()->videoGallery as $video)
						<div class="col-md-3 user-video" id="video_grid_{{ $video->id }}">
							<a href="{{ asset('public/img/video_gallery/'.$video->video.'') }}" data-fancybox="video-preview">
								<video width="400" controls>
									<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/mp4">
										<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/ogg">
											Your browser does not support HTML5 video.
										</video>
									</a>
									<span><i class="fa fa-trash" onclick="deleteVideo('{{ $video->id }}')"></i></span>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@include('include.footer')
		@endsection

		@section('after-scripts')
		<script src="{{ asset('public/front/js/thirdparty/select2.full.min.js?v=4.0.0') }}"></script>
		<script src="{{ asset('public/front/js/thirdparty/croppie.js?v=4.0.0') }}"></script>
		<script>
			var config = {
				routes: {
					upload_videos: '{{ route('upload-videos') }}',
					delete_video: '{{ route('delete-video') }}',
				}
			};
		</script>
		<script src="{{ asset('public/front/js/user-video-gallery.js?v=4.0.0') }}"></script>
		@endsection