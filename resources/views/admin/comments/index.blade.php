 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Comments List
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
 									<th>Topic</th>
 									<th>Comment</th>
 									<th>User Name</th>
 									<th>Comment Status</th>
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
 				url: '{{ route("comment.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'Topic', name: 'Topic'},
 			{data: 'comment', name: 'comment'},
 			{data: 'username', name: 'username'},
 			{data: 'Comment Status', name: 'Comment Status'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			{data: 'actions', name: 'actions', searchable: false, sortable: false,"width": "15%"}
 			],
 			order: [[6, "asc"]],
 			searchDelay: 500,
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});

 		Backend.DataTableSearch.init(dataTable);
 	})();
 </script>

 {{-- <script type="text/javascript">
 	function unapprovefunction(id) {
 		alert(id);
 	}
 </script>
 --}}
 @endsection