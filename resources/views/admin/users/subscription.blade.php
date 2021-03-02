<ul class="timeline timeline-inverse">
	<li>
		<i class="fa fa-user bg-aqua"></i>
		<div class="timeline-item">
			@if(isset($user->owner->stripe_id) && $user->owner->stripe_id!=$user->username)
			<div class="timeline-body">
				<h4>Active Membership</h4>
				@if(isset($plan))
				<span>{{ plan($plan->id)->name }} - (£{{ number_format($plan->amount/100, 2) }} every {{ $plan->interval_count }} {{ $plan->interval }})</span>
				@endif
			</div>
			<div class="timeline-body">
				<h4>Current Period End</h4>
				@if(isset($subscription))
				<span>{{ date('Y/m/d H:i A',$subscription->current_period_end) }}</span>
				@endif
			</div>
			<div class="timeline-body">
				<h4>Card Details</h4> 
				<span>{{ ucfirst($user->card_brand) }} </span>
			</div>
			<div class="timeline-body">
				<h4>{{ ucfirst($user->card_brand) }} Ending</h4>
				<span>{{ $user->card_last_four }}</span>
			</div>
			@endif
			@if(isset($customPlan))
			<div class="timeline-body">
				<span>{{ $customPlan->stripe_plan }} - (£0 every {{ $customPlan->stripe_plan }})</span>
			</div>
			@endif
			<div class="timeline-footer"></div>
		</div>
	</li>
</ul>