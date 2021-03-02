@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Forum Category
		</h1>
	</section>
	<section class="content">
		{{-- @include("admin.include.message") --}}
		<div class="box box-success">
			<div class="box-header with-border ">
				<h3 class="box-title">Forum Category Update</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
						<i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					{!! Form::model($forumcategory, ['route' => ['forumcategory.update', $forumcategory->id], 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
					@include("admin.forumcategory.form")
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
