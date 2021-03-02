@extends('admin.layouts.app') 
@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>
			Subscription Plans
		</h1>
	</section>
	<section class="content">
		{{-- @include("admin.include.message") --}}
		<div class="box box-success">
			<div class="box-header with-border ">
				<h3 class="box-title">Subscription Plans Update (You cannot change a plan’s ID, amount, currency, or billing cycle.)</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
					title="Collapse">
					<i class="fa fa-minus"></i></button>
					<button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
						<i class="fa fa-times"></i></button>
					</div>
				</div>
				<div class="box-body">
					{!! Form::model($plans, ['route' => ['plans.update', $plans->id], 'role' => 'form', 'method' => 'PATCH', 'files' => true]) !!}
					@include("admin.plans.form")
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
			selector:'textarea[id="plan_description"]',
		// width: 900,
		height: 300
	});
</script>
@endsection