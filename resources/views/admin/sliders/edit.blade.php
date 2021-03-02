@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Slider
		</h1>
	</section>
	<section class="content">
		
		<div class="box box-success">
			<div class="box-header with-border ">
				<h3 class="box-title">Slider Update</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
						<i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					@include("admin.include.message")
					{!! Form::model($sliders, ['route' => ['sliders.update', $sliders->id], 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
					@include("admin.sliders.form")
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
