<div class="box-body">
	<div class="row">
		<div class="col-md-4">
			<div class="form-group">
				{{ Form::label('name','Plan Name', ['class' => 'control-label required']) }}
				@if(!isset($plans))	
				{{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Plan Name']) }}
				@if ($errors->has('name'))
				<span class="text-danger">{{ $errors->first('name') }}</span>
				@endif
				@else
				<br/>
				{{ $plans->name }}
				@endif
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{{ Form::label('interval', 'Select Plan Interval', ['class' => 'control-label']) }}
				@if(!isset($plans))
				{{ Form::select('interval', [""=>"Select Interval","day"=>"Daily","week"=>"Weekly","month"=>"Monthly","quarter"=>"Every 3 months","semiannual"=>"Every 6 months","year"=>"Yearly"],null, ['class' => 'form-control']) }}
				@if ($errors->has('interval'))
				<span class="text-danger">{{ $errors->first('interval') }}</span>
				@endif
				@else
				<br/>
					@if($plans->interval=='quarter')
					Every 3 months
					@elseif($plans->interval=='semiannual')
					Every 6 months
					@elseif($plans->interval=='month')
					Monthly
					@elseif($plans->interval=='day')
					Monthly
					@elseif($plans->interval=='week')
					Weekly
					@elseif($plans->interval=='year')
					Yearly
					@endif
				@endif
			</div>
		</div>
		<div class="col-md-3">
			<div class="form-group">
				{{ Form::label('amount','Plan Amount(GBP)', ['class' => 'control-label required']) }}
				@if(!isset($plans))
				{{ Form::number('amount', null, ['class' => 'form-control', 'placeholder' => 'Plan amount']) }}
				@if ($errors->has('amount'))
				<span class="text-danger">{{ $errors->first('amount') }}</span>
				@endif
				@else
				<br/>
				{{ $plans->amount }}
				@endif
			</div>
		</div>
		{{-- <div class="col-md-3">
			<div class="form-group">
				{{ Form::label('trial_period_days','Plan Trial Period Days', ['class' => 'control-label required']) }}
				{{ Form::number('trial_period_days', null, ['class' => 'form-control', 'placeholder' => 'Plan Trial Period Days']) }}
				@if ($errors->has('trial_period_days'))
				<span class="text-danger">{{ $errors->first('trial_period_days') }}</span>
				@endif
			</div>
		</div> --}}
		<div class="col-md-2">
			<div class="form-group">
				{{ Form::label('status', 'Status', ['class' =>'control-label required']) }}
				<br/>
				{{ Form::radio('status', 1,true) }} Active
				{{ Form::radio('status',0,false) }} In Active
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('short_description',"Short Description", ['class' => 'control-label required']) }}
				{{ Form::textarea('short_description', null, ['class' => 'form-control', 'placeholder' => "Short Description","id"=>"plan_short_description","rows"=>"2"]) }}
				@if ($errors->has('short_description'))
				<span class="text-danger">{{ $errors->first('short_description') }}</span>
				@endif
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				{{ Form::label('description',"Description", ['class' => 'control-label required']) }}
				{{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => "Description","id"=>"plan_description"]) }}
				@if ($errors->has('description'))
				<span class="text-danger">{{ $errors->first('description') }}</span>
				@endif
			</div>
		</div>
	</div>
</div>