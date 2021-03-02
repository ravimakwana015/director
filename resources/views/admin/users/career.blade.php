<ul class="timeline timeline-inverse">
	@foreach($career as $key => $detail)
	<li>
		<i class="fa fa-user bg-aqua"></i>
		<div class="timeline-item">
			<div class="timeline-body">
				 {!! $detail->career->title !!}
			</div>
			<div class="timeline-body">
				 {!! $detail->career->description !!}
			</div>
			<div class="timeline-footer">
				CV - <a target="_blank" href="{{ url('/') }}/public/documents/career_cv/{{ $detail->cv }}" class="btn btn-warning btn-flat btn-xs">{!! $detail->cv !!}</a>
			</div>
		</div>
	</li>
	@endforeach
</ul>