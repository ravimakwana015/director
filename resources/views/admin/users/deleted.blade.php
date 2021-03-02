@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Deleted Users List
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    @include("admin.include.message")
                    <div class="box box-info">
                    {{--                        <div class="box-header">--}}
                    {{--                            <a href="{{ route('users.create') }}" class="btn btn-info pull-right">Add New User</a>--}}
                    {{--                        </div>--}}
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
                                    {{-- <th>City</th> --}}
                                    <th>Status</th>
                                    <th>User Type</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i=1;
                                @endphp
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>
                                            @if (isset($user->profile_picture) && !empty($user->profile_picture))
                                                <img src='{{ asset("/public/img/profile_picture/".$user->profile_picture) }}' width='50' height='50'>
                                            @else
                                                <img src='{{ asset("/public/front/images/196.jpg") }}' alt='Profile Picture' width='50' height='50'>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('users.show', $user->id) }}">{{ $user->username }}</a></td>
                                        <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->status == 1)
                                                <label class='label label-success'>Active</label>
                                            @else
                                                <label class='label label-warning'>Inactive</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($user->user_type == '1')
                                                Actor
                                            @elseif ($user->user_type == '2')
                                                Model
                                            @elseif ($user->user_type == '3')
                                                Musician
                                            @elseif ($user->user_type == '4')
                                                Creator
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
                                        <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
                                        <td>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                <input type="hidden" value="DELETE" name="_method">
                                                @csrf
                                                <a href="{{ route('user.restore',$user->id) }}" class="btn btn-info" title="Restore User"><i class="fa fa-refresh"></i></a>
                                                <button type="submit" class="btn btn-danger" data-toggle="confirmation">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @php
                                        $i++;
                                    @endphp
                                @endforeach
                                </tbody>
                            </table>
                            {!! $users->appends(request()->except('page'))->links() !!}
                        </div>
                        <!-- /.box-body -->
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
