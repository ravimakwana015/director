@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Add New User
		</h1>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Add New User</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					{{ Form::open(['route' => 'users.store','role' => 'form', 'method' => 'post', 'id' => 'create-cate', 'files' => true]) }}
					@include("admin.users.form")
					<div class="box-body">
						<div class="form-group">
							<button class="btn btn-success">Add New</button>
						</div>
					</div>
					{!! Form::close() !!}
				</div>
				<div class="box-footer"></div>
			</div>
		</section>
	</div>
	@endsection
@section('after-scripts')
<script>
	var resize = $('#upload-demo').croppie({
		enableExif: true,
					// enableOrientation: false,    
					viewport: { 
						width: 500,
						height: 500,
						type: 'square'
					},
					boundary: {
						width: 600,
						height: 600
					}
				});

	$('#profile_picture-1').on('change', function () {
		var reader = new FileReader();
		reader.onload = function (e) {
			resize.croppie('bind',{
				url: e.target.result
			}).then(function(){
				$('.profile-image-preview').addClass('active');
				$('body').addClass('profile-popup');
			});
		}
		reader.readAsDataURL(this.files[0]);
	});

	$('#close_image_crop').click(function(event) {
		$('.profile-image-preview').removeClass('active');
		$('body').removeClass('profile-popup');
		$('#profile_picture-1').val('');
	});

	$('.upload-image').on('click', function (ev) {
		resize.croppie('result', {
			type: 'canvas',
			size: 'viewport'
		}).then(function (img) {
			$('#loading').show();
			$.ajax({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				url: "{{ route('upload-career-icon') }}",
				type: "POST",
				data: {"image":img},
				success: function (data) {
					$('#loading').hide();
					var path ='{{ asset('public/img/career_icon/') }}';
					$('#career_img').attr('src', path+'/'+data);
					$('#icon_img').val(data);
					$('.profile-image-preview').removeClass('active');
					$('body').removeClass('profile-popup');
				}
			});
		});
	});
</script>
@endsection