@extends('admin.layouts.app')
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Settings
		</h1>
	</section>
	<section class="content">
		@include("admin.include.message")
		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Settings Update</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
				</div>
			</div>
			{!! Form::model($settings, ['route' => ['settings.update', $settings->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST', 'files' => true ,'id' => 'edit-settings']) !!}
			{{ method_field('POST') }}
			{{-- {{ Form::model($settings, ['route' => ['settings.update', $settings], 'class' => 'form-horizontal',
			'role' => 'form', 'files' => true, 'id' => 'edit-settings']) }} --}}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="nav-tabs-custom">
							<ul class="nav nav-tabs">
								<li class="active"><a href="#tab_3" data-toggle="tab">Site Settings</a></li>
								<li class=""><a href="#tab_4" data-toggle="tab">Home Slider Settings</a></li>
								<li class=""><a href="#tab_5" data-toggle="tab">Coupons</a></li>
{{--								<li class=""><a href="#tab_6" data-toggle="tab">Access Code</a></li>--}}
								<li class=""><a href="#tab_7" data-toggle="tab">Google Analytics</a></li>
							</ul>
							<div class="tab-content">
								<div class="tab-pane active" id="tab_3">

									<div class="form-group">
										{{ Form::label('contact_no','Contact Number', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('contact_no', null, ['class' => 'form-control', 'placeholder' => 'Contact Number'])}}
										</div>
									</div>

									<div class="form-group">
										{{ Form::label('email','Email Address', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email Address'])}}
										</div>
									</div>
									<div class="form-group">
										{{ Form::label('address','Address', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::textarea('address', null,['class' => 'form-control', 'placeholder' =>'Address',
											'rows' => 2]) }}
										</div>
										<!--col-lg-3-->
									</div>
									{{-- <div class="form-group">
										{{ Form::label('seo_title','SEO Title', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('seo_title', null, ['class' => 'form-control', 'placeholder' => 'SEO Title'])}}
										</div>
									</div>
									<div class="form-group">
										{{ Form::label('seo_keyword','SEO Keyword', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::textarea('seo_keyword', null,['class' => 'form-control', 'placeholder' =>'SEO Keyword',
											'rows' => 2]) }}
										</div>
										<!--col-lg-3-->
									</div>
									<!--form control-->
									<div class="form-group">
										{{ Form::label('seo_description','SEO Description', ['class' => 'col-lg-2
										control-label']) }}

										<div class="col-lg-10">
											{{ Form::textarea('seo_description', null,['class' => 'form-control', 'placeholder' =>'SEO Description',
											'rows' => 2]) }}
										</div>
										<!--col-lg-3-->
									</div>
									<!--form control-->
									<div class="form-group">
										{{ Form::label('web_analytics','Web Analytics', ['class' => 'col-lg-2 control-label'])}}
										<div class="col-lg-10">
											{{ Form::textarea('web_analytics', null,['class' => 'form-control', 'placeholder' => 'Web Analytics',
											'rows' => 10,'cols'=>50]) }}
										</div>
									</div> --}}
								</div>
								<div class="tab-pane" id="tab_4">

									<div class="form-group">
										{{ Form::label('slider_tagline','Slider Tagline', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('slider_tagline', null, ['class' => 'form-control', 'placeholder' => 'Slider Tagline'])}}
										</div>
									</div>

									<div class="form-group">
										{{ Form::label('image', 'Slider Image', ['class' => 'col-lg-2 control-label']) }}
										<div class="col-md-10">
											{{-- {{ Form::label('image', '', ['class' => 'control-label required']) }} --}}
											@if(!empty($settings->default_image))
											<input type="file" name="default_image" id="default_image-1" class="inputfile inputfile-1"  />
											<br/>
											<img src="{{ asset('/public/img/logo/' . $settings->default_image) }}" width="80" height="80">
											@else
											<input type="file" name="default_image" id="default_image-1" class="inputfile inputfile-1"  />
											@endif
											@if ($errors->has('default_image'))
											<span class="text-danger">{{ $errors->first('default_image') }}</span>
											@endif
										</div>
									</div>
								</div>
								<div class="tab-pane" id="tab_5">

									<div class="form-group">
										{{ Form::label('discount_coupon','Discount Coupon', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('discount_coupon', null, ['class' => 'form-control', 'placeholder' => 'Discount Coupon'])}}
										</div>
									</div>
                                    <div class="form-group">
										{{ Form::label('free_trail_code','Free Trial Coupon', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::text('free_trail_code', null, ['class' => 'form-control', 'placeholder' => 'Free Trial Coupon'])}}
										</div>
									</div>

								</div>
{{--								<div class="tab-pane" id="tab_6">--}}

{{--									<div class="form-group">--}}
{{--										{{ Form::label('access_coupon','Access Code', ['class' => 'col-lg-2 control-label'])}}--}}

{{--										<div class="col-lg-10">--}}
{{--											{{ Form::text('access_coupon', null, ['class' => 'form-control', 'placeholder' => 'Access Code'])}}--}}
{{--										</div>--}}
{{--									</div>--}}

{{--								</div>--}}
                                <div class="tab-pane" id="tab_7">
									<div class="form-group">
										{{ Form::label('web_analytics','Google Analytics', ['class' => 'col-lg-2 control-label'])}}

										<div class="col-lg-10">
											{{ Form::textarea('web_analytics', null, ['class' => 'form-control', 'placeholder' => ' Google Analytics'])}}
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="box-footer">
				<div class="row">
					<div class="col-lg-2 col-lg-10 footer-btn">
						{{ Form::submit('Update', ['class' => 'btn btn-primary btn-md']) }}
						<div class="clearfix"></div>
					</div>
				</div>
			</div>

			{{ Form::close() }}
		</div>
	</section>
</div>
@endsection
@section('after-scripts')
<script>
	tinymce.init({
		selector:'textarea[id="address"]',
		// width: 900,
		height: 300
	});
</script>
@endsection
