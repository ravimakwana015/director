@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Contact Inquire List
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    @include("admin.include.message")
                    <div class="box box-info">
                        <div class="box-body table-responsive">
                            <table id="inquire-table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>User</th>
                                    <th>Name</th>
                                    <th>Photo</th>
                                    <th>Subject</th>
                                    <th>Email</th>
                                    <th>Company</th>
                                    <th>Messages</th>
                                    <th>Instagram</th>
                                    <th>Facebook</th>
                                    <th>Linkedin</th>
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
        (function () {
            var dataTable = $('#inquire-table').dataTable({
                processing: true,
                serverSide: true,
                "scrollX": true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("contactinquire.get") }}',
                    type: 'post',
                },
                columns: [
                    {data: null, searchable: false, sortable: false},
                    {data: 'full_name', name: 'users.first_name'},
                    {data: 'name', name: 'name'},
                    {data: 'photo', name: 'photo'},
                    {data: 'subject', name: 'subject'},
                    {data: 'email', name: 'email'},
                    {data: 'company', name: 'company'},
                    {data: 'message', name: 'message'},
                    {data: 'instagram', name: 'instagram'},
                    {data: 'facebook', name: 'facebook'},
                    {data: 'linkedin', name: 'linkedin'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[11, "desc"]],
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
