@extends('layouts.app')
@section('title','Profiles Details')
@section('content')
@include('include.header')
<div class="main profile-page">
	<div class="custom-container">
		<div class="name-header">
			<h2>
				<span>{{ ucfirst($user->first_name) }} {{ ucfirst($user->last_name) }} -</span>
				@if($user->user_type!=4)
				@if(isset($user->other_professions) && !empty($user->other_professions))
				@php
				$profession=[];
				@endphp
				@foreach(json_decode($user->other_professions) as $key=>$prof)
				@php
				$profession[] = $prof;
				@endphp
				@endforeach
				@php
				$profession[] = getUserTypeValue($user->user_type);
				@endphp
				@else
				@php
				$profession[] = getUserTypeValue($user->user_type);
				@endphp
				@endif
				@else
				@if(isset($user->other_professions) && !empty($user->other_professions))
				@php
				$profession=[];
				@endphp
				@foreach(json_decode($user->other_professions) as $key=>$prof)
				@php
				$profession[] = $prof;
				@endphp
				@endforeach
				@endif
				@php
				$profession[] = $user->crew_type;
				@endphp
				@endif
				@php
				sort($profession);
				@endphp
				<span><i>{{ join(' & ', array_filter(array_merge(array(join(', ', array_slice(array_unique($profession), 0, -1))), array_slice(array_unique($profession), -1)), 'strlen')) }}</i></span>
				@include('profile.profile-like-unlike')
			</h2>
			<div class="like-error" style="color: red;"></div>
		</div>
		@include('admin.include.message')

		<div class="profile-wrap @if($user->user_type=='1') actor @endif @if($user->user_type=='2') model @endif @if($user->user_type=='3') musician @endif
			@if($user->user_type=='4') crew @endif">
			<div class="grid">

				<div class="img-bio">
					<div class="img-wrap">
						@if(isset($user->profile_picture) && $user->profile_picture!='')
						<img src="{{ asset('public/img/profile_picture/'.$user->profile_picture.'') }}" alt="profile-pic" id="profile_img">
						@else
						<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
						@endif
					</div>
				</div>

				@include('profile.profile-info-button')

				@if(isset($user->caption) && $user->caption!='')
				<div class="self-bio">
					<p>"{!! $user->caption  !!}"</p>
				</div>
				@endif
				@if(isset($user->cv) && $user->cv!='')
				<div class="skill-wrap">
					<a href="{{ asset('public/cv/'.$user->cv.'') }}" alt="cv" id="sc" target="_blank" style="color: #606060;"><b>Download CV</b> <i
						class="fas fa-download"></i></a>
					</div>
					@endif

					<div class="self-detail">
						@if(isset($user->gender) && !empty($user->gender))
						<div class="type">
							Gender:
							<span class="ans">
								@if($user->gender=='no_to_ans')
								Prefer not to answer
								@else
								{{ ucfirst($user->gender) }}
								@endif
							</span>
						</div>
						@endif
						@if($user->user_type!=4)
						@if(isset($user->playing_age) && !empty($user->playing_age))
						<div class="type"> Age:
							<span class="ans">
								{{ $user->playing_age }}
							</span>
						</div>
						@endif
						@endif
						@if(isset($user->eye_colour) && !empty($user->eye_colour))
						<div class="type">Eye Colour:
							<span class="ans">
								{{ ucfirst($user->eye_colour) }}
							</span>
						</div>
						@endif
						@if(isset($user->hair_colour) && !empty($user->hair_colour))
						<div class="type">Hair Colour:
							<span class="ans">
								{{ ucfirst($user->hair_colour) }}
							</span>
						</div>
						@endif
						@if(isset($user->height) && !empty($user->height))
						<div class="type">Height:
							<span class="ans">
								{{ $user->height }}
							</span>
						</div>
						@endif
						@if(isset($user->genre) && $user->genre!='') 
						<div class="type">Genre:
							<span class="ans">
								{{ implode(', ', json_decode($user->genre)) }}
							</span>
						</div>
						@endif
						@if($user->user_type!='4')
						@if(isset($user->role_type) && $user->role_type!='') 
						<div class="type">Role types:
							<span class="ans">
								{{ implode(', ', json_decode($user->role_type)) }}
							</span>
						</div>
						@endif
						@endif
						@if(isset($user->languages) && $user->languages!='')
						<div class="type">Language:
							<span class="ans">
								{{ implode(', ', json_decode($user->languages)) }}
							</span>
						</div>
						@endif
						@if($user->user_type==1 || $user->user_type==2)
						@if(isset($user->acents) && $user->acents!='')
						<div class="type">Accents:
							<span class="ans">
								{{ implode(', ', json_decode($user->acents)) }}
							</span>
						</div>
						@endif
						@endif
						@if(isset($user->skills) && $user->skills!='')
						<div class="type">Skills:
							<span class="ans">
								{{ implode(', ', json_decode($user->skills)) }}
							</span>
						</div>
						@endif
					</div>
				</div>
				<div class="grid">
					<div class="shadow-box">
						<h3 class="title">Biography</h3>
						<p>
							@if(isset($user->biography) && !empty($user->biography))
							{{ $user->biography }}
							@endif
						</p>
					</div>
					{{--					<div class="shadow-box">--}}
						{{--						<h3 class="title">Goals</h3>--}}
						{{--						<p>--}}
							{{--							@if(isset($user->goals) && !empty($user->goals))--}}
							{{--							{{ $user->goals }}--}}
							{{--							  @endif--}}
						{{--						</p>--}}
					{{--					</div>--}}
					<div class="shadow-box">
						<h3 class="title">Interests</h3>
						<p>
							@if(isset($user->interests) && !empty($user->interests))
							{{ $user->interests }}
							@endif
						</p>
					</div>
					@php
					$other_actor=0;
					$other_model=0;
					$other_musician=0;
					if($user->other_professions!=''){
						$other_professions = json_decode($user->other_professions,true);
						if(in_array('Actor',$other_professions)){
							$other_actor=1;
						}
						if (in_array('Model',$other_professions)){
							$other_model=1;
						}
						if(in_array('Musician',$other_professions)){
							$other_musician=1;
						}
					}
					@endphp
					@if($user->user_type=='1' || $user->user_type=='2' || $user->user_type=='3' || $other_actor==1 || $other_model==1 || $other_musician==1)
					@if(isset($user->representation) && $user->representation==1)
					<div class="shadow-box">
						<h3 class="title">Representation</h3>
						<p>
							Looking for Representation
						</p>
					</div>
					@elseif($user->model_link !='' && $user->model_name!='')
					<div class="shadow-box">
						<h3 class="title">Representation</h3>
						<p>
							<a href="{{ $user->model_link }}" target="_blank">{{ $user->model_name }}</a>
						</p>
					</div>
					@endif
					@endif
					@if(Auth::user() && Auth::id()==$user->id)
					<div class="shadow-box">
						<h3 class="title">Recent Activity</h3>
						<p>
							@if(isset($user->userActivity) && count($user->userActivity)>0)
							@include('profile.user-activity',['userActivity'=>$user->userActivity])
							<a href="{{ route('user.activity',$user->username) }}" class="btn">See All</a>
							@endif
						</p>
					</div>
					@elseif(Auth::user() && is_friend($user->id))
					<div class="shadow-box">
						<h3 class="title">Recent Activity</h3>
						<p>
							@if(isset($user->userActivity) && count($user->userActivity)>0)
							@include('profile.user-activity',['userActivity'=>$user->userActivity])

							<a href="{{ route('user.activity',$user->username) }}" class="btn">See All</a>
							@endif
						</p>
					</div>
					@endif
				</div>
				<div class="grid">
					@if(Auth::user())
					<?php
					$userTraits = json_decode($user -> personality['click_by'], true);
					if (isset(json_decode($user -> personality['click_by'], true)[Auth ::user() -> id])) {

						$userTraits = json_decode($user -> personality['click_by'], true)[Auth ::user() -> id];
					}
					?>
					@endif
					<div class="box">
						<h3 class="title">Personality Traits</h3>
						<div class="progress-bar-wrap">
							<div class="p-bar">
								<h4>Determination
									@if(Auth::user())
									@if(isset($userTraits) && in_array('loneliness',$userTraits) || $user->personality->loneliness >=200)
									<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
									@elseif($user->id!=Auth::user()->id)
									<div class="add_pr" id="a_loneliness" onclick="increasePersonality('loneliness','{{ $user->id }}','Determination')"><i
										class="fas fa-plus-circle"></i></div>
										@else
										<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
										@endif
										@else
										<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
										@endif
									</h4>

									<div class="progress-bar">
										<div class="progress" @if(isset($user->personality->loneliness))style="width: {{ $user->personality->loneliness/2 }}%;"
											@else style="width: 0%;"
											@endif id="progress_loneliness"></div>
										</div>


									</div>
									<div class="p-bar">
										<h4>Genre Flexibility
											@if(Auth::user())
											@if(isset($userTraits) && in_array('entertainment',$userTraits) || $user->personality->entertainment >=200)
											<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
											@elseif($user->id!=Auth::user()->id)
											<div class="add_pr" id="a_entertainment" onclick="increasePersonality('entertainment','{{ $user->id }}','Genre Flexibility')"><i
												class="fas fa-plus-circle"></i></div>
												@else
												<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
												@endif
												@else
												<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
												@endif
											</h4>
											<div class="progress-bar">
												<div class="progress" @if(isset($user->personality->entertainment))style="width: {{ $user->personality->entertainment/2 }}%;"
													@else style="width: 0%;" @endif id="progress_entertainment"></div>
												</div>

											</div>
											<div class="p-bar">
												<h4>Communication
													@if(Auth::user())
													@if(isset($userTraits) && in_array('curiosity',$userTraits) || $user->personality->curiosity >=200)
													<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
													@elseif($user->id!=Auth::user()->id)
													<div class="add_pr" id="a_curiosity" onclick="increasePersonality('curiosity','{{ $user->id }}','Communication')"><i
														class="fas fa-plus-circle"></i></div>
														@else
														<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
														@endif
														@else
														<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
														@endif
													</h4>
													<div class="progress-bar">
														<div class="progress" @if(isset($user->personality->curiosity) )style="width: {{ $user->personality->curiosity/2 }}%;"
															@else style="width: 0%;"
															@endif id="progress_curiosity"></div>
														</div>

													</div>
													<div class="p-bar">
														<h4>Work Ethic
															@if(Auth::user())
															@if(isset($userTraits) && in_array('relationship',$userTraits) || $user->personality->relationship >=200)
															<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
															@elseif($user->id!=Auth::user()->id)
															<div class="add_pr" id="a_relationship" onclick="increasePersonality('relationship','{{ $user->id }}','Work Ethic')"><i
																class="fas fa-plus-circle"></i></div>
																@else
																<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
																@endif
																@else
																<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
																@endif
															</h4>
															<div class="progress-bar">
																<div class="progress" @if(isset($user->personality->relationship))style="width: {{ $user->personality->relationship/2 }}%;"
																	@else style="width: 0%;" @endif id="progress_relationship"></div>
																</div>

															</div>
															<div class="p-bar">
																<h4>Honesty
																	@if(Auth::user())
																	@if(isset($userTraits) && in_array('hookup',$userTraits) || $user->personality->hookup >=200)
																	<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
																	@elseif($user->id!=Auth::user()->id)
																	<div class="add_pr" id="a_hookup" onclick="increasePersonality('hookup','{{ $user->id }}','Honesty')"><i
																		class="fas fa-plus-circle"></i>
																	</div>
																	@else
																	<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
																	@endif
																	@else
																	<div class="add_pr pr-disabled"><i class="fas fa-plus-circle"></i></div>
																	@endif
																</h4>
																<div class="progress-bar">
																	<div class="progress" @if(isset($user->personality->hookup))style="width: {{ $user->personality->hookup/2 }}%;" @else style="width: 0%;"
																		@endif id="progress_hookup"></div>
																	</div>

																</div>
															</div>
														</div>
														@if($user->user_type=='1')
														<div class="box-list shadow-box">
															<h3 class="title">Favourite Films</h3>
															@if(isset($user->favourite_films) && !empty($user->favourite_films))
															@include('profile.favourite_part',['favourites'=>$user->favourite_films])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Favourite Directors</h3>
															@if(isset($user->favourite_directors) && !empty($user->favourite_directors))
															@include('profile.favourite_part',['favourites'=>$user->favourite_directors])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Play any character from a movie who would it be and why?</h3>
															@if(isset($user->actor_questions) && !empty($user->actor_questions))
															{!! $user->actor_questions !!}
															@endif
														</div>
														@endif

														@if($user->user_type=='2')
														<div class="box-list shadow-box">
															<h3 class="title">Favourite Models</h3>
															@if(isset($user->favourite_models) && !empty($user->favourite_models))
															@include('profile.favourite_part',['favourites'=>$user->favourite_models])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Favourite Brands</h3>
															@if(isset($user->favourite_brands) && !empty($user->favourite_brands))
															@include('profile.favourite_part',['favourites'=>$user->favourite_brands])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Model for any company, which one and why?</h3>
															@if(isset($user->model_questions) && !empty($user->model_questions))
															{!! $user->model_questions !!}
															@endif
														</div>
														@endif

														@if($user->user_type=='3')
														<div class="box-list shadow-box">
															<h3 class="title">Favourite songs</h3>
															@if(isset($user->top_songs) && !empty($user->top_songs))
															@include('profile.favourite_part',['favourites'=>$user->top_songs])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Favourite Musicians</h3>
															@if(isset($user->top_musicians) && !empty($user->top_musicians))
															@include('profile.favourite_part',['favourites'=>$user->top_musicians])
															@endif
														</div>
														<div class="box-list shadow-box">
															<h3 class="title">Perform with anyone on stage who would it be and why?</h3>
															@if(isset($user->musician_questions) && !empty($user->musician_questions))
															{!! $user->musician_questions !!}
															@endif
														</div>
														@endif
													</div>
												</div>

												@if(isset($user->gallery) && count($user->gallery))
												<section id="gallery">
													<div id="image-gallery">
														<h3 class="title">Image Gallery</h3>
														<div class="row">
															@foreach($user->gallery as $key=>$gallery)
															@if($gallery->image !='' && file_exists(public_path('img/user_gallery/').$gallery->image))
															<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
																<div class="img-wrapper">
																	<a href="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}">
																		<img src="{{ asset('public/img/user_gallery/'.$gallery->image.'') }}" class="img-responsive"></a>
																		<div class="img-overlay">
																			<i class="fa fa-plus-circle" aria-hidden="true"></i>
																		</div>
																	</div>
																</div>
																@endif
																@endforeach
															</div>
														</div>
													</section>
													@endif


													@if(isset($user->videoGallery) && count($user->videoGallery))
													<section id="gallery">
														<div id="video-gallery">
															<h3 class="title">Video Gallery</h3>
															<div class="row">
																@foreach($user->videoGallery as $vkey=>$video)
																@if($video->video !='' && file_exists(public_path('img/video_gallery/').$video->video))
																<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12 image">
																	<div class="img-wrapper">
																		<a href="{{ asset('public/img/video_gallery/'.$video->video.'') }}" data-fancybox="video-preview">
																			<video width="400" controls>
																				<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/mp4">
																					<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/ogg"/>
																						Your browser does not support HTML5 video.
																					</video>
																				</a>
																			</div>
																		</div>
																		@endif
																		@endforeach
																		<div style="display: none;">
																			@foreach($user->videoGallery as $ikey=>$video)
																			@if($video->video !='' && file_exists(public_path('img/video_gallery/').$video->video))
																			@if($ikey>0)
																			<a href="{{ asset('public/img/video_gallery/'.$video->video.'') }}" data-fancybox="video-preview">
																				<video width="400" controls>
																					<source src="{{ asset('public/img/video_gallery/'.$video->video.'') }}" type="video/mp4"/>
																					</video>
																				</a>
																				@endif
																				@endif
																				@endforeach
																			</div>
																		</div>
																	</div>
																</section>
																@endif
															</div>
														</div>

														<div class="modal fade profile-contact-form" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
															<div class="modal-dialog modal-dialog-centered" role="document">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title" id="exampleModalCenterTitle">Inquire for Availability</h5>
																		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>
																	<div class="modal-body">
																		<div class="form-errors"></div>
																		<form id="contact_form" method="post" enctype="multipart/form-data">
																			@csrf
																			<input type="hidden" name="to_user" value="{{ $user->id }}">
																			<div class="form-item">
																				<input type="text" name="name" placeholder="Full Name*">
																			</div>
																			<div class="form-item">
																				<input type="text" name="company" placeholder="Company*">
																			</div>
																			<div class="form-item">
																				<input type="email" name="email" placeholder="Email Address *">
																			</div>
																			<div class="form-item">
																				<input type="text" name="subject" placeholder="Subject*">
																			</div>
                        {{-- <div class="form-item">
                            <input type="text" name="mobile" placeholder="Enter Your Contact number">
                          </div> --}}
                          <div class="form-item">
                          	<textarea name="message" placeholder="Message*"></textarea>
                          </div>
                          <div class="form-item">
                          	<input type="text" name="instagram" placeholder="Instagram">
                          </div>
                          <div class="form-item">
                          	<input type="text" name="facebook" placeholder="Facebook">
                          </div>
                          <div class="form-item">
                          	<input type="text" name="linkedin" placeholder="LinkedIn">
                          </div>
                          <div class="form-item">
                          	<label class="custom-file">
                          		<input type="file" name="photo" class="custom-file-input" id="photo_yourself">
                          		<span class="custom-file-control">Upload Photo of Yourself</span>
                          	</label>
                          	<div id="post_image_div"></div>
                          </div>
                          <div class="form-action">
                          	<button class="btn" type="button" id="contact_btn">Send Message</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="modal fade profile-contact-form" id="reportModalCenter" tabindex="-1" role="dialog" aria-labelledby="reportModalCenterTitle" aria-hidden="true">
                	<div class="modal-dialog modal-dialog-centered" role="document">
                		<div class="modal-content">
                			<div class="modal-header">
                				<h5 class="modal-title" id="reportModalCenterTitle">Report User</h5>
                				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                					<span aria-hidden="true">&times;</span>
                				</button>
                			</div>
                			<div class="modal-body">
                				<div class="report_form-errors"></div>
                				<form id="report_form" method="post">
                					@csrf
                					<input type="hidden" name="friend_id" id="report_friend_id" value="">
                					<div class="form-item">
                						<textarea name="reason" placeholder="Type Reason here..."></textarea>
                					</div>
                					<div class="form-action">
                						<button class="btn" type="button" id="report_btn">Report</button>
                					</div>
                				</form>
                			</div>
                		</div>
                	</div>
                </div>

                <div class="modal fade profile-contact-form" id="alreadyReportModalCenter" tabindex="-1" role="dialog" aria-labelledby="alreadyReportModalCenterTitle" aria-hidden="true">
                	<div class="modal-dialog modal-dialog-centered" role="document">
                		<div class="modal-content">
                			<div class="modal-body" style="text-align: center;color:#fff;">
                				<h4> You already reported this user ! </h4>
                			</div>
                		</div>
                	</div>
                </div>

                @include('include.footer')
                @endsection


                @section('after-styles')
                <link rel="stylesheet" href="{{ asset('/public/front/css/fancybox.min.css') }}">
                @endsection
                @section('after-scripts')
                <script src="{{ asset('public/front/js/thirdparty/jquery.fancybox.min.js') }}"></script>
                <script>
                	var config = {
                		routes: {
                			increase_personality: '{{ route('increase.personality') }}',
                			contact_form: '{{ route('contact.form') }}',
                			report_network_user: '{{ route('report.network.user') }}',
                			add_friend: '{{ route('add.friend') }}',
                			confirm_friend: '{{ route('confirm.friend') }}',
                			remove_friend: '{{ route('remove.friend') }}',
                		}
                	};
                </script>
                <script src="{{ asset('public/front/js/profile-details.js?v=4.0.0') }}"></script>
                <script>
                	function likeprofile() {
                		$('#loading').show();
                		var id = $('#profile_id').val();
                		$('.like-error').html('');
                		$.ajax({
                			headers: {
                				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                			},
                			url: '{{ route('like') }}',
                			type: 'POST',
                			dataType: 'json',
                			data: {'id': id},
                			success: function (data) {
                				$('#loading').hide();
                				if (data.status == 0) {
                					$('.like-error').html(data.msg);
                					setTimeout(function () {
                						$('.like-error').html('');
                					}, 5000);

                				} else {

                					if (data.status == 1) {
                						@if($user->user_type=='1')
                						var image = '<img src="{{ asset('public/front/images/actor_details.svg') }}" height="50" width="50">';
                						@endif
                						@if($user->user_type=='2')
                						var image = '<img src="{{ asset('public/front/images/actor_details.svg') }}"  height="50" width="50">';
                						@endif
                						@if($user->user_type=='3')
                						var image = '<img src="{{ asset('public/front/images/actor_details.svg') }}"  height="50" width="50">';
                						@endif
                						@if($user->user_type=='4')
                						var image = '<img src="{{ asset('public/front/images/actor_details.svg') }}"  height="50" width="50">';
                						@endif
                						$('.likeprofile').html('' + image + '');
                						$('.profile-like-count').html('(' + data.likecount + ')');
                						$('.likeprofile').attr('onclick', 'unlikeprofile()');
                					} else {
                						$('.likeprofile').html('<i class="far fa-thumbs-up"></i>');
                					}
                				}

                			},
                		});
                	}

                	function unlikeprofile() {
                		$('#loading').show();
                		var id = $('#profile_id').val();
                		$('.like-error').html('');
                		$.ajax({
                			headers: {
                				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                			},
                			url: '{{ route('unlike') }}',
                			type: 'POST',
                			dataType: 'json',
                			data: {'id': id},
                			success: function (data) {
                				$('#loading').hide();
                				if (data.status == 1) {
                					@if($user->user_type=='1')
                					var image = '<img src="{{ asset('public/front/images/actor_details2.svg') }}" height="50" width="50">';
                					@endif
                					@if($user->user_type=='2')
                					var image = '<img src="{{ asset('public/front/images/actor_details2.svg') }}"  height="50" width="50">';
                					@endif
                					@if($user->user_type=='3')
                					var image = '<img src="{{ asset('public/front/images/actor_details2.svg') }}"  height="50" width="50">';
                					@endif
                					@if($user->user_type=='4')
                					var image = '<img src="{{ asset('public/front/images/actor_details2.svg') }}"  height="50" width="50">';
                					@endif
                					$('.likeprofile').html('' + image + '');
                					$('.profile-like-count').html('(' + data.likecount + ')');
                					$('.likeprofile').attr('onclick', 'likeprofile()');
                				} else {
                					$('.likeprofile').html('<i class="fas fa-thumbs-up"></i>');
                				}
                			},
                		});
                	}
                </script>
                @endsection
