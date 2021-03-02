 @extends('admin.layouts.app') 
 @section('content')
 <div class="content-wrapper">
 	<section class="content-header">
 		<h1>
 			Transaction List
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
 							<div class="col-md-2">
 								<input type="text" name="username" class="form-control" placeholder="User Name">
 								<a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
 							</div>
 							<div class="col-md-4">
 								<div class="input-group input-daterange">
 									<input type="text" name="from_date" id="from_date" readonly class="form-control" />
 									<div class="input-group-addon">to</div>
 									<input type="text"  name="to_date" id="to_date" readonly class="form-control" />
 								</div>
 							</div>
 							<div class="col-md-2">
 								{!! Form::select('month',$months,null, ["class" => "form-control month","placeholder" => "Select Month"]) !!}
 							</div>
 							<div class="col-md-2">
 								{!! Form::select('year', array_unique($year), null, ["class" => "form-control year","placeholder" => "Select Year"]) !!}
 							</div>
 							<div class="col-md-2">
 								<select name="payment_status" class="form-control" data-column='6'>
 									<option value="">Select Status</option>
 									<option value="1">Paid</option>
 									<option value="0">UnPaid</option>
 								</select>
 							</div>
 						</div>
 					</div>


 					<div class="box-body table-responsive">
 						<table id="user-table" class="table table-striped table-bordered" style="width:100%">
 							<thead>
 								<tr>
 									<th>Id</th>
 									<th>Username</th>
 									<th>First Name</th>
 									<th>Last Name</th>
 									<th>Email</th>
 									<th>amount</th>
 									<th>Plan Name</th>
 									<th>Payment status</th>
 									<th>Created At</th>
 									<th>Updated At</th>
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
 	function Delete(){
 		var r = confirm("Are Sure To Delete This Record ??");
 		if (r == true) {
 			$('#rssg').submit();
 		}
 	}
 	$(function() {
 		var dataTable = $('#user-table').DataTable({
 			processing: true,
 			serverSide: true, 
 			// "scrollX": true,
 			ajax: {
 				headers: {
 					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
 				},
 				url: '{!! route('get.transaction') !!}',
 				type: 'post',
 				data: function(d) {
 					d.month = $('select[name=month]').val();
 					d.year = $('select[name=year]').val();
 					d.from_date = $('input[name="from_date"]').val();
 					d.to_date = $('input[name="to_date"]').val();
 					d.status = $('select[name=payment_status]').val();
 					d.username = $('input[name=username]').val();

 				}
 			},
 			columns: [
 			{data: null, searchable: false, sortable: false},
 			{data: 'username', name: 'users.username'},
 			{data: 'first_name', name: 'users.first_name'},
 			{data: 'last_name', name: 'users.last_name'},
 			{data: 'email', name: 'users.email'},
 			{data: 'amount', name: 'transactions.amount'},
 			{data: 'plan', name: 'transactions.plan'},
 			{data: 'payment_status', name: 'transactions.payment_status'},
 			{data: 'created_at', name: 'transactions.created_at'},
 			{data: 'updated_at', name: 'updated_at'},

 			],
 			order: [[8, "desc"]],
 			"fnRowCallback": function (nRow, aData, iDisplayIndex) {
 				$("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
 				return nRow;
 			}

 		});
		// $('#dateSearch').on('click', function() {
		// 	dataTable.draw();
		// });
		Backend.DataTableSearch.init(dataTable);

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
		$('select[name=payment_status]').on('change', function() {
			dataTable.draw();
		});
		$('input[name=username]').on('change', function() {
			dataTable.draw();
		});
	});

</script>
@endsection