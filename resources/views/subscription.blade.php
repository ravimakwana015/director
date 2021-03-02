@extends('layouts.app')

@section('content')
<div class="main discount-page">
	<div class="custom-container">
		<div class="discount-wrap">
			@foreach($plans->data as $plan)
			@if(plan($plan->id)->status==1 && $plan->interval=='month' && $plan->interval_count==1)
			<div class="grid monthly">
				<div class="shape-wrap">
					<div class="heading">
						<h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
					</div>
					<div class="body">
						<div class="price-wrap">
							<span class="currency-symbol">£</span>
							<span class="price">{{ number_format($plan->amount/100, 2) }}</span>
							<span class="mo">/mo</span>
						</div>
						<h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
						<h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
					</div>
				</div>
				<div class="white-space">
					<p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
					<div class="try-wrap">
						<a href="{{ route('get.plans',$plan->id) }}">Subscribe <i class="fas fa-chevron-right"></i>
						</a>
					</div>
				</div>
			</div>
			@endif
			@endforeach
			@foreach($plans->data as $plan)
			@if(plan($plan->id)->status==1 && $plan->interval=='year')
			<div class="grid yearly most-popular">
				<div class="shape-wrap">
					<div class="corner-ribbon top-right sticky blue">Most Popular</div>
					<div class="heading">
						<h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
					</div>
					<div class="body">
						<div class="price-wrap">
							<span class="currency-symbol">£</span>
							<span class="price">{{ number_format($plan->amount/100, 2) }}</span>
							<span class="mo">/Year</span>
						</div>
						<h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
						<h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
					</div>
				</div>
				<div class="white-space">
					<p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
					<div class="try-wrap">
						<a href="{{ route('get.plans',$plan->id) }}">Subscribe <i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
			@endif
			@endforeach
			@foreach($plans->data as $plan)
			@if(plan($plan->id)->status==1 && $plan->interval=='month' && $plan->interval_count==3)
			<div class="grid quarterly">
				<div class="shape-wrap">
					<div class="heading">
						<h2>@if(isset(plan($plan->id)->name)){{ plan($plan->id)->name }} @endif</h2>
					</div>
					<div class="body">
						<div class="price-wrap">
							<span class="currency-symbol">£</span>
							<span class="price">{{ number_format($plan->amount/100, 2) }}</span>
							<span class="mo">/mo</span>
						</div>
						<h2 class="btn-wrap">@if(isset(plan($plan->id)->short_description)){!! trim(plan($plan->id)->short_description) !!}@endif</h2>
						<h3 class="billing">£{{ number_format($plan->amount/100, 2) }} billed on purchase</h3>
					</div>
				</div>
				<div class="white-space">
					<p>@if(isset(plan($plan->id)->description)){!! plan($plan->id)->description !!}@endif</p>
					<div class="try-wrap">
						<a href="{{ route('get.plans',$plan->id) }}">Subscribe <i class="fas fa-chevron-right"></i></a>
					</div>
				</div>
			</div>
			@endif
			@endforeach
		</div>
	</div>
</div>
<script type="text/javascript">
	history.pushState(null, null, location.href);
	window.onpopstate = function () {
		history.go(1);
	};
</script>
@endsection