<header>
    <div class="header-wrap">
        <div class="logo-wrap">
            <h1><a href="{{ route('home') }}">Producers Eye</a></h1>
        </div>
        <div class="toggle-btn">
            <span></span>
            <span></span>
            <span></span>
        </div>
        <div class="right-header">
            <div class="nav-wrap">
                <ul>
                    @foreach(pages() as $page)
                    <li><a href="{{ route('page',$page->slug) }}" title="{{ $page->title }}">{{ $page->title }}</a></li>
                    @break
                    @endforeach
                    <li>
                        <a href="javascript:;" onclick="browse('browse','{{ route('transfer') }}','{{route('users')}}')">Browse</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('career') }}" id="login-career-click">Career</a>
                    </li> --}}
                    <li>
                        <a href="{{ route('explore') }}">Develop</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('discover') }}">Enter</a>
                    </li> --}}
                    <li>
                        <a href="{{ route('forumlist') }}">Forum</a>
                    </li>
                    @guest
                    <li>
                        <a href="{{ route('login') }}">Login</a>
                    </li>
                    @endguest
                </ul>
            </div>
            @guest
            @else
            <div class="notification-header network">
                <a href="{{ route('my-network',[str_replace(' ', '-', strtolower(Auth::user()->username))]) }}">
                    <div class="notify-count"><span class="icon gold"><i class="fas fa-eye"></i></span></div>
                    {{-- <span class="icon gold"><i class="fas fa-eye"></i></span> --}}
                </a>
            </div>
            <div class="has-dropdown">
                <a href="javascript:;">
                    <div class="profile-icon">
                        <div class="img-icon">
                            @if(isset(Auth::user()->profile_picture) && Auth::user()->profile_picture!='')
                            <img src="{{ asset('public/img/profile_picture/'.Auth::user()->profile_picture.'') }}" alt="profile-pic" id="header_icon">
                            @else
                            <img src="{{ asset('public/front/images/196.jpg') }}" alt="profile-pic" id="header_icon">
                            @endif
                        </div>
                        <div class="status"></div>
                    </div>
                </a>
                <div class="user-drop-down">

                   <span class="@if(Auth::user()->user_type=='1') bg-actor @endif
                    @if(Auth::user()->user_type=='2') bg-model @endif
                    @if(Auth::user()->user_type=='3') bg-musician @endif
                    @if(Auth::user()->user_type=='4') bg-crew @endif">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                </span>

                <a href="{{ route('profile-details',str_replace(' ', '-', strtolower(Auth::user()->username))) }}">My Profile</a>
                <a href="{{ route('profile') }}">Edit My Profile</a>
                <a href="{{ route('my-network',[str_replace(' ', '-', strtolower(Auth::user()->username))]) }}">My Network</a>
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <a href="{{route('chat.index')}}">Messages @if(count(auth()->user()->unreadNotifications->where('type','App\Notifications\MessageNotification')))
                    ({{ count(auth()->user()->unreadNotifications->where('type','App\Notifications\MessageNotification')) }})@endif</a>
                    <a href="{{ route('contactinquires') }}">Inquiries @if(count(auth()->user()->unreadNotifications->where('type','App\Notifications\ContactProfileNotification')))
                        ({{ count(auth()->user()->unreadNotifications->where('type','App\Notifications\ContactProfileNotification')) }})@endif</a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>

                <div class="notification-header">
                    <a href="{{ route('show.all.notification') }}">
                        @if(count(auth()->user()->unreadNotifications) > 0 )
                        <div class="notify-count gold"><i class="fas fa-bell"><span class="notification-count">{{ count(auth()->user()->unreadNotifications) }}</span></i></div>
                        @else
                        <div class="notify-count"><i class="fas fa-bell"><span class="notification-count">{{ count(auth()->user()->unreadNotifications) }}</span></i></div>
                        @endif
                    </a>
                </div>
                @endguest
                <div class="modal fade pleaseloginpopup" id="mycareerloginModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Please Login</h4>
                            </div>

                            <div class="modal-body">
                                We will help you with reaching future career aspirations as well as listing suitable job opportunities in your sector regularly. If you ever need help
                                with your application we will provide expert insight from one of our producers on board. The jobs that are listed are very exclusive and working with
                                highly reputable firms we are proud to call our partners. Short and long term work will both be available in your sector.
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('login') }}" class="btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade pleaseloginpopup" id="mydeveloploginModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Please Login</h4>
                            </div>

                            <div class="modal-body">
                                We really want to help all of our users of this platform to develop their skills. We have partnered up with different firms from all over the world that
                                we are proud to be working alongside. You will have the chance to go to Acting Workshops, Vocal Coaching, Guitar School, Photography Classes and much
                                more. You will be able to find a course that is suitable for you and no doubt you will be impressed.
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('login') }}" class="btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade pleaseloginpopup" id="myenterloginModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Please Login</h4>
                            </div>

                            <div class="modal-body">
                                Nobody likes homework right? But we will be setting regular projects that anyone can enter. For example, Short Movie Production, Screenplay Writing,
                                Photography, Music Videos and many more. The winners will be advertised on the platform and also will be able to win potential contracts with our
                                reputable partners. All eyes will be on you and this is the place to really showcase your talent under time pressure.
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('login') }}" class="btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade pleaseloginpopup" id="myforumloginModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Please Login</h4>
                            </div>

                            <div class="modal-body">
                                We want to make sure our whole community can connect with each-other easily. Our forum is created for you to see top industry advice, for you to ask any
                                questions and really connect with your fellow entertainers. We will be posting regularly about how you can develop your skills and will reply back
                                almost instantly to any queries. We are a family on here and we will be supporting and helping one another to succeed.
                            </div>
                            <div class="modal-footer">
                                <a href="{{ route('login') }}" class="btn">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade pleaseloginpopup" id="myprofileloginModal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Producers Eye</h4>
                            </div>

                            <div class="modal-body">
                                Have a look at all of our talent who are motivated in breaking through their industry to the very top. Connect with them directly with just a few simple
                                clicks and work with one another. To be able to have a profile, you will need to have an access code from a user that is already on the platform. This
                                way not everyone will be able to sign up as we only want the most serious of performers on this exclusive network.
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade pleaseloginpopup" id="free_trial_expired">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Your Free Trial Expired</h4>
                        </div>

                        <div class="modal-body">
                            Please Activate Membership to use the system features.
                        </div>
                        <div class="modal-footer">
                            @if(Auth::user() && Auth::user()->planid!='')
                            <a href="{{ route('get.payment.trail',Auth::user()->planid) }}" class="btn btn-small">Activate Membership</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade pleaseloginpopup" id="Headshot">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Upload Headshot</h4>
                        </div>

                        <div class="modal-body">
                            Please add your Headshot to your profile, using Edit Profile please. The more you fill out your profile, the higher you will appear on the browse page and
                            found more easily by our partners.
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('profile') }}" class="btn btn-small">Click here to Upload</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth ::user() && isset(Auth ::user() -> owner) && Auth::user()->owner->stripe_id==Auth::user()->username &&  Auth ::user() -> owner->trial_ends_at!='' && Auth ::user() ->
        owner->trial_ends_at<=now() && Auth::user()->planid!='')
        <script>
            jQuery(document).ready(function ($) {
                $("#free_trial_expired").modal({backdrop: 'static', keyboard: false});
            });
        </script>
        @endif
        
        @if(Auth ::user() && \Request::route()->getName()!='profile')
        @if(empty(Auth::user()->profile_picture) || !file_exists('public/img/profile_picture/' . Auth::user()->profile_picture))
        <script>
            jQuery(document).ready(function ($) {
                $("#Headshot").modal({backdrop: 'static', keyboard: false});
            });
        </script>
        @endif
        @endif
    </header>
