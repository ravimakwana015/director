<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box">
				<div class="box-body">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th style="width: 10px">#</th>
								<th style="width: 60px">My Friends</th>
							</tr>
							@php
							$i = 1;
							@endphp
							@foreach($user->networkFriends() as $friendsValue)
							<tr>
								<td>{{ $i }}</td>
								<td> {{ $friendsValue->first_name }} {{ $friendsValue->last_name }} 
								</td>
							</tr>
							@php
							$i++;	
							@endphp
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>