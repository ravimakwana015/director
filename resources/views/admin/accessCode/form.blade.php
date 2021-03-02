<div class="box-body">
	<div class="row">
		<div class="col-md-11">
			<div class="form-group">
				{{ Form::label('Access Code','Access Code', ['class' => 'control-label required']) }}
				{{ Form::text('code', null, ['class' => 'form-control', 'placeholder' => 'Access Code']) }}
				@if ($errors->has('code'))
				<span class="text-danger">{{ $errors->first('code') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('Content',"Access Code Description", ['class' => 'control-label required']) }}
				{{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => "Access Code Description",'id'=>'page_description']) }}
				@if ($errors->has('description'))
				<span class="text-danger">{{ $errors->first('description') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-11">
			<div class="form-group">
                {{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
                <br/>
                {{ Form::radio('status', 1, true) }} Active
                {{ Form::radio('status',0,false) }} In Active
                @if ($errors->has('status'))
                    <span class="text-danger">{{ $errors->first('status') }}</span>
                @endif
            </div>
		</div>
	</div>
</div>
