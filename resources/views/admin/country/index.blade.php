 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Country List
 		</h1>
 	</section>
 	<section class="content">

 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					<div class="box-header">
 						<div class="row">
 							<div class="col-md-6"></div>
 							<div class="col-md-6">
 								<a href="{{ route('country.create') }}" class="btn btn-info pull-right">Add Country</a>
 							</div>
 						</div>
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="contry-table" class="table table-bordered table-striped">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Name</th>
 									<th>Created Date</th>
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

 	$(function() {
 		var dataTable = $('#contry-table').dataTable({
 			processing: true,
 			serverSide: true,
 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{!! route('get.country') !!}',
 				type: 'post'
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'name', name: 'name'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'actions', name: 'actions', searchable: false, sortable: false}
 			],
 			order: [[2, "desc"]],
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});
 	});

 </script>
 @endsection