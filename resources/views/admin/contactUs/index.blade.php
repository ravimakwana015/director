 @extends('admin.layouts.app')
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Contact & Help
 		</h1>
 	</section>
 	<section class="content">

 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
{{-- 					<div class="box-header">--}}
{{-- 						<div class="row">--}}
{{-- 							<div class="col-md-6"></div>--}}
{{-- 							<div class="col-md-6">--}}
{{-- 								<a href="{{ route('country.create') }}" class="btn btn-info pull-right">Add Country</a>--}}
{{-- 							</div>--}}
{{-- 						</div>--}}
{{-- 					</div>--}}
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="contact-table" class="table table-bordered table-striped">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Name</th>
 									<th>Email</th>
 									<th>Phone</th>
 									<th>Message</th>
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

 	$(function() {
 		var dataTable = $('#contact-table').dataTable({
 			processing: true,
 			serverSide: true,
 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{!! route('get.contact') !!}',
 				type: 'post'
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'name', name: 'name'},
 			{data: 'email', name: 'email'},
 			{data: 'contact_number', name: 'contact_number'},
 			{data: 'message', name: 'message'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			],
 			order: [[5, "desc"]],
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});
 	});

 </script>
 @endsection
