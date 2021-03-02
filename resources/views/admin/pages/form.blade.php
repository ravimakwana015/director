<div class="box-body">
	<div class="row">
		<div class="col-md-11">
			<div class="form-group">
				{{ Form::label('Page Title','Page Title', ['class' => 'control-label required']) }}
				{{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Page Title']) }}
				@if ($errors->has('title'))
				<span class="text-danger">{{ $errors->first('title') }}</span>
				@endif
			</div>
		</div>
	</div>
    <div class="row">
		<div class="col-md-11">
			<div class="form-group">
				{{ Form::label('Page Title','Display Contact Form', ['class' => 'control-label']) }}
                {!! Form::checkbox('form','1', null) !!}
				@if ($errors->has('form'))
				<span class="text-danger">{{ $errors->first('form') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('Content',"Content", ['class' => 'control-label required']) }}
				{{ Form::textarea('content', null, ['class' => 'form-control', 'placeholder' => "content",'id'=>'page_description']) }}
				@if ($errors->has('content'))
				<span class="text-danger">{{ $errors->first('content') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-11">
			<div class="form-group">
				{{ Form::label('status', 'Select Page Status', ['class' => 'control-label']) }}
				{{ Form::select('status', [""=>"Select Page Status","1"=>"Active","0"=>"InActive","2"=>"Draft"],null, ['class' => 'form-control']) }}
				@if ($errors->has('status'))
				<span class="text-danger">{{ $errors->first('status') }}</span>
				@endif
			</div>
		</div>
	</div>
</div>
