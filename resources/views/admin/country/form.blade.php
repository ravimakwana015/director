<div class="box-body">
	<div class="form-group">
		{{ Form::label('name','Title', ['class' => 'control-label required']) }}
		{{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Country Name']) }}
		@if ($errors->has('name'))
		<span class="text-danger">{{ $errors->first('name') }}</span>
		@endif
	</div>
</div>