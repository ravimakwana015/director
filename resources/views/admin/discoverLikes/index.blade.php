 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Enter Likes List
 		</h1>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					<div class="box-header">
 						{{-- <form action="">
 							@foreach($discoverslikes as $discovervalue)
 							<input type="hidden" name="profile_id" value="{{ $discovervalue->id }}" id="profile_id">
 							@endforeach
 						</form> --}}
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="careers-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Competitions</th>
 									<th>Discover</th>
 									<th>Profile Name</th>
 									<th>Email</th>
 									<th>Created Date</th>
 									<th>Updated Date</th>
 									<th>Action</th>
 								</tr>
 							</thead>
 						</table>
 					</div>
 					<!-- /.box-body -->
 				</div>
 			</div>
 		</div>
 	</section>
 </div>
 @endsection

 @section('after-scripts')
 <script>
 	(function() {
 		var dataTable = $('#careers-table').dataTable({
 			processing: true,
 			serverSide: true,

 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{{ route("discoverlikes.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'competitions', name: 'competitions'},
 			{data: 'discover', name: 'discover'},
 			{data: 'profile_name', name: 'profile_name'},
 			{data: 'email', name: 'email'},
 			// {data: 'cover_letter', name: 'cover_letter'},
 			// {data: 'cv', name: 'cv'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			{data: 'actions', name: 'actions', searchable: false, sortable: false}
 			],
 			order: [[0, "asc"]],
 			searchDelay: 500,
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});

 		Backend.DataTableSearch.init(dataTable);
 	})();
 </script>

 <script>
 	function likeprofile(user_id,discover_id,id) {
 		$.ajax({
 			headers: {
 				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 			},
 			url: '{{ route('discoverlikes.profile') }}',
 			type: 'POST',
 			dataType: 'json',
 			data: {'user_id':user_id,'discover_id':discover_id},
 			success:function(data)
 			{
 				if(data == true)
 				{
 					$('.likeprofile_'+id).html('<i class="fas fa-thumbs-up"></i>');
 					$('.likeprofile_'+id).css('color','red');
 				}
 				else
 				{
 					$('.likeprofile_'+id).html('<i class="far fa-thumbs-up"></i>');
 					$('.likeprofile_'+id).css('color','black');
 				}
 			}
 		});
 	}
 </script>

 @endsection