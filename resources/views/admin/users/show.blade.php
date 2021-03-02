@extends('admin.layouts.app')
@section('content')
    <div class="content-wrapper" style="min-height: 1136px;">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Profile
            </h1>
            {{--  <ol class="breadcrumb">
               <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
               <li><a href="#">Examples</a></li>
               <li class="active">User profile</li>
             </ol> --}}
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="row">
                <div class="col-md-3">

                    <!-- Profile Image -->
                    <div class="box box-primary">
                        <div class="box-body box-profile">
                            @if(isset($user->profile_picture) && $user->profile_picture!='')
                                <img class="profile-user-img img-responsive" src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="User profile picture">
                            @else
                                <img class="profile-user-img img-responsive img-circle" src="{{ asset('public/front/images/no-image-available.png') }}" alt="User profile picture">
                            @endif

                            <h3 class="profile-username text-center">{{ $user->first_name }} {{ $user->last_name }}</h3>

                            <p class="text-muted text-center">@if($user->user_type=='1')
                                    <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                @endif
                                @if($user->user_type=='2')
                                    <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                @endif
                                @if($user->user_type=='3')
                                    <img src="{{ asset('public/front/images/actor_details.svg') }}" height="20" width="20">
                                @endif</p>

                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Status</b>
                                    <span class="pull-right">
    								@if($user->status==1)
                                            Available
                                        @else
                                            Not Available
                                        @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Gender</b>
                                    <span class="pull-right">
    								@if(isset($user->gender) && !empty($user->gender))
                                            {{ $user->gender }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Playing Age</b>
                                    <span class="pull-right">
    								@if(isset($user->playing_age) && !empty($user->playing_age))
                                            {{ $user->playing_age }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Eye Colour</b>
                                    <span class="pull-right">
    								@if(isset($user->eye_colour) && !empty($user->eye_colour))
                                            {{ $user->eye_colour }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Hair Colour</b>
                                    <span class="pull-right">
    								@if(isset($user->hair_colour) && !empty($user->hair_colour))
                                            {{ $user->hair_colour }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Height</b>
                                    <span class="pull-right">
    								@if(isset($user->height) && !empty($user->height))
                                            {{ $user->height }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Likes</b>
                                    <span class="pull-right">
    								@if(isset($likeCount) && !empty($likeCount))
                                            {{ $likeCount }}
                                        @else 0  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Skills</b>
                                    <span class="pull-right">
    								@if(isset($user->skills) && $user->skills!='') {{ implode(',', json_decode($user->skills)) }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Genre</b>
                                    <span class="pull-right">
    								@if(isset($user->genre) && $user->genre!='') {{ implode(',', json_decode($user->genre)) }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Role Type</b>
                                    <span class="pull-right">
    								@if(isset($user->role_type) && $user->role_type!='') {{ implode(',', json_decode($user->role_type)) }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Languages</b>
                                    <span class="pull-right">
    								@if(isset($user->languages) && $user->languages!='') {{ implode(',', json_decode($user->languages)) }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Accents</b>
                                    <span class="pull-right">
    								@if(isset($user->acents) && $user->acents!='') {{ implode(',', json_decode($user->acents)) }}
                                        @else N/A  @endif
    							</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Plan Name</b>
                                    <span class="pull-right">
    								@if(isset($user->owner->stripe_plan) && $user->owner->stripe_plan!='') {{ $user->owner->stripe_plan }}
                                        @else N/A  @endif
    							</span>
                                </li>
                            </ul>
                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Profile Details</a></li>
                            <li class=""><a href="#photogallery" data-toggle="tab" aria-expanded="false"><i class="fa fa-camera"></i> Photo Gallery</a></li>
                            <li class=""><a href="#videogallery" data-toggle="tab" aria-expanded="false"><i class="fa fa-video"></i> Video Gallery</a></li>
                            <li class=""><a href="#forum" data-toggle="tab" aria-expanded="false"></i> Forum</a></li>
                            <li class=""><a href="#career" data-toggle="tab" aria-expanded="false"></i> Career</a></li>
                            <li class=""><a href="#discover" data-toggle="tab" aria-expanded="false"></i> Discover</a></li>
                            <li class=""><a href="#explore" data-toggle="tab" aria-expanded="false"></i> Explore</a></li>
                            <li class=""><a href="#subscription" data-toggle="tab" aria-expanded="false"></i> Subscription</a></li>
                            <li class=""><a href="#transcation" data-toggle="tab" aria-expanded="false"></i> Transaction</a></li>
                            <li class=""><a href="#refer" data-toggle="tab" aria-expanded="false"></i> Refer List</a></li>
                            <li class=""><a href="#mynetwork" data-toggle="tab" aria-expanded="false"></i> My Friends</a></li>
                            <li class=""><a href="#mypost" data-toggle="tab" aria-expanded="false"></i> My Post</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="activity">
                                @include('admin.users.user-details')
                            </div>
                            <div class="tab-pane" id="photogallery">
                                @include('admin.users.photogallery')
                            </div>
                            <div class="tab-pane" id="videogallery">
                                @include('admin.users.videogallery')
                            </div>
                            <div class="tab-pane" id="forum">
                                @include('admin.users.forum')
                            </div>
                            <div class="tab-pane" id="career">
                                @include('admin.users.career')
                            </div>
                            <div class="tab-pane" id="discover">
                                @include('admin.users.discover')
                            </div>
                            <div class="tab-pane" id="explore">
                                @include('admin.users.explore')
                            </div>
                            <div class="tab-pane" id="subscription">
                                @include('admin.users.subscription')
                            </div>
                            <div class="tab-pane" id="transcation">
                                @include('admin.users.transcation')
                            </div>
                            <div class="tab-pane" id="refer">
                                @include('admin.users.referlist')
                            </div>
                            <div class="tab-pane" id="mynetwork">
                                @include('admin.users.mynetwork')
                            </div>
                            <div class="tab-pane" id="mypost">
                                @include('admin.users.mypost')
                            </div>
                            <div class="tab-pane" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputName" placeholder="Name">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-10">
                                            <button type="submit" class="btn btn-danger">Submit</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
