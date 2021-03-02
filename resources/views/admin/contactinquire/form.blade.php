<div class="box-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('category','Forum Category', ['class' => 'control-label required']) }}
				{{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Forum Category']) }}
				@if ($errors->has('title'))
				<span class="text-danger">{{ $errors->first('title') }}</span>
				@endif
			</div>
		</div>

		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
				<br/>
				{{ Form::radio('status', 1, true) }} Active
				{{ Form::radio('status',0, false) }} In Active
			</div>
		</div>
	</div>
</div>