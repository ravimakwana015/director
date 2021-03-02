<div class="main winner-page">
    <section class="winnerdetail-wrap">
        <div class="left-content">
            <div class="img-wrap">
                @if(isset($discover->getResult->data) && $discover->getResult->data!='' && file_exists(public_path
                           ('img/discover_results/').$discover->getResult->data))
                    @php
                        $extension = explode('.',$discover->getResult->data)[1];
                    @endphp
                    @if ($extension == 'png' || $extension == 'jpeg' || $extension == 'jpg')
                        <img src="{{ asset('public/img/discover_results/'.$discover->getResult->data.'') }}" alt="discover_results">
                    @elseif ($extension == 'mkv' || $extension == 'mp4')
                        <video controls>
                            <source src="{{ asset('public/img/discover_results/'.$discover->getResult->data.'') }}" type="video/mp4"/>
                            <source src="{{ asset('public/img/discover_results/'.$discover->getResult->data.'') }}" type="video/mkv"/>
                            Your browser does not support HTML5 video.
                        </video>
                    @endif
                @else
                @endif
            </div>
        </div>
        <div class="right-content">
            <div class="detail-text">
                <h2>{!! $discover->title !!}</h2>
                <p>{!! $discover->description !!}</p>
            </div>
        </div>
        <div class="btn-wrap">
            <a href="javascript:;" class="btn">Winner's Profile</a>
        </div>
        <div class="winner-profile-wrap">
            <div class="winner-profile">
                @if(isset($discover->getResult->usersid))
                    <a href="{{ route('users.show',$discover->getResult->usersid->id) }}">
                        <div class="job-logo">
                            @if(file_exists('public/img/profile_picture/225/' . $discover->getResult->usersid->profile_picture) && isset($discover->getResult->usersid->profile_picture)  && $discover->getResult->usersid->profile_picture!='')
                                <img src="{{ asset('public/img/profile_picture/225/'.$discover->getResult->usersid->profile_picture.'') }}" data-src="" alt="profile-pic"
                                     class="lazy"
                                     id="profile_img">
                            @elseif(file_exists('public/img/profile_picture/' . $discover->getResult->usersid->profile_picture) && isset($discover->getResult->usersid->profile_picture)  && $discover->getResult->usersid->profile_picture!='')
                                <img src="{{ asset('public/img/profile_picture/'.$discover->getResult->usersid->profile_picture.'') }}" data-src="" alt="profile-pic" class="lazy"
                                     id="profile_img">
                            @else
                                <img src="{{ asset('public/front/images/240.jpg') }}" data-src="" alt="profile-pic" class="lazy" id="profile_img">
                            @endif
                        </div>
                        <div class="job-detail @if($discover->getResult->usersid->user_type=='1')bg-actor-9 @endif @if($discover->getResult->usersid->user_type=='2') bg-model-9
@endif @if($discover->getResult->usersid->user_type=='3') bg-musician-9 @endif @if($discover->getResult->usersid->user_type=='4') bg-crew-9 @endif">
                            <div class="job-detail-inner">
                                <span class="job-title">{{ $discover->getResult->usersid->first_name }} {{ $discover->getResult->usersid->last_name }}</span>
                                <div class="type">
                                    @if(isset($user->caption) && $user->caption!='') "{!! str_limit($user->caption, 50) !!}" @else N/A @endif
                                </div>
                                <div class="type">
                                    @if($discover->getResult->usersid->user_type=='1')
                                        <img src="{{ asset('public/front/images/actor_list.svg') }}" height="20" width="20">
                                    @endif
                                    @if($discover->getResult->usersid->user_type=='2')
                                        <img src="{{ asset('public/front/images/model_list.svg') }}" height="20" width="20">
                                    @endif
                                    @if($discover->getResult->usersid->user_type=='3')
                                        <img src="{{ asset('public/front/images/musician_list.svg') }}" height="20" width="20">
                                    @endif
                                    @if($discover->getResult->usersid->user_type=='4')
                                        <img src="{{ asset('public/front/images/crew-white.svg') }}" height="20" width="20">
                                    @endif
                                    {{ likeCount($discover->getResult->usersid->id) }}
                                </div>
                            </div>
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </section>
</div>

