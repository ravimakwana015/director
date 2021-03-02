 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Chat Report List
 		</h1>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					{{-- <div class="box-header">
 						<a href="{{ route('chatreport.create') }}" class="btn btn-info pull-right">Add New chatreport</a>
 					</div> --}}
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="chatreport-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Report By</th>
 									<th>Report TO</th>
 									<th>Reason for Report</th>
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
 		var dataTable = $('#chatreport-table').dataTable({
 			processing: true,
 			serverSide: true,

 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{{ route("chatreport.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'report_by', name: 'report_by',searchable: false, sortable: false},
 			{data: 'report_to', name: 'report_to',searchable: false, sortable: false},
 			{data: 'reason', name: 'reason',searchable: false, sortable: false},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			],
 			order: [[3, "desc"]],
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