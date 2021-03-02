@extends('layouts.app')

@section('content')
    @include('include.header')

    <div class="main dashboard-page notifications-page">
        <div class="custom-container">
            <div class="dashboard-wrap">
                <div class="left-sidebar">
                    @include('user.left-sidebar')
                </div>
                <div class="right-side">
                    <div class="main-dashboard">
                        <div class="accounts-wrap">
                            <div class="my-account">
                                <div class="account-header">
                                    <h3 class="dash-title">Notifications</h3>
                                    <a href="{{ route('read.all.notification') }}" class="btn btn-small btn-grey">Mark all as Read</a>
                                </div>
                                <div class="account-body">
                                    @if ($message = Session::get('success'))
                                        <div class="alert alert-success alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @endif
                                    @if ($message = Session::get('error'))
                                        <div class="alert alert-danger alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @endif
                                    @foreach($userNotifications as $notification)
                                        <div class="bill">
                                            <h4>
                                                <div class="status bg-actor"></div>
                                                @if($notification->read_at==null)
                                                    <div class="unread">
                                                        @if(isset($notification->data['data']))
                                                            @if($notification->type=='App\Notifications\ContactProfileNotification')
                                                                <a href="{{ route('contactinquires') }}"><span
                                                                        onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['data']!!}</span></a>
                                                            @else
                                                                <span onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['data'] !!}</span>
                                                            @endif
                                                        @endif
                                                        @if(isset($notification->data['message']))
                                                            <span onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['name']  !!} Sent a Message : "{!!
                                                             $notification->data['message']!!}"</span>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div>
                                                        @if(isset($notification->data['data']))
                                                            @if($notification->type=='App\Notifications\ContactProfileNotification')
                                                                <a href="{{ route('contactinquires') }}"><span>{!! $notification->data['data'] !!}</span></a>
                                                            @else
                                                                <span>{!! $notification->data['data'] !!}</span>
                                                            @endif
                                                        @endif
                                                        @if(isset($notification->data['message']))
                                                            <span>{!! $notification->data['name']  !!} Sent a Message : "{!! $notification->data['message']  !!}"</span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </h4>
                                            <a href="{{ route('delete.notification',[$notification->id]) }}" class="btn btn-small"><i class="fa fa-trash"></i></a>
                                        </div>
                                    @endforeach
                                    {{ $userNotifications->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function markAsRead(element, id) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ route('markAsRead.notification') }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
            }).done(function (res) {
                $(element).closest("div").removeClass('unread');
            });
        }
    </script>
@endsection
