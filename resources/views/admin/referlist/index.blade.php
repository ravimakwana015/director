 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Refer List
 		</h1>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					<div class="box-header">
 						{{-- <a href="{{ route('careers.create') }}" class="btn btn-info pull-right">Add New Career</a> --}}
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="careers-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>User Name</th>
 									<th>Refer Friend Name</th>
 									<th>Refer Friend Email</th>
 									<th>Refer Code</th>
 									<th>Created Date</th>
 									<th>Updated Date</th>
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
 				url: '{{ route("referlist.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'username', name: 'username'},
 			{data: 'name', name: 'name'},
 			{data: 'email', name: 'email'},
 			{data: 'token', name: 'token'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			],
 			order: [[0, "desc"]],
 			searchDelay: 500,
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});

 		Backend.DataTableSearch.init(dataTable);
 	})();
 </script>
 @endsection