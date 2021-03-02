<ul class="timeline timeline-inverse">
	@foreach($explorerequest as $key => $detail)
	<li>
		<i class="fa fa-user bg-aqua"></i>
		<div class="timeline-item">
			<div class="timeline-body">
				 {!! $detail->explore->title !!}
			</div>
			<div class="timeline-body">
				 {!! $detail->explore->description !!}
			</div>
			<div class="timeline-footer">
				CV - <a target="_blank" href="{{ url('/') }}/public/documents/explore_cv/{{ $detail->cv }}" class="btn btn-warning btn-flat btn-xs">{!! $detail->cv !!}</a>
			</div>
		</div>
	</li>
	@endforeach
</ul>