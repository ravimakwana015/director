@extends('layouts.app')
@section('title','Profiles')
@section('content')
    @include('include.header')
<link rel="stylesheet" href="{{ asset('public/front/css/select2.min.css?v=4.0.0') }}">
    <div class="main career-page profile-listing-page">
        <section class="filter-section">
            <div class="custom-container">
                <div class="filter-wrap">
                    <form onsubmit="return false">
                        <div class="form-item">
                            <input type="search" name="search" placeholder="Search Profile" value="{{ request()->get('search') }}" id="search">
                        </div>
                    </form>
                    <ul class="options-tab isotope-filters">
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{url('profiles')}}?usertype=all')" class="btn btn-small all active" id="all_user"
                               data-user_type="all">
							<span class="loader-1">
								<span class="loading-bar"></span>
							</span>
                                All
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{url('profiles')}}?usertype=actor')" class="btn btn-small red" id="actor_user"
                               data-user_type="actor">
							<span class="loader-1">
								<span class="loading-bar"></span>
							</span>
                                Actors
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{url('profiles')}}?usertype=model')" class="btn btn-small blue" id="model_user"
                               data-user_type="model">
							<span class="loader-1">
								<span class="loading-bar"></span>
							</span>
                                Models
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{url('profiles')}}?usertype=musician')" class="btn btn-small yellow" id="musician_user"
                               data-user_type="musician">
							<span class="loader-1">
								<span class="loading-bar"></span>
							</span>
                                Musicians
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{url('profiles')}}?usertype=crew')" class="btn btn-small crew" id="crew_user"
                               data-user_type="crew">
							<span class="loader-1">
								<span class="loading-bar"></span>
							</span>
                                Creators
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="advance-search">
                    <a href="javascript:;" id="adv-click" class="btn">
					<span class="loader-1">
						<span class="loading-bar"></span>
					</span>
                        Advanced Search</a>
                </div>
                <div class="search-filter-main-wrap" style="display: none;">
                    <div class="filter-wrap">
                        <h2>Advanced Search</h2>
                        <form action="{{ route('advancesearchlist') }}" method="post" id="advance-search">
                            {{-- @csrf --}}
                            <div class="search-fields">
                                <div class="form-item odd">
                                    <select name="country" class="ad_search_class" id="country_search">
                                        <option value="">Country</option>
                                    </select>
                                </div>
                                <div class="form-item even" id="gender">
                                    <select name="gender" class="ad_search_class" id="gender_search">
                                        <option value="">Gender</option>
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        {{-- <option value="no_to_ans">Prefer not to answer</option> --}}
                                    </select>
                                </div>
                                <div class="form-item odd" id="genre">
                                    <select name="genre" class="ad_search_class" id="genre_search">
                                        <option value="">Genre</option>
                                        <option value="Action">Action</option>
                                        <option value="Animation">Animation</option>
                                        <option value="Comedy">Comedy</option>
                                        <option value="Crime">Crime</option>
                                        <option value="Drama">Drama</option>
                                        <option value="Experimental">Experimental</option>
                                        <option value="Fantasy">Fantasy</option>
                                        <option value="Historical">Historical</option>
                                        <option value="Horror">Horror</option>
                                        <option value="Romance">Romance</option>
                                        <option value="Sci-fi">Sci-fi</option>
                                        <option value="Thriller">Thriller</option>
                                        <option value="Western">Western</option>
                                        <option value="Musical Theatre">Musical Theatre</option>
                                        <option value="Jazz">Jazz</option>
                                        <option value="Rock">Rock</option>
                                        <option value="Hip-Hop">Hip-Hop</option>
                                        <option value="Classical">Classical</option>
                                        <option value="Pop">Pop</option>
                                        <option value="Soul">Soul</option>
                                        <option value="Instrumental">Instrumental</option>
                                    </select>
                                </div>
                                <div class="form-item even" id="acent">
                                    <select name="acent" class="ad_search_class" id="acents_search">
                                        <option value="">Accents</option>
                                        <option value="Irish">Irish</option>
                                        <option value="Welsh">Welsh</option>
                                        <option value="Scottish">Scottish</option>
                                        <option value="American">American</option>
                                        <option value="British">British</option>
                                        <option value="Indian">Indian</option>
                                        <option value="Australian">Australian</option>
                                        <option value="South African">South African</option>
                                        <option value="South American">South American</option>
                                        <option value="Italian">Italian</option>
                                        <option value="French">French</option>
                                        <option value="Spanish">Spanish</option>
                                        <option value="Kiwi">Kiwi</option>
                                        <option value="Canadian">Canadian</option>
                                        <option value="Russian">Russian</option>
                                    </select>
                                </div>
                                <div class="form-item odd" id="age">
                                    <select name="age" class="ad_search_class" id=""><!-- Remove Id age_search-->
                                        <option value="">Playing Age</option>
                                        <option value="16-21">16-21</option>
                                        <option value="21-25">21-25</option>
                                        <option value="25-30">25-30</option>
                                        <option value="30-35">30-35</option>
                                        <option value="35-40">35-40</option>
                                        <option value="40-45">40-45</option>
                                        <option value="45-50">45-50</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-item even" id="height">
                                    <select name="height" class="ad_search_class" id="height_search">
                                        <option value="">Height</option>
                                        <option value="4’9”">4’9”</option>
                                        <option value="4’10”">4’10”</option>
                                        <option value="4’11”">4’11”</option>
                                        <option value="5’0”">5’0”</option>
                                        <option value="5’1”">5’1”</option>
                                        <option value="5’2”">5’2”</option>
                                        <option value="5’3”">5’3”</option>
                                        <option value="5’4”">5’4”</option>
                                        <option value="5’5”">5’5”</option>
                                        <option value="5’6”">5’6”</option>
                                        <option value="5’7”">5’7”</option>
                                        <option value="5’8”">5’8”</option>
                                        <option value="5’9”">5’9”</option>
                                        <option value="5’10”">5’10”</option>
                                        <option value="5’11”">5’11”</option>
                                        <option value="6’0”">6’0”</option>
                                        <option value="6’1”">6’1”</option>
                                        <option value="6’2”">6’2”</option>
                                        <option value="6’3”">6’3”</option>
                                        <option value="6’4”">6’4”</option>
                                        <option value="6’5”">6’5”</option>
                                        <option value="6’6”">6’6”</option>
                                    </select>
                                </div>
                                <div class="form-item odd" id="eye_colour">
                                    <select name="eye_colour" class="ad_search_class" id="eye_colour_search">
                                        <option value="">Eye Colour</option>
                                        <option value="amber">Amber</option>
                                        <option value="blue">Blue</option>
                                        <option value="brown">Brown</option>
                                        <option value="gray">Gray</option>
                                        <option value="green">Green</option>
                                        <option value="hazel">Hazel</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-item even" id="hair_colour">
                                    <select name="hair_colour" class="ad_search_class" id="hair_colours_search">
                                        <option value="">Hair Colour</option>
                                        <option value="Jet Black">Jet Black</option>
                                        <option value="Brunette">Brunette</option>
                                        <option value="Blonde">Blonde</option>
                                        <option value="Grey">Grey</option>
                                        <option value="Ginger">Ginger</option>
                                        <option value="White">White</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-item odd" id="instrument">
                                    <select name="instrument" class="ad_search_class" id="instrument_search">
                                        <option value="">Instrument</option>
                                        <option value="Electric Guitar">Electric Guitar</option>
                                        <option value="Keyboard">Keyboard</option>
                                        <option value="Piano">Piano</option>
                                        <option value="Guitar">Guitar</option>
                                        <option value="Drums">Drums</option>
                                        <option value="Violin">Violin</option>
                                        <option value="Saxophone">Saxophone</option>
                                        <option value="Flute">Flute</option>
                                        <option value="Cello">Cello</option>
                                        <option value="Clarinet">Clarinet</option>
                                        <option value="Sitar">Sitar</option>
                                    </select>
                                </div>
                                <div class="form-item even" id="crew_type">
                                    <select class="ad_search_class" name="crew_type">
                                        <option value="" selected="selected">Sector</option>
                                        <option value="Artist">Artist</option>
                                        <option value="Photographer">Photographer</option>
                                        <option value="Choreographer">Choreographer</option>
                                        <option value="Cinematographer">Cinematographer</option>
                                        <option value="Director">Director</option>
                                        <option value="Writer">Writer</option>
                                        <option value="Editor">Editor</option>
                                        <option value="Composer">Composer</option>
                                        <option value="Make Up Artist">Make Up Artist</option>
                                        <option value="Sound, Light, Effects, Design">Sound, Light, Effects, Design</option>
                                    </select>
                                </div>
                            </div>
                            <div class="action-wrap">
                                <div class="total_result">
                                    {{-- <strong id="result_count">{{ $count }}</strong> Results --}}
                                </div>
                                <div class="btn-wrap">
                                    <a href="{{ route('users') }}" class="btn">Reset</a>
                                    <button type="button" class="btn" id="ad_search_btn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div id="content">
                    @include('profile.ajaxprofiles')
                </div>
            </div>
        </section>
    </div>
    <script>
        var config = {
            routes: {
                get_user_country: "{{ route('get.user.country') }}",
                profiles: '{{url('profiles')}}',
                get_search_options: '{{ route('getSearchOptions') }}',
            }
        };
    </script>
    <script src="{{ asset('public/front/js/thirdparty/select2.full.min.js?v=4.0.0') }}"></script>
    <script src="{{ asset('public/front/js/profile.js?v=4.0.0') }}"></script>
    <script>

        @if(session('usertypeTransferType') == 'Actors')
        setTimeout(function () {
            $('a[id="actor_user"]').trigger('click');
        }, 1000);
        @endif
        @if(session('usertypeTransferType') == 'Actors')
        setTimeout(function () {
            $('a[id="actor_user"]').trigger('click');
        }, 1000);
        $('#instrument').hide();
        $('#crew_type').hide();
        $('#acent').show();
        $('#age').show();
        $('#height').show();
        $('#eye_colour').show();
        $('#hair_colour').show();
        $('#genre').show();
        $('#gender').show();
        @endif
        @if(session('usertypeTransferType') == 'Models')
        $('a[id="model_user"]').trigger('click');
        $('#model_user').addClass('active');
        $('#all_user').removeClass('active');
        // $('#instrument').hide();
        // $('#acent').hide();
        // $('#age').show();
        // $('#height').show();
        // $('#eye_colour').show();
        // $('#hair_colour').show();
        // $('#crew_type').hide();
        // $('#genre').show();
        // $('#gender').show();
        $('#instrument').hide();
        $('#crew_type').hide();
        $('#acent').hide();
        $('#age').show();
        $('#height').show();
        $('#eye_colour').show();
        $('#hair_colour').show();
        $('#genre').show();
        $('#gender').show();

        $('#acent').removeClass('even');
        $('#acent').addClass('odd');
        $('#age').removeClass('odd');
        $('#age').addClass('even');
        $('#height').removeClass('even');
        $('#height').addClass('odd');
        $('#eye_colour').removeClass('odd');
        $('#eye_colour').addClass('even');
        $('#hair_colour').removeClass('even');
        $('#hair_colour').addClass('odd');
        @endif
        @if(session('usertypeTransferType') == 'Musicians')
        $('a[id="musician_user"]').trigger('click');
        $('#musician_user').addClass('active');
        $('#all_user').removeClass('active');
        $('#instrument').show();
        $('#acent').hide();
        $('#age').hide();
        $('#height').hide();
        $('#eye_colour').hide();
        $('#hair_colour').hide();
        $('#crew_type').hide();
        $('#genre').show();
        $('#gender').show();
        @endif
        @if(session('usertypeTransferType') == 'Crew')
        $('a[id="crew_user"]').trigger('click');
        $('#crew_user').addClass('active');
        $('#all_user').removeClass('active');
        $('#crew_type').show();
        $('#acent').hide();
        $('#age').hide();
        $('#height').hide();
        $('#eye_colour').hide();
        $('#hair_colour').hide();
        $('#instrument').hide();
        $('#genre').hide();
        $('#gender').hide();
        @endif
    </script>
    @include('include.footer')
@endsection

