@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Edit Pages
		</h1>
	</section>
	<section class="content">
		{{-- @include("admin.include.message") --}}
		<div class="box box-success">
			<div class="box-header with-border ">
				<h3 class="box-title">Page Update</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
						<i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					{!! Form::model($pages, ['route' => ['pages.update', $pages->id], 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
					@include("admin.pages.form")
					<div class="box-body">
						<div class="form-group">
							<button class="btn btn-success">Update</button>
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
		tinymce.init({
    selector: 'textarea[id="page_description"]',  // change this value according to your HTML
    plugins: 'print preview fullpage powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons code',
    relative_urls: false,
    image_dimensions: false

});
</script>
@endsection