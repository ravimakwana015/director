<div class="box-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('name','Friend Name', ['class' => 'control-label required']) }}
				{{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Friend Name']) }}
				@if ($errors->has('name'))
				<span class="text-danger">{{ $errors->first('name') }}</span>
				@endif
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('email','Friend Email', ['class' => 'control-label required']) }}
				{{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Friend Email']) }}
				@if ($errors->has('email'))
				<span class="text-danger">{{ $errors->first('email') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('admin_refer_code','Refer Code', ['class' => 'control-label required']) }}
				{{ Form::text('admin_refer_code', null, ['class' => 'form-control', 'placeholder' => 'Refer Code']) }}
				@if ($errors->has('admin_refer_code'))
				<span class="text-danger">{{ $errors->first('admin_refer_code') }}</span>
				@endif
			</div>
		</div>
	</div>
</div>