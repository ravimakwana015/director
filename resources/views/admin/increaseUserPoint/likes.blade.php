@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Increase User Likes
            </h1>
        </section>
        <section class="content">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div class="box box-info">
                <div class="box-header with-border ">
                    <h3 class="box-title">Increase User Likes</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                {{ Form::open(['route' => 'increase.user.likes','role' => 'form', 'method' => 'post', 'id' => 'pages']) }}
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Select User</label>
                                <select class="form-control select2" name="user_id" id="userId">
                                    <option value="">--Select User--</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->first_name }} - {{ $user->username }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                @endif
                                <span id="user_likes" style="font-weight: bolder;"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_id">Enter Likes</label>
                                {{ Form::text('like', null, ['class' => 'form-control']) }}
                                @if ($errors->has('like'))
                                    <span class="text-danger">{{ $errors->first('like') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box-footer">
                    <div class="row">
                        <div class="col-lg-2 col-lg-10 footer-btn">
                            {{ Form::submit('Submit', ['class' => 'btn btn-primary btn-md']) }}
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>

                {{ Form::close() }}
            </div>
        </section>
    </div>
@endsection
@section('after-scripts')
    <script>
        $(document).ready(function () {
            $("#userId").change(function () {
                var user_id = $(this).children("option:selected").val();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ route('get.user.likes') }}',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        user_id: user_id
                    },
                }).done(function (res) {
                    $('#user_likes').html($("#userId option:selected").html() + '<img src="{{ asset('/public/front/images/actor_details.svg') }}" height="20" width="20"> ' +
                        res);
                });
            });
        });
    </script>
@endsection
