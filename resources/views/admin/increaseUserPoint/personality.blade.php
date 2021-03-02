@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Increase User Personality
            </h1>
        </section>
        <section class="content">
            @if ($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
            @endif
            <div id="errors"></div>
            <div id="success"></div>
            <div class="box box-info">
                <div class="box-header with-border ">
                    <h3 class="box-title">Increase User Personality</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                                title="Collapse">
                            <i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <form id="increaseUserPersonalityForm">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Select User</label>
                                    <select class="form-control select2" name="user_id" id="user_id" onchange="getUserPersonality()">
                                        <option value="">--Select User--</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->first_name }} - {{ $user->username }}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('user_id'))
                                        <span class="text-danger">{{ $errors->first('user_id') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="row" id="userTraits">
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-lg-2 col-lg-10 footer-btn">
                                {{ Form::button('Increase User Personality ', ['class' => 'btn btn-primary btn-md',
                                'onclick'=>'increaseUserPersonality()']) }}
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>

    <script>
        function increaseUserPersonality() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('increase.user.personality') }}',
                type: 'POST',
                dataType: 'json',
                data: $('#increaseUserPersonalityForm').serialize(),
            }).done(function (res) {
                var errorString = '<div class="alert alert-success alert-block">' +
                    '<button type="button" class="close" data-dismiss="alert">×</button>' +
                    '' +res.errors+
                    '</div>';
                $('#success').html(errorString);
                // location.reload();
            }).fail(function (res) {
                var response = JSON.parse(res.responseText);
                var errorString = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button><ul>';
                $.each(response.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul></div>';
                $('#errors').html(errorString);
            });
        }

        function getUserPersonality() {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('get.user.personality') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    user_id: $('#user_id').val()
                },
            }).done(function (res) {
                $('#userTraits').html(res.personality);
            }).fail(function (res) {
                var response = JSON.parse(res.responseText);
                var errorString = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button><ul>';
                $.each(response.errors, function (key, value) {
                    errorString += '<li>' + value + '</li>';
                });
                errorString += '</ul></div>';
                $('#errors').html(errorString);
            });
        }
    </script>

@endsection
