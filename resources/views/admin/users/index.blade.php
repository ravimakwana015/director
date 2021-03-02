@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Users List
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    @include("admin.include.message")
                    <div class="box box-info">
                        <div class="box-header">
                            <a href="{{ route('users.create') }}" class="btn btn-info pull-right">Add New User</a>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="users-table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Profile Picture</th>
                                    <th>Username</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>User Type</th>
                                    <th>Access Code</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <thead class="transparent-bg">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        {!! Form::text('username', null, ["class" => "search-input-text form-control", "data-column" => 2, "placeholder" => 'Username',"size"=>"5"]) !!}
                                        <a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    </th>
                                    <th></th>
                                    <th>
                                        {!! Form::text('email', null, ["class" => "search-input-text form-control", "data-column" => 4, "placeholder" => 'Email',"size"=>"15"]) !!}
                                        <a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    </th>
                                    {{-- <th>
                                        {!! Form::text('city', null, ["class" => "search-input-text form-control", "data-column" => 5, "placeholder" => 'City',"size"=>"5"]) !!}
                                        <a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    </th> --}}
                                    <th>
                                        {!! Form::select('status', ["1"=>"Active","0"=>"Inactive"], null, ["class" => "search-input-select form-control", "data-column" => 5, "placeholder" => 'Status']) !!}
                                        <a class="reset-data" href="javascript:void(0)"><i class="fa fa-times"></i></a>
                                    </th>
                                    <th>
                                        {!! Form::select('user_type', ["1"=>"Actors","2"=>"Models","3"=>"Musicians","4"=>"Creators"], null, ["class" =>
                                        "search-input-select form-control", "data-column" => 6, "placeholder" => 'User Type']) !!}
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
    <div class="modal fade" id="deleteUserModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Delete Account
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    If you delete this user, user will lose all data like,<br/>
                    <ol>
                        <li>Profile picture</li>
                        <li>User Image gallery</li>
                        <li>User video gallery</li>
                        <li>Career Request</li>
                        <li>Develop Request</li>
                        <li>Enter Request</li>
                    </ol>
                    and user will not display on profile search as well.
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="deleteUser">Delete</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script>
        function deleteUserModal(user_id) {
            $('#deleteUser').attr('onclick', 'deleteUser("' + user_id + '")');
            $('#deleteUserModal').modal('show');
        }

        function deleteUser(user_id) {
            $.ajax({
                url: '{{ route('user.delete') }}',
                type: 'post',
                dataType: 'json',
                data: {
                    id: user_id
                },
            }).done(function (res) {
               if(res.status==1){
                  $('#deleteUserModal').modal('hide');
                  location.reload();
               }
            }).fail(function (res) {
                console.log('error');
            });
        }

        (function () {
            var dataTable = $('#users-table').dataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("user.get") }}',
                    type: 'post',
                },
                columns: [
                    {data: null, searchable: false, sortable: false},
                    {data: 'icon', name: 'icon', searchable: false, sortable: false, "width": "5%"},
                    {data: 'username', name: 'username'},
                    {data: 'name', name: 'first_name'},
                    {data: 'email', name: 'email'},
                    {data: 'status', name: 'users.status'},
                    {data: 'user_type', name: 'user_type'},
                    {data: 'access_code', name: 'access_code'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false, "width": "20%"}
                ],
                order: [[8, "desc"]],
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
