@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Dashboard</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3>{{ $actor }}</h3>

                            <p>Actors</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">View More Actors <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>{{ $model }}</h3>

                            <p>Models</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">View More Models <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>{{ $musicians }}</h3>
                            <p>Musicians</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">View More Musicians <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-2 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-blue">
                        <div class="inner">
                            <h3>{{ $crew }}</h3>
                            <p>Creators</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{ route('users.index') }}" class="small-box-footer">View More Creators <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>{{ $transactions }}</h3>

                            <p>Total Transaction</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{ route('transaction.reports') }}" class="small-box-footer">View Transaction <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- Main content -->
            <div class="row">
                <div class="col-md-6">

                    <!-- BAR CHART -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Subscription Transaction Chart</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="barChart" style="width:100%"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
                <div class="col-md-6">

                    <!-- BAR CHART -->
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Pie Chart</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="chart">
                                <canvas id="pieChart" style="width:100%"></canvas>
                            </div>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->

                </div>
            </div>
            <!-- /.content -->
            <div class="row">
                <div class="col-md-6">
                    <!-- USERS LIST -->
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Latest New Members</h3>
                            <div class="box-tools pull-right">
                                <span class="label label-danger">Latest New Members</span>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                @foreach($latestTenUsers as $latestTenUser)
                                    <li>
                                        @if(isset($latestTenUser->profile_picture) && $latestTenUser->profile_picture!='')
                                            <img src="{{ asset('public/img/profile_picture/'.$latestTenUser->profile_picture.'') }}" alt="profile-pic" id="profile_img" width="80">
                                        @else
                                            <img src="{{ asset('public/front/images/196.jpg') }}" alt="profile-pic" id="profile_img" width="80">
                                        @endif
                                        <a href="{{ route('users.show',$latestTenUser->id) }}">
                                            <span class="users-list-date">{{ $latestTenUser->first_name }} {{ $latestTenUser->last_name }}</span>
                                        </a>
                                        <span class="users-list-date">{{ $latestTenUser->created_at->diffForHumans()}}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <!-- /.users-list -->
                        </div>
                    </div>
                    <!--/.box -->
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Most Like Profile</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Profile Icon</th>
                                        <th>Profile User Name</th>
                                        <th>Type</th>
                                        <th>Likes</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach($like as $likeValue)
                                        <tr>
                                            <td>{{ $n }}</td>
                                            <td>@if(isset($likeValue->profilelike->profile_picture) && $likeValue->profilelike->profile_picture!='')
                                                    <img src="{{ asset('public/img/profile_picture/'.$likeValue->profilelike->profile_picture.'') }}" alt="profile-pic"
                                                         id="profile_img" width="50">
                                                @else
                                                    <img src="{{ asset('public/front/images/196.jpg') }}" alt="profile-pic" id="profile_img" width="50">
                                                @endif</td>
                                            <td>
                                                <a href="{{ route('users.show',$likeValue->profilelike->id) }}">{{ $likeValue->profilelike->first_name }} {{ $likeValue->profilelike->last_name }}</a>
                                            </td>
                                            <td>{{ getUserTypeValue($likeValue->profilelike->user_type) }}</td>
                                            <td>{{ $likeValue->userLikes }}</td>
                                        </tr>
                                        @php
                                            $n++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Active Users</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach($activeusers as $activeUsersValue)
                                        <tr>
                                            <td>{{ $n }}</td>
                                            <td><a href="{{ route('users.show',$activeUsersValue->id) }}">{{ $activeUsersValue->first_name }} {{ $activeUsersValue->last_name }}</a>
                                            </td>
                                            <td><span class="label label-success">{{ $activeUsersValue->email }}</span></td>
                                            <td>{{ $activeUsersValue->country }}</td>
                                        </tr>
                                        @php
                                            $n++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">InActive Users</h3>

                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Email</th>
                                        <th>Country</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach($inactiveusers as $inactiveusersvalue)
                                        <tr>
                                            <td>{{ $n }}</td>
                                            <td>
                                                <a href="{{ route('users.show',$inactiveusersvalue->user_id) }}">{{ $inactiveusersvalue->first_name }} {{ $inactiveusersvalue->last_name }}</a>
                                            </td>
                                            <td><span class="label label-success">{{ $inactiveusersvalue->email }}</span></td>
                                            <td>{{ $inactiveusersvalue->country }}</td>
                                        </tr>
                                        @php
                                            $n++;
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Latest Payment Transactions</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Status</th>
                                        <th>Amount</th>
                                        <th>Payment Type</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach($tentranscation as $tenTransactionValue)

                                        @if(isset($tenTransactionValue->usertransaction))
                                            <tr>
                                                <td>{{ $n }}</td>
                                                <td>
                                                    <a href="{{ route('users.show',$tenTransactionValue->user_id) }}">{{ $tenTransactionValue->usertransaction->first_name }} {{ $tenTransactionValue->usertransaction->last_name }}</a>
                                                </td>
                                                <td>
                                                    @if($tenTransactionValue->coupon== 1)
                                                        Coupon Applied
                                                    @elseif($tenTransactionValue->coupon== 0 && $tenTransactionValue->payment_status==1)
                                                        Payment Paid
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($tenTransactionValue->coupon== 1)
                                                        N/A
                                                    @elseif($tenTransactionValue->coupon== 0 && $tenTransactionValue->payment_status==1)
                                                        {{ $tenTransactionValue->amount }}
                                                    @endif

                                                </td>
                                                <td>
                                                    @if($tenTransactionValue->coupon== 1)
                                                        Coupon Applied
                                                    @elseif($tenTransactionValue->coupon== 0 && $tenTransactionValue->payment_status==1)
                                                        Online
                                                    @endif

                                                </td>
                                            </tr>
                                            @php
                                                $n++;
                                            @endphp
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Latest Comments</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table no-margin">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>User Name</th>
                                        <th>Topic Name</th>
                                        <th>Comment</th>
                                        {{-- <th>Status</th> --}}
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @if(isset($comments))
                                        @foreach($comments as $commentsValue)
                                            @if(isset($commentsValue->usercomment))
                                                <tr>
                                                    <td>{{ $n }}</td>
                                                    <td>
                                                        <a href="{{ route('users.show',$commentsValue->user_id) }}">{{ $commentsValue->usercomment->first_name }} {{ $commentsValue->usercomment->last_name }}</a>
                                                    </td>
                                                    <td>{!!  $commentsValue->forumtopic->topic  !!}</td>
                                                    <td>{!! $commentsValue->comment !!}</td>
                                                    {{-- <td>
                                                        @if($commentsValue->comment_status == 1)
                                                        Approved
                                                        @else
                                                        UnApproved
                                                        @endif
                                                       </td> --}}
                                                </tr>
                                                @php
                                                    $n++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{ Html::script('public/admin/js/jquery.min.js') }}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

    <script>
        $.ajax({
            url: "{{ route('admin.chart') }}",
            success: function (data) {

                function addData(chart, label, color, data, bcolor) {
                    chart.data.datasets.push({
                        label: label,
                        backgroundColor: color,
                        borderColor: bcolor,
                        data: data,
                        fill: true,
                    });
                    chart.update();
                }

                setTimeout(function () {
                    function random_rgba() {
                        var o = Math.round, r = Math.random, s = 255;
                        return 'rgba(' + o(r() * s) + ',' + o(r() * s) + ',' + o(r() * s) + ',' + r().toFixed(1) + ')';
                    }

                    function random_hash() {
                        var letters = '0123456789ABCDEF';
                        var color = '#';
                        for (var i = 0; i < 6; i++) {
                            color += letters[Math.floor(Math.random() * 16)];
                        }
                        return color;
                    }

                    $.each(data.data, function (key, value) {
                        var count = [];
                        $.each(value, function (innerkey, innervalue) {
                            count.push(innervalue);
                        });
                        addData(chart, "Transactions of :" + key + "", random_rgba(), count, random_hash());
                    });
                }, 0);
                var ctx = document.getElementById("barChart").getContext('2d');
                ctx.canvas.height = 329;
                var chart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"],
                        datasets: [],

                    },

                    options: {
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Month'
                                }
                            }],
                            yAxes: [{
                                display: true,

                                ticks: {
                                    min: 0,
                                    stepSize: 5
                                }
                            }]
                        },
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
            },
        });
    </script>


    <script>
        $.ajax({
            url: "{{ route('admin.piedata') }}",
            success: function (data) {
                console.log(data);

                function addData(chart, data) {
                    chart.data.datasets.push({
                        backgroundColor: [
                            "#e74c3c",
                            "#3498db",
                            "#f1c40f",
                        ],
                        data: data,
                        fill: true,
                    });
                    chart.update();
                }

                setTimeout(function () {
                    addData(chart, data);
                }, 0);
                var ctx = document.getElementById("pieChart").getContext('2d');
                ctx.canvas.height = 329;
                var chart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: ["Actors", "Models", "Musicians", "Creators"],
                        datasets: [],
                    },
                });
            },
        });
    </script>
@endsection
