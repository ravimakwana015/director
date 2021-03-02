 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Register User List
 		</h1>
 	</section>
 	<section class="content">

 		<div class="row">
 			<div class="col-xs-12">
 				@include("admin.include.message")
 				<div id="main_div"></div>
 				<div class="box box-info">
 					<div class="box-header">
 						<h3>Filter</h3>
 						<div class="row">
 							<div class="col-md-4">
 								<div class="input-group input-daterange">
 									<input type="text" name="from_date" id="from_date" readonly class="form-control" />
 									<div class="input-group-addon">to</div>
 									<input type="text"  name="to_date" id="to_date" readonly class="form-control" />
 								</div>
 							</div>
 							<div class="col-md-2">
 								{!! Form::selectMonth('month', $months, ["class" => "search-input-select form-control month","placeholder" => "Select Month"]) !!}
 							</div>
 							<div class="col-md-2">
 								{!! Form::select('year', array_unique($year), null, ["class" => "search-input-select form-control year","placeholder" => "Select Year"]) !!}
 							</div>
 							<div class="col-md-2">
 								<select name="status" class="search-input-select form-control">
 									<option value="">Select Status</option>
 									<option value="1">Active</option>
 									<option value="0">Inactive</option>
 								</select>
 							</div>
 						</div>
 					</div>
 					<!-- /.box-header -->
 					<div class="box-body table-responsive">
 						<table id="user-table"  class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>#</th>
 									<th>Profile Picture</th>
 									<th>Username</th>
 									<th>Name</th>
 									<th>Email</th>
 									<th>Country</th>
 									<th>User Type</th>
 									<th>Likes</th>
 									<th>Status</th>
 									<th>Create At</th>
 									<th>Update At</th>
 								</tr>
 							</thead>
 						</table>
 					</div>
 				</div>
 			</div>
 		</div>
 	</section>
 </div>
 @endsection
 @section('after-scripts')

 

 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css" />
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
 
 <script>
 	$(document).ready(function(){
 		var date = new Date();
 		$('.input-daterange').datepicker({
 			todayBtn: 'linked',
 			format: 'yyyy-mm-dd',
 			autoclose: true
 		});
 	});
 </script>


 <script>

 	$(function() {
 		var dataTable = $('#user-table').DataTable({
 			processing: true,
 			serverSide: true, 
 			// "scrollX": true,
 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{!! route('get.user') !!}',
 				type: 'post',
 				data: function(d) {
 					d.month = $('select[name=month]').val();
 					d.year = $('select[name=year]').val();
 					d.from_date = $('input[name="from_date"]').val();
 					d.to_date = $('input[name="to_date"]').val();
 					d.status = $('select[name=status]').val();
 				}
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'icon', name: 'icon'},
 			{data: 'username', name: 'username'},
 			{data: 'name', name: 'name'},
 			{data: 'email', name: 'email'},
 			{data: 'country', name: 'country'},
 			{data: 'user_type', name: 'user_type'},
 			{data: 'likecount', name: 'likecount'},
 			{data: 'status', name: 'status'},
 			{data: 'created_at', name: 'created_at'},
 			{data: 'updated_at', name: 'updated_at'},
 			],
 			order: [[8, "asc"]],
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}
 		});

 		$('input[name="from_date"]').on('change', function() {
 			dataTable.draw();
 		});
 		$('input[name="to_date"]').on('change', function() {
 			dataTable.draw();
 		});
 		$('select[name=year]').on('change', function() {
 			dataTable.draw();
 		});
 		$('select[name=month]').on('change', function() {
 			dataTable.draw();
 		});
 		$('select[name=status]').on('change', function() {
 			dataTable.draw();
 		});
 		Backend.DataTableSearch.init(dataTable);
 	});
 </script>
 @endsection