@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Add New Page
		</h1>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Add New Page</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
				</div>
			</div>
			<div class="box-body">
				{{ Form::open(['route' => 'pages.store','role' => 'form', 'method' => 'post', 'id' => 'pages']) }}
				@include("admin.pages.form")
				<div class="box-body">
					<div class="form-group">
						<button class="btn btn-success">Submit</button>
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
      // toolbar: "undo redo | style-p style-h1 style-h2 style-h3 style-pre style-code",
      plugins: 'print preview fullpage powerpaste casechange importcss tinydrive searchreplace autolink autosave save directionality advcode visualblocks visualchars fullscreen image link media mediaembed template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists checklist wordcount tinymcespellchecker a11ychecker imagetools textpattern noneditable help formatpainter permanentpen pageembed charmap tinycomments mentions quickbars linkchecker emoticons code',
      relative_urls: false,
      image_dimensions: false,
      setup : function(ed)
      {
      	ed.on('init', function() 
      	{
      		this.getDoc().body.style.fontFamily = 'Calluna-Regular';
      	});
      }

    });



  </script>
  @endsection