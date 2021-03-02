@extends('layouts.app')

@section('content')
@include('include.header')
<link rel="stylesheet" href="{{ asset('public/front/css/select2.min.css?v=4.0.0') }}">
<link rel="stylesheet" href="{{ asset('public/front/css/croppie.min.css?v=4.0.0') }}">
<div class="profile-image-preview">
	<div class="content">
		<div class="image-header">
			<h4 class="title">Upload Image</h4>
			<button type="button" id="close_image_crop" class="close">&times;</button>
		</div>
		<div class="image-wrap">
			<div id="upload-demo"></div>
			<div id="preview-crop-image"></div>
		</div>
		<div class="image-footer">
			<button class="btn btn-primary upload-image">Upload Image</button>
		</div>
	</div>
</div>

<div class="main edit-profile-page">
	<div class="custom-container">
		<div class="name-header">
			<h2>Edit Profile</h2>
		</div>
		@include('admin.include.message')
		<div id="error-msg"></div>
		<div class="profile-wrap actor">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="{{ route('profile') }}" id="profile_add" class="btn">Profile</a>
				</li>
				<li>
					<a href="{{ route('photo_gallery') }}" class="btn">Photo Gallery</a>
				</li>
				<li>
					<a href="{{ route('video_gallery') }}" class="btn">Video Gallery</a>
				</li>
			</ul>
			<div class="tab-content">
				<div id="profile" class="tab-pane fade in active show">
					<div class="row">
						<div class="col-md-4 profile-picture-wrap">
							@if(isset(Auth::user()->profile_picture) && Auth::user()->profile_picture!='')
							<img src="{{ asset('public/img/profile_picture/'.Auth::user()->profile_picture.'') }}" alt="profile-pic" id="profile_img">
							@else

							<img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
							@endif
							<h6 class="m-t-2">Upload a Profile Picture</h6>
							<label class="custom-file">
								<input type="file" id="image" class="custom-file-input" name="profile_picture">
								<span class="custom-file-control">Choose file</span>
							</label>
							<br/>
                            <span id="profile-dimension-error" style="color: red;"></span>
							<span id="profile-pic-error" style="color:red;display: none;">Please upload only png,jpg,jpeg format files.</span>
							<div class="sample_cv_templte">
								<a href="{{ asset('public/cv/template.docx') }}" alt="cv" id="sc" target="_blank" style="color: #606060;"><b>Download CV Template</b> <i
									class="fas fa-download"></i></a>
								</div>
								<h6 class="m-t-2">Upload CV</h6>
								<label class="custom-file">
									<input type="file" id="user_cv" class="custom-file-input" name="user_cv">
									<span class="custom-file-control">Choose file</span>
								</label>
								<div style="color: red;display: none;" id="cv_error">Please upload only pdf,doc,docx,ppt format files.</div>

								@if(isset(Auth::user()->cv) && Auth::user()->cv!='')
								<div class="download_cv">
									<a href="{{ asset('public/cv/'.Auth::user()->cv.'') }}" alt="cv" id="sc" target="_blank" style="color: #606060;"><b>Download CV</b> <i
										class="fas fa-download"></i></a>
									</div>
									@endif
								</div>
								<div class="col-md-8  personal-info">
									<form role="form" action="{{ route('update-profile') }}" method="post" id="edit_profile_form">
										@csrf
										<h3>Basic Details</h3>
										<hr/>
										<input type="hidden" name="profile_picture" id="profile_picture_hidden_field" value="{{ Auth::user()->profile_picture }}">
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label required">First name</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" name="first_name"
												value="{{ old('first_name' . Auth::user()->first_name, Auth::user()->first_name) }}"/>
												@if ($errors->has('first_name'))
												<span class="text-danger">{{ $errors->first('first_name') }}</span>
												@endif
											</div>
										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label required">Last name</label>
											<div class="col-lg-9">
												<input class="form-control" type="text" name="last_name"
												value="{{ old('last_name' . Auth::user()->last_name, Auth::user()->last_name) }}"/>
												@if ($errors->has('last_name'))
												<span class="text-danger">{{ $errors->first('last_name') }}</span>
												@endif
											</div>

										</div>
										<div class="form-group row">
											<label class="col-lg-3 col-form-label form-control-label">Username</label>
											<label class="col-lg-9 col-form-label form-control-label">{{ Auth::user()->username }}</label>
       {{-- <div class="col-lg-9">
    <input class="form-control" type="text" name="username" value="{{ Auth::user()->username }}" />
   </div> --}}
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Email</label>
  	<label class="col-lg-9 col-form-label form-control-label">{{ Auth::user()->email }}</label>
  {{-- <div class="col-lg-9">
<input class="form-control" type="email" name="email" value="{{ Auth::user()->email }}" readonly="" disabled="" />
</div> --}}
</div>
<div class="form-group row">
	<label class="col-lg-3 col-form-label form-control-label">User Type</label>
	<label class="col-lg-9 col-form-label form-control-label">{{ getUserTypeValue(Auth::user()->user_type) }}</label>
   {{-- <div class="col-lg-9">
    <input class="form-control" type="email" name="email" value="{{ Auth::user()->email }}" readonly="" disabled="" />
   </div> --}}
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">City</label>
  	<div class="col-lg-9">
  		<input class="form-control" type="text" name="city" value="{{ old('city' . Auth::user()->city, Auth::user()->city) }}"
  		placeholder="City"/>
  		@if ($errors->has('city'))
  		<span class="text-danger">{{ $errors->first('city') }}</span>
  		@endif
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Country</label>
  	<div class="col-lg-9">
  		{{-- 		<input class="form-control" type="text" name="country" value="{{ old('country' . Auth::user()->country, Auth::user()->country) }}" placeholder="country" />  --}}
  		<select name="country" id="country-thread" class="form-control">
  			<option value=''>- Search Country -</option>
  		</select>
  		@if ($errors->has('country'))
  		<span class="text-danger">{{ $errors->first('country') }}</span>
  		@endif
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Instagram Link</label>
  	<div class="col-lg-9">
  		<input class="form-control" type="text" name="instagram_link"
  		value="{{ old('instagram_link' . Auth::user()->instagram_link, Auth::user()->instagram_link) }}" placeholder="Instagram link"/>
  		<span style="color: #c6cdd4;">e.g. -> https://www.instagram.com/your-page-name</span><br/>
  		<span class="text-danger" id="insta-error" style="display: none;">Please Enter Valid Instagram Link</span>
  		@if ($errors->has('instagram_link'))
  		<span class="text-danger">{{ $errors->first('instagram_link') }}</span>
  		@endif
  	</div>
  </div>
  <hr/>
  <h3>Personal Details</h3>
  <hr/>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Other Professions</label>
  	<div class="col-lg-9">
  		<select name="other_professions[]" multiple class="profession-select">
  			<option value="">--Select Professions--</option>
  			@php
  			/** @var $professions */
  			$professions=["Actor"=>"Actor","Artist"=>"Artist","Choreographer"=>"Choreographer", "Cinematographer"=>"Cinematographer", "Composer"=>"Composer", "Director"=>"Director",
  			"Editor"=>"Editor", "Make Up Artist"=>"Make Up Artist", "Model"=>"Model", "Musician"=>"Musician",
  			"Photographer"=>"Photographer", "Sound, Light, Effects, Design"=>"Sound, Light, Effects, Design",
  			"Writer"=>"Writer"];
  			@endphp

  			@foreach ($professions as $profession)
  			@php
  			$my_professions=json_decode(Auth::user()->other_professions);
  			$sel = '';
  			if (isset($my_professions) && in_array($profession, $my_professions)){
  				$sel = ' selected="selected" ';
  			}
  			@endphp
  			@if(Auth::user()->user_type==1 && $profession=='Actor')
  			@elseif(Auth::user()->user_type==2 && $profession=='Model')
  			@elseif(Auth::user()->user_type==3 && $profession=='Musician')
  			@else
  			<option value="{!! $profession !!}" {!! $sel !!}>{!! $profession !!}</option>
  			@endif
  			@endforeach
  		</select>
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label required">Gender</label>
  	<div class="col-lg-9">
  		<select name="gender" class="js-example-basic-single form-control">
  			<option value="male" @if(Auth::user()->gender=='male') selected="" @endif>Male</option>
  			<option value="female" @if(Auth::user()->gender=='female') selected="" @endif>Female</option>
  			<option value="no_to_ans" @if(Auth::user()->gender=='no_to_ans') selected="" @endif>Prefer not to answer</option>
  		</select>
  		@if ($errors->has('gender'))
  		<span class="text-danger">{{ $errors->first('gender') }}</span>
  		@endif
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Status</label>
  	<div class="col-lg-9">
  		<input type="radio" name="status" @if(Auth::user()->status=='1') checked="" @endif value="1"/>Active
  		<input type="radio" name="status" @if(Auth::user()->status=='0') checked="" @endif value="0"/>Inactive
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label required">Height</label>
  	<div class="col-lg-9">
  		{{--                                            <input class="form-control" type="text" name="height" value="{{ old('height' . Auth::user()->height, Auth::user()->height) }}"--}}
  		{{--                                                   placeholder="Height"/>--}}
  		<select name="height" class="js-example-basic-single form-control">
  			<option value="">Height</option>
  			<option value="4’9”" @if(Auth::user()->height=='4’9”') selected="" @endif>4’9”</option>
  			<option value="4’10”" @if(Auth::user()->height=='4’10”') selected="" @endif>4’10”</option>
  			<option value="4’11”" @if(Auth::user()->height=='4’11”') selected="" @endif>4’11”</option>
  			<option value="5’0”" @if(Auth::user()->height=='5’0”') selected="" @endif>5’0”</option>
  			<option value="5’1”" @if(Auth::user()->height=='5’1”') selected="" @endif>5’1”</option>
  			<option value="5’2”" @if(Auth::user()->height=='5’2”') selected="" @endif>5’2”</option>
  			<option value="5’3”" @if(Auth::user()->height=='5’3”') selected="" @endif>5’3”</option>
  			<option value="5’4”" @if(Auth::user()->height=='5’4”') selected="" @endif>5’4”</option>
  			<option value="5’5”" @if(Auth::user()->height=='5’5”') selected="" @endif>5’5”</option>
  			<option value="5’6”" @if(Auth::user()->height=='5’6”') selected="" @endif>5’6”</option>
  			<option value="5’7”" @if(Auth::user()->height=='5’7”') selected="" @endif>5’7”</option>
  			<option value="5’8”" @if(Auth::user()->height=='5’8”') selected="" @endif>5’8”</option>
  			<option value="5’9”" @if(Auth::user()->height=='5’9”') selected="" @endif>5’9”</option>
  			<option value="5’10”" @if(Auth::user()->height=='5’10”') selected="" @endif>5’10”</option>
  			<option value="5’11”" @if(Auth::user()->height=='5’11”') selected="" @endif>5’11”</option>
  			<option value="6’0”" @if(Auth::user()->height=='6’0”') selected="" @endif>6’0”</option>
  			<option value="6’1”" @if(Auth::user()->height=='6’1”') selected="" @endif>6’1”</option>
  			<option value="6’2”" @if(Auth::user()->height=='6’2”') selected="" @endif>6’2”</option>
  			<option value="6’3”" @if(Auth::user()->height=='6’3”') selected="" @endif>6’3”</option>
  			<option value="6’4”" @if(Auth::user()->height=='6’4”') selected="" @endif>6’4”</option>
  			<option value="6’5”" @if(Auth::user()->height=='6’5”') selected="" @endif>6’5”</option>
  			<option value="6’6”" @if(Auth::user()->height=='6’6”') selected="" @endif>6’6”</option>
  		</select>
  		@if ($errors->has('height'))
  		<span class="text-danger">{{ $errors->first('height') }}</span>
  		@endif
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label required">Eye colour</label>
  	<div class="col-lg-9">
  		{{-- <input class="form-control" type="text" name="eye_colour" value="{{ old('eye_colour' . Auth::user()->eye_colour, Auth::user()->eye_colour) }}" placeholder="Eye colour" /> --}}
  		<select name="eye_colour" class="js-example-basic-single form-control ">
  			<option value="">Eye colour</option>
  			@php
  			$default_eye_colours=['amber', 'blue', 'brown', 'gray', 'green', 'hazel', 'Other'];
  			@endphp
  			@foreach($eye_colours as $eye_colour)
  			@if(!in_array($eye_colour,$default_eye_colours) && $eye_colour!='')
  			<option value="{{ $eye_colour }}" @if(Auth::user()->eye_colour==$eye_colour) selected="" @endif>{{ $eye_colour }}</option>
  			@endif
  			@endforeach
  			<option value="amber" @if(Auth::user()->eye_colour=='amber') selected="" @endif>Amber</option>
  			<option value="blue" @if(Auth::user()->eye_colour=='blue') selected="" @endif>Blue</option>
  			<option value="brown" @if(Auth::user()->eye_colour=='brown') selected="" @endif>Brown</option>
  			<option value="gray" @if(Auth::user()->eye_colour=='gray') selected="" @endif>Gray</option>
  			<option value="green" @if(Auth::user()->eye_colour=='green') selected="" @endif>Green</option>
  			<option value="hazel" @if(Auth::user()->eye_colour=='hazel') selected="" @endif>Hazel</option>
  			<option value="other" @if(Auth::user()->eye_colour=='other') selected="" @endif>Other</option>
  		</select>
  		@if ($errors->has('eye_colour'))
  		<span class="text-danger">{{ $errors->first('eye_colour') }}</span>
  		@endif
  	</div>
  </div>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Hair Colour</label>
  	<div class="col-lg-9">
  		{{-- <input class="form-control" type="text" name="hair_colour" value="{{ old('hair_colour' . Auth::user()->hair_colour, Auth::user()->hair_colour) }}" placeholder="Hair colour" /> --}}
  		<select name="hair_colour" class="js-example-basic-single form-control">
  			<option value="">Hair Colour</option>
  			@php
  			$default_hair_colours=['Jet Black', 'Brunette', 'Blonde', 'Grey', 'Ginger', 'White', 'Other'];
  			@endphp
  			@foreach($hair_colours as $hair_colour)
  			@if(!in_array($hair_colour,$default_hair_colours) && $hair_colour!='')
  			<option value="{{ $hair_colour }}"
  			@if(Auth::user()->hair_colour==$hair_colour) selected="" @endif>{{ $hair_colour }}</option>
  			@endif
  			@endforeach
  			<option value="Jet Black" @if(Auth::user()->hair_colour=='Jet Black') selected="" @endif>Jet Black</option>
  			<option value="Brunette" @if(Auth::user()->hair_colour=='Brunette') selected="" @endif>Brunette</option>
  			<option value="Blonde" @if(Auth::user()->hair_colour=='Blonde') selected="" @endif>Blonde</option>
  			<option value="Grey" @if(Auth::user()->hair_colour=='Grey') selected="" @endif>Grey</option>
  			<option value="Ginger" @if(Auth::user()->hair_colour=='Ginger') selected="" @endif>Ginger</option>
  			<option value="White" @if(Auth::user()->hair_colour=='White') selected="" @endif>White</option>
  			<option value="other" @if(Auth::user()->hair_colour=='other') selected="" @endif>Other</option>
  		</select>
  		@if ($errors->has('hair_colour'))
  		<span class="text-danger">{{ $errors->first('hair_colour') }}</span>
  		@endif
  	</div>
  </div>
  @if(Auth::user()->user_type=='3')
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Instrument</label>
  	<div class="col-lg-9">
  		@php
  		$instruments=json_decode(Auth::user()->instrument);
  		@endphp
  		<select name="instrument[]" multiple class="js-example-basic-single form-control">
  			<option value="Electric Guitar" @if(isset($instruments) && in_array('Electric Guitar',$instruments)) selected="" @endif>Electric
  				Guitar
  			</option>
  			<option value="Keyboard" @if(isset($instruments) && in_array('Keyboard',$instruments)) selected="" @endif>Keyboard</option>
  			<option value="Piano" @if(isset($instruments) && in_array('Piano',$instruments)) selected="" @endif>Piano</option>
  			<option value="Guitar" @if(isset($instruments) && in_array('Guitar',$instruments)) selected="" @endif>Guitar</option>
  			<option value="Drums" @if(isset($instruments) && in_array('Drums',$instruments)) selected="" @endif>Drums</option>
  			<option value="Violin" @if(isset($instruments) && in_array('Violin',$instruments)) selected="" @endif>Violin</option>
  			<option value="Saxophone" @if(isset($instruments) && in_array('Saxophone',$instruments)) selected="" @endif>Saxophone</option>
  			<option value="Flute" @if(isset($instruments) && in_array('Flute',$instruments)) selected="" @endif>Flute</option>
  			<option value="Cello" @if(isset($instruments) && in_array('Cello',$instruments)) selected="" @endif>Cello</option>
  			<option value="Clarinet" @if(isset($instruments) && in_array('Clarinet',$instruments)) selected="" @endif>Clarinet</option>
  			<option value="Sitar" @if(isset($instruments) && in_array('Sitar',$instruments)) selected="" @endif>Sitar</option>
  		</select>
  		@if ($errors->has('instrument'))
  		<span class="text-danger">{{ $errors->first('instrument') }}</span>
  		@endif
  	</div>
  </div>
  @endif
  <hr/>
  <h3>Other Details</h3>
  <hr/>
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Caption</label>
  	<div class="col-lg-9">
  		<textarea class="form-control" type="text" name="caption" placeholder="Caption" onkeyup="countChar(this)">{{ old('caption' .
  		Auth::user()->caption, Auth::user()->caption) }}</textarea>
  		<div id="charNum"></div>
  	</div>
  </div>
{{--  <div class="form-group row">--}}
{{--  	<label class="col-lg-3 col-form-label form-control-label">Main career highlight</label>--}}
{{--  	<div class="col-lg-9">--}}
{{--  		<textarea name="experience" class="form-control" placeholder="Main career highlight">{{ old('experience' . Auth::user()->experience,--}}
{{--  		Auth::user()->experience) }}</textarea>--}}
{{--  	</div>--}}
{{--  </div>--}}
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Biography</label>
  	<div class="col-lg-9">
  		<textarea name="biography" class="form-control" placeholder="Biography">{{ old('biography' . Auth::user()->biography, Auth::user()
  		->biography) }}</textarea>
  	</div>
  </div>

  {{-- @if(Auth::user()->user_type=='1') --}}
  @if(Auth::user()->user_type!=4)
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label">Playing Age</label>
  	<div class="col-lg-9">
  		{{-- <input class="form-control" type="text" name="playing_age"
  		value="{{ old('playing_age' . Auth::user()->playing_age, Auth::user()->playing_age) }}"
  		placeholder="Playing Age (Ex : 12 - 20)"/> --}}
  		<select name="playing_age" class="js-example-basic-single form-control">
  			<option value="">Playing Age</option>
  			<option value="16-21" @if(Auth::user()->playing_age=='16-21') selected="" @endif>16-21</option>
  			<option value="21-25" @if(Auth::user()->playing_age=='21-25') selected="" @endif>21-25</option>
  			<option value="25-30" @if(Auth::user()->playing_age=='25-30') selected="" @endif>25-30</option>
  			<option value="30-35" @if(Auth::user()->playing_age=='30-35') selected="" @endif>30-35</option>
  			<option value="35-40" @if(Auth::user()->playing_age=='35-40') selected="" @endif>35-40</option>
  			<option value="40-45" @if(Auth::user()->playing_age=='40-45') selected="" @endif>40-45</option>
  			<option value="45-50" @if(Auth::user()->playing_age=='45-50') selected="" @endif>45-50</option>
  			<option value="Other" @if(Auth::user()->playing_age=='Other') selected="" @endif>Other</option>
  		</select>
  		@if ($errors->has('playing_age'))
  		<span class="text-danger">{{ $errors->first('playing_age') }}</span>
  		@endif
  	</div>
  </div>
  @endif
  {{-- @endif --}}
  <div class="form-group row">
  	<label class="col-lg-3 col-form-label form-control-label required">Languages</label>
  	<div class="col-lg-9">
  		<select name="languages[]" multiple class="js-example-basic-single form-control">
  			@php
  			$userlang=json_decode(Auth::user()->languages);
  			@endphp
  			@foreach($languages as $language)
  			<option value="{{ $language->language }}"
  				@if(isset($userlang) && in_array($language->language,$userlang)) selected="" @endif>{{ $language->language }}</option>
  				@endforeach
  			</select>
  			@if ($errors->has('languages'))
  			<span class="text-danger">{{ $errors->first('languages') }}</span>
  			@endif
  		</div>
  	</div>
  	<div class="form-group row">
  		<label class="col-lg-3 col-form-label form-control-label required">Skills</label>
  		<div class="col-lg-9">
  			<select name="skills[]" multiple class="js-example-basic-single form-control">
  				@php
  				$userskills=json_decode(Auth::user()->skills);
  				@endphp
  				@foreach($skills as $skill)
  				<option value="{{ $skill->skill }}"
  					@if(isset($userskills) && in_array($skill->skill,$userskills)) selected="" @endif>{{ $skill->skill }}</option>
  					@endforeach
  				</select>
  				@if ($errors->has('skills'))
  				<span class="text-danger">{{ $errors->first('skills') }}</span>
  				@endif
  			</div>
  		</div>
  		<div class="form-group row">
  			<label class="col-lg-3 col-form-label form-control-label required">Genre</label>
  			<div class="col-lg-9">
  				<select name="genre[]" multiple class="js-example-basic-single form-control">
  					@php
  					$userGenre=json_decode(Auth::user()->genre);
  					@endphp
  					{{--                                                @foreach($genres as $genre)--}}
  					{{--                                                    <option value="{{ $genre->genre }}"--}}
  						{{--                                                            @if(isset($userGenre) && in_array($genre->genre,$userGenre)) selected="" @endif>{{ $genre->genre }}</option>--}}
  						{{--                                                @endforeach--}}
  						<option value="Action" @if(isset($userGenre) && in_array('Action',$userGenre)) selected="" @endif>Action</option>
  						<option value="Animation" @if(isset($userGenre) && in_array('Animation',$userGenre)) selected="" @endif>Animation</option>
  						<option value="Comedy" @if(isset($userGenre) && in_array('Comedy',$userGenre)) selected="" @endif>Comedy</option>
  						<option value="Crime" @if(isset($userGenre) && in_array('Crime',$userGenre)) selected="" @endif>Crime</option>
  						<option value="Drama" @if(isset($userGenre) && in_array('Drama',$userGenre)) selected="" @endif>Drama</option>
  						<option value="Experimental" @if(isset($userGenre) && in_array('Experimental',$userGenre)) selected="" @endif>Experimental</option>
  						<option value="Fantasy" @if(isset($userGenre) && in_array('Fantasy',$userGenre)) selected="" @endif>Fantasy</option>
  						<option value="Historical" @if(isset($userGenre) && in_array('Historical',$userGenre)) selected="" @endif>Historical</option>
  						<option value="Horror" @if(isset($userGenre) && in_array('Horror',$userGenre)) selected="" @endif>Horror</option>
  						<option value="Romance" @if(isset($userGenre) && in_array('Romance',$userGenre)) selected="" @endif>Romance</option>
  						<option value="Sci-fi" @if(isset($userGenre) && in_array('Sci-fi',$userGenre)) selected="" @endif>Sci-fi</option>
  						<option value="Thriller" @if(isset($userGenre) && in_array('Thriller',$userGenre)) selected="" @endif>Thriller</option>
  						<option value="Western" @if(isset($userGenre) && in_array('Western',$userGenre)) selected="" @endif>Western</option>
  						<option value="Musical Theatre" @if(isset($userGenre) && in_array('Musical Theatre',$userGenre)) selected="" @endif>Musical Theatre
  						</option>
  						<option value="Jazz" @if(isset($userGenre) && in_array('Jazz',$userGenre)) selected="" @endif>Jazz</option>
  						<option value="Rock" @if(isset($userGenre) && in_array('Rock',$userGenre)) selected="" @endif>Rock</option>
  						<option value="Hip-Hop" @if(isset($userGenre) && in_array('Hip-Hop',$userGenre)) selected="" @endif>Hip-Hop</option>
  						<option value="Classical" @if(isset($userGenre) && in_array('Classical',$userGenre)) selected="" @endif>Classical</option>
  						<option value="Pop" @if(isset($userGenre) && in_array('Pop',$userGenre)) selected="" @endif>Pop</option>
  						<option value="Soul" @if(isset($userGenre) && in_array('Soul',$userGenre)) selected="" @endif>Soul</option>
  						<option value="Instrumental" @if(isset($userGenre) && in_array('Instrumental',$userGenre)) selected="" @endif>Instrumental</option>
  					</select>
  					@if ($errors->has('genre'))
  					<span class="text-danger">{{ $errors->first('genre') }}</span>
  					@endif
  				</div>
  			</div>
  			@if(Auth::user()->user_type==1 || Auth::user()->user_type==2)
  			<div class="form-group row">
  				<label class="col-lg-3 col-form-label form-control-label">Accents</label>
  				<div class="col-lg-9">
  					<select name="acents[]" multiple class="js-example-basic-single form-control">
  						@php
  						$userAccent=json_decode(Auth::user()->acents);
  						@endphp
  						{{--                                                    @foreach($accents as $accent)--}}
  						{{--                                                        <option value="{{ $accent->accent }}"--}}
  							{{--                                                                @if(isset($userAccent) && in_array($accent->accent,$userAccent)) selected="" @endif>{{ $accent->accent }}</option>--}}
  							{{--                                                    @endforeach--}}
  							<option value="Irish" @if(isset($userAccent) && in_array('Irish',$userAccent)) selected="" @endif>Irish</option>
  							<option value="Welsh" @if(isset($userAccent) && in_array('Welsh',$userAccent)) selected="" @endif>Welsh</option>
  							<option value="Scottish" @if(isset($userAccent) && in_array('Scottish',$userAccent)) selected="" @endif>Scottish</option>
  							<option value="American" @if(isset($userAccent) && in_array('American',$userAccent)) selected="" @endif>American</option>
  							<option value="British" @if(isset($userAccent) && in_array('British',$userAccent)) selected="" @endif>British</option>
  							<option value="Indian" @if(isset($userAccent) && in_array('Indian',$userAccent)) selected="" @endif>Indian</option>
  							<option value="Australian" @if(isset($userAccent) && in_array('Australian',$userAccent)) selected="" @endif>Australian</option>
  							<option value="South African" @if(isset($userAccent) && in_array('South African',$userAccent)) selected="" @endif>South
  								African
  							</option>
  							<option value="South American" @if(isset($userAccent) && in_array('South American',$userAccent)) selected="" @endif>South
  								American
  							</option>
  							<option value="Italian" @if(isset($userAccent) && in_array('Italian',$userAccent)) selected="" @endif>Italian</option>
  							<option value="French" @if(isset($userAccent) && in_array('French',$userAccent)) selected="" @endif>French</option>
  							<option value="Spanish" @if(isset($userAccent) && in_array('Spanish',$userAccent)) selected="" @endif>Spanish</option>
  							<option value="Kiwi" @if(isset($userAccent) && in_array('Kiwi',$userAccent)) selected="" @endif>Kiwi</option>
  							<option value="Canadian" @if(isset($userAccent) && in_array('Canadian',$userAccent)) selected="" @endif>Canadian</option>
  							<option value="Russian" @if(isset($userAccent) && in_array('Russian',$userAccent)) selected="" @endif>Russian</option>
  						</select>
  					</div>
  				</div>
  				@endif
  				@if(Auth::user()->user_type!=4)
  				<div class="form-group row">
  					<label class="col-lg-3 col-form-label form-control-label">Role types</label>
  					<div class="col-lg-9">
  						<select name="role_type[]" multiple class="js-example-basic-single form-control">
  							@php
  							$userRoleType=json_decode(Auth::user()->role_type);
  							@endphp
  							@foreach($roletypes as $roletype)
  							<option value="{{ $roletype->role }}"
  								@if(isset($userRoleType) && in_array($roletype->role,$userRoleType)) selected="" @endif>{{ $roletype->role }}</option>
  								@endforeach
  							</select>
  						</div>
  					</div>
  					@endif
  					<hr/>

  					@if(Auth::user()->user_type=='1')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite Films</label>
  						<div class="col-lg-9">
  							@if(isset(Auth::user()->favourite_films) && Auth::user()->favourite_films!=null)
  							@php
  							$i=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->favourite_films) as $key=>$film)
  							<label class="col-form-label form-control-label">Favourite Films {{ $i }}</label>
  							<input class="form-control" type="text" name="favourite_films[{{ $key }}][name]"
  							value="@if(isset($film->name)){{ $film->name }} @endif" placeholder="Films Name"/><br/>
  							<input class="form-control" type="text" name="favourite_films[{{ $key }}][link]"
  							value="@if(isset($film->link)){{ $film->link }} @endif" placeholder="Films Link"/><br/>
  							@php
  							$i++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Films 1</label>
  							<input class="form-control" type="text" name="favourite_films[film1][name]" value="" placeholder="Film Name"/><br/>
  							<input class="form-control" type="text" name="favourite_films[film1][link]" value="" placeholder="Film Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Films 2</label>
  							<input class="form-control" type="text" name="favourite_films[film2][name]" value="" placeholder="Film Name"/><br/>
  							<input class="form-control" type="text" name="favourite_films[film2][link]" value="" placeholder="Film Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Films 3</label>
  							<input class="form-control" type="text" name="favourite_films[film3][name]" value="" placeholder="Film Name"/><br/>
  							<input class="form-control" type="text" name="favourite_films[film3][link]" value="" placeholder="Film Link"/><br/>
  							@endif
  						</div>
  					</div>
  					<hr/>
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite Directors</label>
  						<div class="col-lg-9">
  							@if(isset(Auth::user()->favourite_directors) && Auth::user()->favourite_directors!=null)
  							@php
  							$f=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->favourite_directors) as $key=>$director)
  							<label class="col-form-label form-control-label">Favourite Director {{ $f }} </label>
  							<input class="form-control" type="text" name="favourite_directors[{{ $key }}][name]"
  							value="@if(isset($director->name)){{ $director->name }} @endif" placeholder="Director Name"/><br/>
  							<input class="form-control" type="text" name="favourite_directors[{{ $key }}][link]"
  							value="@if(isset($director->link)){{ $director->link }} @endif" placeholder="Director Link"/><br/>
  							@php
  							$f++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Director 1</label>
  							<input class="form-control" type="text" name="favourite_directors[director1][name]" value="" placeholder="Director Name"/><br/>
  							<input class="form-control" type="text" name="favourite_directors[director1][link]" value="" placeholder="Director Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Director 2</label>
  							<input class="form-control" type="text" name="favourite_directors[director2][name]" value="" placeholder="Director Name"/><br/>
  							<input class="form-control" type="text" name="favourite_directors[director2][link]" value="" placeholder="Director Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Director 3</label>
  							<input class="form-control" type="text" name="favourite_directors[director3][name]" value="" placeholder="Director Name"/><br/>
  							<input class="form-control" type="text" name="favourite_directors[director3][link]" value="" placeholder="Director Link"/><br/>
  							@endif
  						</div>
  					</div>
  					<hr/>
  					@endif


  					@if(Auth::user()->user_type=='2')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite Models</label>
  						<div class="col-lg-9">
  							@if(isset(Auth::user()->favourite_models) && Auth::user()->favourite_models!=null)
  							@php
  							$i=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->favourite_models) as $key=>$model)
  							<label class="col-form-label form-control-label">Favourite Model {{ $i }}</label>
  							<input class="form-control" type="text" name="favourite_models[{{ $key }}][name]"
  							value="@if(isset($model->name)){{ $model->name }} @endif" placeholder="Model Name"/><br/>
  							<input class="form-control" type="text" name="favourite_models[{{ $key }}][link]"
  							value="@if(isset($model->link)){{ $model->link }} @endif" placeholder="Model Link"/><br/>
  							@php
  							$i++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Model 1</label>
  							<input class="form-control" type="text" name="favourite_models[model1][name]" value="" placeholder="Model Name"/><br/>
  							<input class="form-control" type="text" name="favourite_models[model1][link]" value="" placeholder="Model Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Model 2</label>
  							<input class="form-control" type="text" name="favourite_models[model2][name]" value="" placeholder="Model Name"/><br/>
  							<input class="form-control" type="text" name="favourite_models[model2][link]" value="" placeholder="Model Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Model 3</label>
  							<input class="form-control" type="text" name="favourite_models[model3][name]" value="" placeholder="Model Name"/><br/>
  							<input class="form-control" type="text" name="favourite_models[model3][link]" value="" placeholder="Model Link"/><br/>
  							@endif
  						</div>
  					</div>
  					<hr/>
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite Brands</label>
  						<div class="col-lg-9">
  							@if(isset(Auth::user()->favourite_brands) && Auth::user()->favourite_brands!=null)
  							@php
  							$f=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->favourite_brands) as $key=>$brand)
  							<label class="col-form-label form-control-label">Favourite Brand {{ $f }} </label>
  							<input class="form-control" type="text" name="favourite_brands[{{ $key }}][name]"
  							value="@if(isset($brand->name)){{ $brand->name }} @endif" placeholder="Brand Name"/><br/>
  							<input class="form-control" type="text" name="favourite_brands[{{ $key }}][link]"
  							value="@if(isset($brand->link)){{ $brand->link }} @endif" placeholder="Brand Link"/><br/>
  							@php
  							$f++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Brand 1</label>
  							<input class="form-control" type="text" name="favourite_brands[brand1][name]" value="" placeholder="Brand Name"/><br/>
  							<input class="form-control" type="text" name="favourite_brands[brand1][link]" value="" placeholder="Brand Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Brand 2</label>
  							<input class="form-control" type="text" name="favourite_brands[brand2][name]" value="" placeholder="Brand Name"/><br/>
  							<input class="form-control" type="text" name="favourite_brands[brand2][link]" value="" placeholder="Brand Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Brand 3</label>
  							<input class="form-control" type="text" name="favourite_brands[brand3][name]" value="" placeholder="Brand Name"/><br/>
  							<input class="form-control" type="text" name="favourite_brands[brand3][link]" value="" placeholder="Brand Link"/><br/>
  							@endif
  						</div>
  					</div>
  					<hr/>
  					@endif


  					@if(Auth::user()->user_type=='3')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite songs</label>
  						<div class="col-lg-9">

  							@if(isset(Auth::user()->top_songs) && Auth::user()->top_songs!=null)
  							@php
  							$f=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->top_songs) as $key=>$songs)
  							<label class="col-form-label form-control-label">Favourite Songs {{ $f }} </label>
  							<input class="form-control" type="text" name="top_songs[{{ $key }}][name]"
  							value="@if(isset($songs->name)){{ $songs->name }} @endif" placeholder="Song Name"/><br/>
  							<input class="form-control" type="text" name="top_songs[{{ $key }}][link]"
  							value="@if(isset($songs->link)){{ $songs->link }} @endif" placeholder="Song Link"/><br/>
  							@php
  							$f++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Songs 1</label>
  							<input class="form-control" type="text" name="top_songs[song1][name]" value="" placeholder="Song Name"/><br/>
  							<input class="form-control" type="text" name="top_songs[song1][link]" value="" placeholder="Song Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Songs 2</label>
  							<input class="form-control" type="text" name="top_songs[song2][name]" value="" placeholder="Song Name"/><br/>
  							<input class="form-control" type="text" name="top_songs[song2][link]" value="" placeholder="Song Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Songs 3</label>
  							<input class="form-control" type="text" name="top_songs[song3][name]" value="" placeholder="Song Name"/><br/>
  							<input class="form-control" type="text" name="top_songs[song3][link]" value="" placeholder="Song Link"/><br/>
  							@endif


  						</div>
  					</div>
  					<hr/>
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Top 3 Favourite Musicians</label>
  						<div class="col-lg-9">

  							@if(isset(Auth::user()->top_musicians) && Auth::user()->top_musicians!=null)
  							@php

  							$f=1;
  							@endphp
  							@foreach(json_decode(Auth::user()->top_musicians) as $key=>$musicians)
  							<label class="col-form-label form-control-label">Favourite Musicians {{ $f }} </label>
  							<input class="form-control" type="text" name="top_musicians[{{ $key }}][name]"
  							value="@if(isset($musicians->name)){{ $musicians->name }} @endif" placeholder="Musicians Name"/><br/>
  							<input class="form-control" type="text" name="top_musicians[{{ $key }}][link]"
  							value="@if(isset($musicians->link)){{ $musicians->link }} @endif" placeholder="Musicians Link"/><br/>
  							@php
  							$f++;
  							@endphp
  							@endforeach
  							@else
  							<label class="col-form-label form-control-label">Favourite Musicians 1</label>
  							<input class="form-control" type="text" name="top_musicians[musicians1][name]" value="" placeholder="Musicians Name"/><br/>
  							<input class="form-control" type="text" name="top_musicians[musicians1][link]" value="" placeholder="Musicians Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Musicians2</label>
  							<input class="form-control" type="text" name="top_musicians[musicians2][name]" value="" placeholder="Musicians Name"/><br/>
  							<input class="form-control" type="text" name="top_musicians[musicians2][link]" value="" placeholder="Musicians Link"/><br/>

  							<label class="col-form-label form-control-label">Favourite Musicians 3</label>
  							<input class="form-control" type="text" name="top_musicians[musicians3][name]" value="" placeholder="Musicians Name"/><br/>
  							<input class="form-control" type="text" name="top_musicians[musicians3][link]" value="" placeholder="Musicians Link"/><br/>
  							@endif
  						</div>
  					</div>
  					<hr/>
  					@endif

