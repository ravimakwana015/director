<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<table class="table table-bordered">
						<tbody><tr>
							<th style="width: 10px">#</th>
							<th style="width: 60px">Name</th>
							<th style="width: 20px">Email</th>
							<th style="width: 20px">Message</th>
							<th style="width: 20px">Access Code</th>
						</tr>
						@php
						$i = 1;	
						@endphp
						@foreach($invite as $inviteValue)
						<tr>
							<td>{{ $i }}</td>
							<td>@if(isset($inviteValue->name) && $inviteValue->name!='') {{ $inviteValue->name }} 
							@else N/A  @endif</td>
							<td>@if(isset($inviteValue->email) && $inviteValue->email!='') {{ $inviteValue->email }} 
							@else N/A  @endif</td>
							<td>@if(isset($inviteValue->message) && $inviteValue->message!='') {{ $inviteValue->message }} 
							@else N/A  @endif</td>
							<td>@if(isset($inviteValue->token) && $inviteValue->token!='') {{ $inviteValue->token }} 
							@else N/A  @endif</td>
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