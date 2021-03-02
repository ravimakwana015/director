{{-- <div class="timeline-item">
	<div class="timeline-body">
		@if(isset($user->gallery) && count($user->gallery))
		@foreach($user->gallery as $key=>$gallery)
		<a href="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}" class="fancybox" rel="group">
			<img src="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}" width="100"  />
		</a>
		@endforeach
		@endif
	</div>
</div> --}}
<section id="gallery">
	<div id="image-gallery">
		<div class="row">
			@if(isset($user->gallery) && count($user->gallery))
			@foreach($user->gallery as $key=>$gallery)
			<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
				<div class="img-wrapper">
					<a href="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}">
						<img src="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}" class="img-responsive">
					</a>
					<div class="img-overlay">
						<i class="fa fa-plus-circle" aria-hidden="true"></i>
					</div>
				</div>
			</div>
			@endforeach
			@endif
		</div>
	</div>
</section>