{{--  					<div class="form-group row">--}}
{{--  						<label class="col-lg-3 col-form-label form-control-label">Goals (100 characters)</label>--}}
{{--  						<div class="col-lg-9">--}}
{{--  							<textarea name="goals" class="form-control" placeholder="Goals (100 characters)"--}}
{{--  							maxlength="100">{{ old('goals' . Auth::user()->goals, Auth::user()->goals) }}</textarea>--}}
{{--  						</div>--}}
{{--  					</div>--}}
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Interests</label>
  						<div class="col-lg-9">
  							<textarea name="interests" class="form-control"
  							placeholder="Interests">{{ old('interests' . Auth::user()->interests, Auth::user()->interests) }}</textarea>
  						</div>
  					</div>
                       @php
                            $other_actor=0;
                            $other_model=0;
                            $other_musician=0;
                            if(Auth::user()->other_professions!=''){
                               $other_professions = json_decode(Auth::user()->other_professions,true);
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
                       @if(Auth::user()->user_type=='1' || Auth::user()->user_type=='2' || Auth::user()->user_type=='3' || $other_actor==1 || $other_model==1 || $other_musician==1)
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Representation Name</label>
                            <div class="col-lg-9">
                                <input type="text" class="form-control" id="model_name" name="model_name" @if(Auth::user()->representation=='1') disabled="disabled" @endif
                                       value="{{ Auth::user()
                                ->model_name }}" placeholder="Representation Name">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Representation Link</label>
                            <div class="col-lg-9">
                                <input type="url" class="form-control" id="model_link" name="model_link" @if(Auth::user()->representation=='1') disabled="disabled" @endif value="{{ Auth::user()->model_link }}" placeholder="Representation Link">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Looking for Representation</label>
                            <div class="col-lg-9">
                                <input type="checkbox" name="representation" @if(Auth::user()->representation=='1') checked="" @endif value="1" id="representation"
                                       class="form-control">
                            </div>
                        </div>
                      @endif

  					@if(Auth::user()->user_type=='1')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Play any character from a movie who would it be and why?</label>
  						<div class="col-lg-9">
  							<textarea name="actor_questions" class="form-control"
  							placeholder="Play any character from a movie who would it be and why?">{{ old('actor_questions' . Auth::user()->actor_questions, Auth::user()->actor_questions) }}</textarea>
  						</div>
  					</div>
  					@endif
  					@if(Auth::user()->user_type=='3')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Perform with anyone on stage who would it be and why?</label>
  						<div class="col-lg-9">
  							<textarea name="musician_questions" class="form-control"
  							placeholder="Perform with anyone on stage who would it be and why?">{{ old('musician_questions' . Auth::user()->musician_questions, Auth::user()->musician_questions) }}</textarea>
  						</div>
  					</div>
  					@endif
  					@if(Auth::user()->user_type=='2')
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label">Model for any company, which one and why?</label>
  						<div class="col-lg-9">
  							<textarea name="model_questions" class="form-control"
  							placeholder="Model for any company, which one and why?">{{ old('model_questions' . Auth::user()->model_questions, Auth::user()->model_questions) }}</textarea>
  						</div>
  					</div>
  					@endif
  					<hr/>
  					<div class="form-group row">
  						<label class="col-lg-3 col-form-label form-control-label"></label>
  						<div class="col-lg-9">
  							<input type="button" id="save_profile" class="btn btn-primary" value="Save Changes"/>
  						</div>
  					</div>
  				</form>
  			</div>
  		</div>
  	</div>
  </div>
 </div>
</div>
</div>
@include('include.footer')
@endsection

@section('after-scripts')
<script src="{{ asset('public/front/js/thirdparty/select2.full.min.js?v=4.0.0') }}"></script>
<script src="{{ asset('public/front/js/thirdparty/croppie.js?v=4.0.0') }}"></script>
<script>
	var config = {
		routes: {
			user_country: '{{ Auth::user()->country }}',
			get_user_country: "{{ route('get.user.country') }}",
			upload_profile_picture: "{{ route('upload-profile-picture') }}",
			profile_picture: "{{ asset('public/img/profile_picture/') }}",
			upload_cv: "{{ route('upload-cv') }}",
		}
	};
</script>
<script src="{{ asset('public/front/js/user-profile.js?v=6.0.0') }}"></script>
@endsection
