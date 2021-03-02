<div class="box-body">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('email','Email', ['class' => 'control-label required']) }}
				{{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email']) }}
				@if ($errors->has('email'))
				<span class="text-danger">{{ $errors->first('email') }}</span>
				@endif
			</div>
		</div>
	</div>
</div>