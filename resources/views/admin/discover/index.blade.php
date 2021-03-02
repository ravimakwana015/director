@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Enter List
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    @include("admin.include.message")
                    <div class="box box-info">
                        <div class="box-header">
                            <a href="{{ route('discover.create') }}" class="btn btn-info pull-right">Add New Enter</a>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body table-responsive">
                            <table id="careers-table" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Competitions</th>
                                    <th>Title</th>
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

    <div class="modal fade" id="resultModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Declare Results
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="form-errors"
                         style="background-color: #dd4b39;padding: 15px; margin-bottom: 20px; border: 1px solid transparent;color: #fff;font-weight: bold;display: none;"></div>
                    <div class="form-success" style="background-color: #32a552;padding: 15px; margin-bottom: 20px; border: 1px solid transparent;color: #fff;font-weight: bold;
                    display: none;"></div>
                    <form id="contact_form" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="enter_id" name="enter_id" value="">
                        <div class="form-group">
                            <label for="user_id">Select Winner</label>
                            <select class="form-control" name="user_id" id="users"></select>
                        </div>
                        <div class="form-group">
                            <label class="custom-file">Upload Photo Or Video</label>
                            <input type="file" name="photo" class="custom-file-input" id="photo_yourself">
                            <div id="post_image_div"></div>
                        </div>
                        <div class="form-action">
                            <button class="btn btn-success" type="button" id="declare_btn">Declare Results</button>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="resultErrorModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="background-color: #f39c12;color: #fff; font-weight: bolder; padding: 20px; text-align: center;">
                        No User apply for the competition
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="winnerModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Competition Winner
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </h4>
                </div>

                <!-- Modal body -->
                <div class="modal-body" id="winner_wrap">
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="winnerErrorModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div style="background-color: #f39c12;color: #fff; font-weight: bolder; padding: 20px; text-align: center;">
                        Enter Not Available
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('after-scripts')
    <script>
        function declareResult(id) {
            $('#loading').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('get.competition.user') }}',
                type: 'POST',
                dataType: 'json',
                data: {id: id},

            }).done(function (res) {
                $('#loading').hide();
                $('#enter_id').val(id);
                var users = '<option value="">-- Select Winner --</option>';
                $.each(res.users, function (key, val) {
                    users += '<option value="' + val.user_id + '">' + val.name + '-' + val.username + '</option>';
                });
                $('#users').html(users);
                $('#resultModal').modal('show');
            }).fail(function (data) {
                $('#loading').hide();
                $('#resultErrorModal').modal('show');
            }).always(function () {
                // console.log("complete");
            });
        }

        function vieWinner(id) {
            $('#loading').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('view.winner.user') }}',
                type: 'POST',
                dataType: 'json',
                data: {id: id},

            }).done(function (res) {
                $('#loading').hide();
                $('#winner_wrap').html(res.data);
                $('#winnerModal').modal('show');
            }).fail(function (data) {
                $('#loading').hide();
                $('#winnerErrorModal').modal('show');
            }).always(function () {
                // console.log("complete");
            });
        }

        $('#declare_btn').on('click', function () {
            $('.form-errors').hide();
            var formdata = new FormData();
            var files = document.getElementById("photo_yourself").files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("photo", files[i], files[i]['name']);
            }
            formdata.append("enter_id", $('input[name=enter_id]').val());
            formdata.append("user_id", $('#users').val());
            $('#loading').show();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('declare.result') }}',
                type: 'POST',
                data: formdata,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
            }).done(function (res) {
                $('#loading').hide();
                if (res.status == false) {
                    var errorString = '<ul>';
                    $.each(res.msg, function (key, value) {
                        errorString += '<li>' + value + '</li>';
                    });
                    errorString += '</ul>';
                    $('.form-errors').html('');
                    $('.form-errors').html(errorString);
                    $('.form-errors').show();
                    // setTimeout(function () {
                    //     $('.form-errors').html('');
                    // }, 4000);
                } else {
                    $('.form-errors').html('');
                    $('.form-errors').hide();
                    $('.form-success').html('');
                    $('.form-success').html(res.msg);
                    $('.form-success').show();
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                }
            });
        });

        (function () {
            var dataTable = $('#careers-table').dataTable({
                processing: true,
                serverSide: true,

                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route("discover.get") }}',
                    type: 'post',
                },
                columns: [
                    {data: null, searchable: false, sortable: false},
                    {data: 'competitions', name: 'competitions'},
                    {data: 'title', name: 'title'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'updated_at', name: 'updated_at'},
                    {data: 'actions', name: 'actions', searchable: false, sortable: false}
                ],
                order: [[4, "desc"]],
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
