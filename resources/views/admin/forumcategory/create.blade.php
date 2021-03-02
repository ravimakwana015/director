@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Add New Forum Category
		</h1>
	</section>
	<section class="content">
		<div class="box box-info">
			<div class="box-header with-border ">
				<h3 class="box-title">Add New Forum Category</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					</div>
				</div>
				<div class="box-body">
					{{ Form::open(['route' => 'forumcategory.store','role' => 'form', 'method' => 'post', 'id' => 'create-category']) }}
					@include("admin.forumcategory.form")
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
