<div class="shows">
	<div class="show">
		<div class="detail-wrap">
			<ul class="favourite-wrap">
				@foreach(json_decode($favourites) as $favourite)

				@if(isset($favourite->link) && $favourite->link !='' && isset($favourite->name) && $favourite->name !='')
				<li><a href="@if(isset($favourite->link)){{ $favourite->link }}@endif" target="_blank">@if(isset($favourite->name)){!! $favourite->name !!}@endif</a></li>
				@elseif(isset($favourite->name) && $favourite->name !='')
				<li>{!! $favourite->name !!}</li>
				@elseif(isset($favourite->link) && $favourite->link !='')
				<li><a href="{{ $favourite->link }}" target="_blank">{!! $favourite->link !!}</a></li>
				@endif
				@endforeach
			</ul>
		</div>
	</div>
</div>