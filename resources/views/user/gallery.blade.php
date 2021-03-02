@extends('layouts.app')

@section('content')
@include('include.header')
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
				<li class="active">
					<a href="{{ route('photo_gallery') }}" id="photo_gallery_adds" class="btn">Photo Gallery</a>
				</li>
				<li>
					<a href="{{ route('video_gallery') }}" class="btn">Video Gallery</a>
				</li>
			</ul>

			<div class="tab-content">
				<div id="photo_gallery" class="tab-pane fade in active show">
					<div class="row">
						<div class="col-md-8  personal-info">
							<form role="form" action="{{ route('upload-photos') }}" method="post" enctype="multipart/form-data" id="myForm">
								@csrf
								<h3>Photo Gallery</h3>
								<hr />
								
								<div class="form-group row">
									<h6>Upload Images</h6>

									<label class="custom-file">
										<input type="file" name="photos" class="custom-file-input" id="uploadFile">
										<span class="custom-file-control">Choose file</span>
									</label>	
								</div>
								<div id="error" style="color: red;display: none;"></div>
							</form>
						</div>
					</div>
					<section id="gallery">
						<div id="image-gallery">
							<div class="row">
								@foreach(Auth::user()->gallery as $gallery)
								<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image" id="gallery_grid_{{ $gallery->id }}">
									<div class="img-wrapper">
										<a href="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}">
											<span class="delete-gallerys"><img src="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}" class="img-responsive"></a></span>
											<div class="img-overlay">
												<i class="fa fa-plus-circle" aria-hidden="true"></i>
											</div>
											<span class="delete-gallery"><i class="fa fa-trash" onclick="deleteImage('{{ $gallery->id }}')"></i></span>
										</div>
									</div>
									@endforeach
								</div>
							</div>
						</section>
					</div>
				</div>
			</div>
		</div>
	</div>
	@include('include.footer')
	@endsection

	@section('after-scripts')
	<script src="{{ asset('public/front/js/thirdparty/croppie.js?v=4.0.0') }}"></script>

	<script>
		var config = {
			routes: {
				upload_photos: '{{ route('upload-photos') }}',
				delete_photo: '{{ route('delete-photo') }}',
			}
		};
	</script>
	<script src="{{ asset('public/front/js/user-image-gallery.js?v=4.0.0') }}"></script>
	@endsection