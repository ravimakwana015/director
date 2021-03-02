@extends('layouts.app')

@section('content')
    @include('include.header')
    <div class="main dashboard-page">
        <div class="custom-container">
            <div class="dashboard-wrap">
                <div class="left-sidebar">
                    @include('user.left-sidebar')
                </div>
                <div class="right-side">
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
                    <div class="main-dashboard">
                        <div class="profile-edit">
                            <div class="profile-img-wrap">
                                @if(isset(Auth::user()->profile_picture) && Auth::user()->profile_picture!='')
                                    <img src="{{ asset('public/img/profile_picture/'.Auth::user()->profile_picture.'') }}" alt="profile-pic">
                                @else
                                    <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic">
                                @endif
                            </div>
                            <div class="content-wrap">
                                <div class="my-profile">
                                    <h2 class="dash-title">My Profile</h2>
                                    <div class="login-details">
                                        <div class="last-login">Last Login: <span
                                                class="date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime(Auth::user()->last_login_at))->diffForHumans() }}</span></div>
                                    </div>
                                    <div class="refer-code">
                                        <div class="last-refer-code">Access Code : <span class="date">@if(isset( Auth::user()->refer_code)) {{ Auth::user()->refer_code }}
                                                @endif</span></div>
                                        <div class="delete-account">
                                            <button type="button" class="btn btn-small" style="margin-top: 10px;" onclick="deleteUserModal('{{ Auth::user()->id }}')">Delete
                                                Account
                                            </button>
                                        </div>
                                    </div>
                                    <span class="like-count">@if(Auth::user()->user_type=='1')
                                            <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                        @endif
                                        @if(Auth::user()->user_type=='2')
                                            <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                        @endif
                                        @if(Auth::user()->user_type=='3')
                                            <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                        @endif
                                        @if(Auth::user()->user_type=='4')
                                            <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                        @endif <span>{{ likeCount(Auth::user()->id) }}</span>
                                    </span>
                                    <div class="collect-reward">
                                        <button type="button" class="btn btn-small" onclick="collectReward('{{ route('collect.reward') }}')">Collect Reward</button>
                                        <div id="reward_error" style="color:red"></div>
                                        <div id="reward_success" style="color:green"></div>
                                    </div>
                                </div>
                                <div class="name-no-wrap">
                                    <div class="name">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</div>
                                </div>
                                <div class="email-wrap">
                                    <div class="email">{{ Auth::user()->email }}</div>
                                </div>
                                <div class="btn-wrap">
                                    <a href="{{ route('profile') }}" class="btn btn-red">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                        <div class="accounts-wrap">
                            <div class="my-account">
                                <div class="account-header">
                                    <h3 class="dash-title">My Membership</h3>
                                </div>
                                <div class="account-body">

                                    <div class="account">
                                        <div class="text">
                                            <h4>Active Membership</h4>
                                            @if(isset($plan))
                                                <span>{{ plan($plan->id)->name }} - (£{{ number_format($plan->amount/100, 2) }}  {{ plan($plan->id)->name }})</span>
                                            @endif
                                            @if(Auth::user()->owner->stripe_id!=Auth::user()->username)
                                                @if (Auth::user()->subscription('main')->onGracePeriod())
                                                    <br/>
                                                    <span>Your Subscription Ends At - {{ date('F d Y',strtotime(Auth::user()->owner->ends_at)) }}</span>
                                                @endif
                                            @endif

                                            @if(isset($customPlan))
                                                <span>{{ $customPlan->stripe_plan }} - (£0  {{ $customPlan->stripe_plan }})</span>
                                            @endif
                                        </div>
                                        @if(Auth::user()->owner->stripe_id!=Auth::user()->username)
                                            @if (Auth::user()->subscription('main')->onGracePeriod())
                                                <a href="{{ route('resume-membership') }}" class="btn btn-small resume-membership">Resume Membership</a>
                                            @else
                                                <a href="javascript:" class="btn btn-small cancle-membership" id="conform-member">Cancel Membership</a>
                                            @endif
                                        @endif
                                        @if(Auth::user()->owner->trial_ends_at!='' && Auth::user()->planid!='')
                                            <a href="{{ route('get.payment.trail',Auth::user()->planid) }}" class="btn btn-small">Activate Membership</a>
                                        @endif
                                    </div>
                                    @if(Auth::user()->owner->trial_ends_at!='')
                                        <div class="account">
                                            <div class="text">
                                                <h4>Trail Period Ends</h4>
                                                <span>{{ date('dS M Y H:i A',strtotime(Auth::user()->owner->trial_ends_at)) }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    @if(Auth::user()->owner->stripe_id!=Auth::user()->username)
                                        <div class="account">
                                            <div class="text">
                                                <h4>Current Period Ends</h4>
                                                <span>{{ date('dS M Y H:i A',$subscription->current_period_end) }}</span>
                                            </div>
                                        </div>
                                        <div class="account">
                                            <div class="text">
                                                <h4>Card Details</h4>
                                                @if(isset(Auth::user()->card_brand) && Auth::user()->card_brand!='')
                                                    <span>{{ ucfirst(Auth::user()->card_brand) }} </span>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="account">
                                            <div class="text">
                                                <h4>{{ ucfirst(Auth::user()->card_brand) }} Ending</h4>
                                                @if(isset(Auth::user()->card_last_four) && Auth::user()->card_last_four!='')
                                                    <span>{{ ucfirst(Auth::user()->card_last_four) }} </span>
                                                @else
                                                    <span>N/A</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="upgrading-plan">
                                            <a href="{{ route('get.membership.plan') }}" class="btn">Change Membership</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="my-account">
                                <div class="account-header">
                                    <h3 class="dash-title">Notifications</h3>
                                    <a href="{{ route('show.all.notification') }}" class="btn btn-small btn-grey">Show all</a>
                                </div>
                                <div class="account-body">
                                    @foreach(Auth::user()->notifications as $key=>$notification)
                                        @if($key<=7)
                                            <div class="bill">
                                                <h4>
                                                    <div class="status bg-actor"></div>
                                                    @if($notification->read_at==null)
                                                        <div class="unread">
                                                            @if(isset($notification->data['data']))
                                                                @if($notification->type=='App\Notifications\ContactProfileNotification')
                                                                    <a href="{{ route('contactinquires') }}"><span
                                                                            onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['data'] !!}</span></a>
                                                                @else
                                                                    <span onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['data'] !!}</span>
                                                                @endif
                                                            @endif
                                                            @if(isset($notification->data['message']))
                                                                <span onclick="markAsRead(this,'{{ $notification->id }}')">{!! $notification->data['name'] !!} Sent a Message : "{!! $notification->data['message'] !!}"</span>
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
                                                                <span>{!! $notification->data['name'] !!} Sent a Message : "{!! $notification->data['message'] !!}"</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </h4>
                                            </div>
                                        @endif
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        @if(Auth::user()->owner->stripe_id!=Auth::user()->username)
                            <div class="accounts-wrap refer-wrap">
                                <div class="my-account">
                                    <div class="account-header">
                                        <h3 class="dash-title">Cards</h3>
                                        <button type="button" class="btn btn-small btn-grey" id="addCard"><i class="fas fa-plus"></i> Add card</button>
                                    </div>
                                    <div class="account-body">
                                        @if ($message = Session::get('delete-card'))
                                            <div class="alert alert-success alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @endif
                                        <div id="setcard-error-msg" style="display: none;">
                                            <div class="alert alert-success alert-block">
                                                <button type="button" class="close" data-dismiss="alert">×</button>
                                                <strong id="set_msg"></strong>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            @include('user.card')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @include('user.how_to_use_tab')
                        <div class="accounts-wrap refer-wrap">
                            <div class="my-account">
                                <div class="account-header">
                                    <h3 class="dash-title">Refer Friend List</h3>
                                </div>
                                <div class="account-body">
                                    @if ($message = Session::get('success-refer'))
                                        <div class="alert alert-success alert-block">
                                            <button type="button" class="close" data-dismiss="alert">×</button>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @endif
                                    @if(count($invites))
                                        <div class="refer-content">
                                            <table class="table table-striped table-bordered" style="width:100%" id="invites-user	">
                                                <thead>
                                                <tr>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Status</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($invites as $invite)
                                                    <tr>
                                                        <td>{!!  $invite->name !!}</td>
                                                        <td>{!!  $invite->email !!}</td>
                                                        <td>
                                                            @if($invite->status==1)
                                                                <label class='label label-success'>Registered</label>
                                                            @else
                                                                <label class='label label-warning'>Not Registered</label>
                                                                <a href="{{ route('reminder',$invite->id) }}" class="btn btn-small fas fa-paper-plane" title="Send reminder"></a>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="forum-pagination">
                                                {!! $invites->appends(request()->except('page'))->links() !!}
                                            </div>
                                            @else
                                                No Records Available
                                            @endif
                                        </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteUserModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Delete Account</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
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

    <div class="modal fade" id="addCardModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add a card</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => route('add-card'), 'data-parsley-validate', 'id' => 'card-form']) !!}
                    <div id="card-error-msg"></div>
                    <div id="card-success-msg"></div>
                    <input type="hidden" id="card-url" value="{{ route('add-card') }}">
                    <input type="hidden" id="card-redirect_url" value="{{ route('dashboard') }}">
                    <br/>
                    <div class="form-item">
                        <label>Credit Card Number</label>
                        {!! Form::text('number', null, ['class'=> 'form-control','required'=> 'required','data-stripe'=> 'number','data-parsley-type'=> 'number','maxlength'=> '16','data-parsley-trigger'=> 'change focusout','data-parsley-class-handler'=> '#cc-group']) !!}
                    </div>
                    <div class="form-item">
                        <label>MM</label>
                        {!! Form::selectMonth('exp_month', null, ['required'=> 'required','data-stripe'=> 'exp-month'], '%m') !!}
                    </div>
                    <div class="form-item">
                        <label>YYYY</label>
                        {!! Form::selectYear('exp_year', date('Y'), date('Y') + 10, null, ['required'=> 'required','data-stripe'       => 'exp-year']) !!}
                    </div>
                    <div class="form-item">
                        <label>CVC/CVV</label>
                        {!! Form::text('cvc', null, ['required'=> 'required','data-stripe'=> 'cvc','data-parsley-type'=> 'number','data-parsley-trigger'=> 'change focusout','maxlength'=> '4','data-parsley-class-handler'=> '#ccv-group','placeholder'=> '3 or 4 digits code']) !!}
                    </div>
                    <div class="form-action">
                        {!! Form::submit('Add card', ['class' => 'btn', 'id' => 'submitBtn', 'style' => 'margin-top: 20px;']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateCardModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Update a card</h4>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => route('update-card'), 'data-parsley-validate', 'id' => 'update-card-form']) !!}
                    <div id="update-card-error-msg"></div>
                    <input type="hidden" id="update-card-url" value="{{ route('update-card') }}">
                    <input type="hidden" id="update-card-redirect_url" value="{{ route('dashboard') }}">
                    <div class="form-item">
                        <label>MM</label>
                        {!! Form::selectMonth('exp_month', null, ['required'=> 'required','data-stripe'=> 'exp-month','id'=>'exp_month'], '%m') !!}
                    </div>
                    <div class="form-item">
                        <label>YYYY</label>
                        {!! Form::selectYear('exp_year', date('Y'), date('Y') + 10, null, ['required'=> 'required','data-stripe'=> 'exp-year','id'=>'exp_year']) !!}
                    </div>
                    <div class="form-action">
                        {!! Form::submit('Update', ['class' => 'btn', 'id' => 'updateBtn', 'style' => 'margin-top: 20px;']) !!}
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteCardModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Remove payment method</h4>
                </div>
                <form id="deleteCardBtn" method="post" accept-charset="UTF-8">
                    @csrf
                    <div class="modal-body">
                        The Payment method will no longer be in use after you have removed it.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-small cancle-membership" data-dismiss="modal">Go Back</button>
                        <button type="submit" class="btn btn-small cancle-membership" id="cancle-membership">Yes I’m Sure</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myconformModal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Producer Eye</h4>
                </div>
                <div class="modal-body">
                    <h5>Are you sure you would like to cancel your membership ?</h5>
                    <form action="{{ route('cancle-membership') }}" method="post">
                        @csrf
                        <div class="form-item">
                            <textarea name="feedback" placeholder="Reason for cancel your membership"></textarea>
                        </div>
                        <button type="submit" class="btn btn-small cancle-membership">Yes I’m Sure</button>
                    </form>
                </div>
            </div>
        </div>
    </div>



    {{ Html::script('https://parsleyjs.org/dist/parsley.js') }}
    {{ Html::script('https://js.stripe.com/v2/') }}
    {{ Html::script('public/front/js/dashboard.js?v=5.0.0') }}

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

        function deleteUserModal(user_id) {
            $('#deleteUser').attr('onclick', 'deleteUser("' + user_id + '","{{ route('delete.user.profile') }}")');
            $('#deleteUserModal').modal('show');
        }

        function expander(id) {
            $('#TableData_' + id + '').slideToggle('slow');
        }

        Stripe.setPublishableKey("<?php echo env('STRIPE_KEY') ?>");
    </script>

    @include('include.footer')
@endsection
