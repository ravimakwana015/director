<div class="box-body">
	<div class="row">
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('title','Slider Title', ['class' => 'control-label required']) }}
				{{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Slider Title']) }}
				{{-- @if ($errors->has('title'))
				<span class="text-danger">{{ $errors->first('title') }}</span>
				@endif --}}
			</div>
		</div>
		<div class="col-md-6">
			<div class="form-group">
				{{ Form::label('image', 'Slider Image', ['class' => 'control-label required']) }}
				@if(!empty($sliders->image))
				<input type="file" name="image" id="image-1" class="inputfile inputfile-1"  />
				<br/>
				<img src="{{ asset('/public/img/sliders/' . $sliders->image) }}" width="80" height="80">
				@else
				<input type="file" name="image" id="image-1" class="inputfile inputfile-1"  />
				@endif
				{{-- @if ($errors->has('image'))
				<span class="text-danger">{{ $errors->first('image') }}</span>
				@endif --}}
			</div>
		</div>
		{{-- <div class="col-md-12">
			<div class="form-group">
				{{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
				{{ Form::radio('status', 1, ['class' => 'form-control', 'placeholder' => 'Status']) }} Active
				{{ Form::radio('status',0, ['class' => 'form-control', 'placeholder' => 'Status']) }} In Active
				@if ($errors->has('status'))
				<span class="text-danger">{{ $errors->first('status') }}</span>
				@endif
			</div>
		</div> --}}
	</div>
</div>