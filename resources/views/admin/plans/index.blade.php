 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Membership Subscription Plans
 		</h1>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					<div class="box-header">
 						<div class="row">
 							@if(isset($plans) && count($plans)>0)
 							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
 								<a href="{{ route('plans.import') }}" class="btn btn-success"><b><i class="fas fa-download"></i> Import Stripe Plans</b></a>
 							</div>	
 							@endif
 							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
 								<a href="{{ route('plans.create') }}" class="btn btn-info pull-right">Add Plan</a>
 							</div>
 						</div>
 						

 						
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="plans-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Name</th>
 									<th>Amount</th>
 									<th>Interval</th>
 									<th>Status</th>
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
 		var dataTable = $('#plans-table').dataTable({
 			processing: true,
 			serverSide: true,

 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{{ route("plans.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'name', name: 'name'},
 			{data: 'amount', name: 'amount'},
 			{data: 'interval', name: 'interval'},
 			{data: 'status', name: 'status'},
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
 @endsection