@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Enter List SEO
		</h1>
	</section>
	<section class="content">
		@include("admin.include.message")
		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Enter List SEO Update</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
				</div>
			</div>
			
			{!! Form::model($discoverlistseo, ['route' => ['discoverlist.update', $discoverlistseo->id], 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'POST', 'files' => true]) !!}
			<div class="box-body">
				<div class="row">
					<div class="col-md-12">
						<div class="nav-tabs-custom">
							<div class="tab-content">
								<div class="tab-pane active" id="tab_3">
									<div class="form-group">
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