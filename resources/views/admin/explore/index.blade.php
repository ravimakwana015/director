 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Develop List
 		</h1>
 	</section>
 	<section class="content">
 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div class="box box-info">
 					<div class="box-header">
 						<a href="{{ route('explore.create') }}" class="btn btn-info pull-right">Add New Develop</a>
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="careers-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Title</th>
 									<th>Job Type</th>
 									<th>Status</th>
 									<th>Created Date</th>
 									<th>Updated Date</th>
 									<th>Action</th>
 								</tr>
 							</thead>
 							<thead class="transparent-bg">
 								<tr>
 									<th></th>
 									<th>
 										{!! Form::text('title', null, ["class" => "search-input-text form-control", "data-column" => 1, "placeholder" => 'Title',"size"=>"5"]) !!}
 										<a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
 									</th>
 									<th>
 										{!! Form::select('job_type', ["Actor"=>"Actor","Models"=>"Models","Musicians"=>"Musicians"], null, ["class" => "search-input-select form-control", "data-column" => 2, "placeholder" => 'Job Type']) !!}
 										<a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
 									</th>
 									<th style="width: 100px;">
 										{!! Form::select('status', ["1"=>"Active","0"=>"Inactive"], null, ["class" => "search-input-select form-control", "data-column" => 3, "placeholder" => 'Status']) !!}
 										<a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
 									</th>
 									<th></th>
 									<th></th>
 									<th></th>
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
 				url: '{{ route("explore.get") }}',
 				type: 'post',
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'title', name: 'title',"width": "15%"},
 			{data: 'job_type', name: 'job_type',"width": "15%"},
 			{data: 'status', name: 'careers.status',"width": "15%"},
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