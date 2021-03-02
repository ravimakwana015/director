<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<table class="table table-bordered">
						<tbody><tr>
							<th style="width: 10px">#</th>
							<th style="width: 60px">Plan Name</th>
							<th style="width: 20px">Amount</th>
							<th style="width: 20px">Payment Status</th>
						</tr>
						@php
						$i = 1;
						@endphp
						@foreach($transcation as $transcationValue)
						<tr>
							<td>{{ $i }}</td>
							<td>@if(isset($user->owner->stripe_plan) && $user->owner->stripe_plan!='') {{ $user->owner->stripe_plan }} 
							@else N/A  @endif</td>
							<td>
								@if(isset($transcationValue->amount) && $transcationValue->amount!='') {{ $transcationValue->amount }} 
								@else N/A  @endif
							</td>
							<td>@if($transcationValue->payment_status == 1)
								<span class="badge bg-green">Paid</span>
								@else
								<span class="badge bg-red">UnPaid</span>
							@endif</td>
						</tr>
						@php
						$i++;	
						@endphp
						@endforeach
					</tbody></table>
				</div>
				{{-- <div class="box-footer clearfix">
					<ul class="pagination pagination-sm no-margin pull-right">
						<li><a href="#">«</a></li>
						<li><a href="#">1</a></li>
						<li><a href="#">2</a></li>
						<li><a href="#">3</a></li>
						<li><a href="#">»</a></li>
					</ul>
				</div> --}}
			</div>
		</div>
	</div>
</section>