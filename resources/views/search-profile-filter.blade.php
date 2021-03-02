@extends('layouts.app')

@section('content')
@include('include.header')

<div class="main career-page profile-listing-page">
  <section class="filter-section">
    <div class="custom-container">
      <div class="filter-wrap">
        <ul class="options-tab isotope-filters">
          <li>
            <a href="javascript:void(0);" data-option-value="*" class="btn btn-small all active" id="all_user">All</a>
          </li>
          <li>
            <a href="javascript:void(0);" data-option-value=".actors" class="btn btn-small red" id="actor_user">Actors</a>
          </li>
          <li>
            <a href="javascript:void(0);" data-option-value=".models" class="btn btn-small blue" id="model_user">Models</a>
          </li>
          <li>
            <a href="javascript:void(0);" data-option-value=".musicians" class="btn btn-small crew" id="musician_user">Musicians</a>
          </li>
          <li>
            <a href="javascript:void(0);" data-option-value=".crew" class="btn btn-small yellow" id="crew_user">Crew</a>
          </li>
        </ul>
      </div>

      <div class="search-filter-main-wrap">
        <div class="filter-wrap">
          <h2>Advanced Search</h2>
          <form action="{{ route('advancesearchlist') }}" method="post" id="advance-search">
            @csrf
            <div class="search-fields">
              <div class="form-item odd">
                <select name="country" class="ad_search_class">
                  <option value="">Country</option>
                  @if(isset($userdata))
                  @foreach($userdata as $countryname)
                  @if($countryname!='')
                  <option value="{!! $countryname !!}" @if(isset($input['country']) && $input['country'] == $countryname) selected @endif>{!! ucfirst($countryname)  !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item even" id="gender">
                <select name="gender" class="ad_search_class">
                  <option value="">Gender</option>
                  @if(isset($gender))
                  @foreach($gender as $genderResult)
                  @if($genderResult!='')
                  <option value="{!! $genderResult !!}" @if(isset($input['gender']) && $input['gender'] == $genderResult) selected @endif>{!! ucfirst($genderResult) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item odd" id="genre">
                <select name="genre" class="ad_search_class">
                  <option value="">Genre</option>
                  @if(isset($genre))
                  @foreach($genre as $genreResult)
                  @if($genreResult!='')
                  <option value="{!! $genreResult !!}" @if(isset($input['genre']) && $input['genre'] == $genreResult) selected @endif>{!! ucfirst($genreResult) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item even" id="acent" >
                <select name="acent" class="ad_search_class">
                  <option value="">Accents</option>
                  @if(isset($acents))
                  @foreach($acents as $acent)
                  @if($acent!='')
                  <option value="{!! $acent !!}" @if(isset($input['acent']) && $input['acent'] == $acent) selected @endif>{!! ucfirst($acent) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item odd" id="age" >
                <select name="age" class="ad_search_class">
                  <option value="">Playing Age</option>
                  @if(isset($playing_age))
                  @foreach($playing_age as $age)
                  @if($age!='')
                  <option value="{!! $age !!}" @if(isset($input['age']) && $input['age'] == $age) selected @endif>{!! ucfirst($age) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item even" id="height">
                <select name="height" class="ad_search_class">
                  <option value="">Height</option>
                  @if(isset($heights))
                  @foreach($heights as $height)
                  @if($height!='')
                  <option value="{!! $height !!}" @if(isset($input['height']) && $input['height'] == $height) selected @endif>{!! ucfirst($height) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item odd" id="eye_colour">
                <select name="eye_colour" class="ad_search_class">
                  <option value="">Eye Colour</option>
                  @if(isset($eye_colours))
                  @foreach($eye_colours as $eye_colour)
                  @if($eye_colour!='')
                  <option value="{!! $eye_colour !!}" @if(isset($input['eye_colour']) && $input['eye_colour'] == $eye_colour) selected @endif>{!! ucfirst($eye_colour) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item even" id="hair_colour">
                <select name="hair_colour" class="ad_search_class">
                  <option value="">Hair Colour</option>
                  @if(isset($hair_colours))
                  @foreach($hair_colours as $hair_colour)
                  @if($hair_colour!='')
                  <option value="{!! $hair_colour !!}" @if(isset($input['hair_colour']) && $input['hair_colour'] == $hair_colour) selected @endif>{!! ucfirst($hair_colour) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item odd" id="instrument">
                <select name="instrument" class="ad_search_class">
                  <option value="">Instrument</option>
                  @if(isset($instruments))
                  @foreach($instruments as $instrument)
                  @if($instrument!='')
                  <option value="{!! $instrument !!}" @if(isset($input['instrument']) && $input['instrument'] == $instrument) selected @endif>{!! ucfirst($instrument) !!}</option>
                  @endif
                  @endforeach
                  @endif
                </select>
              </div>
              <div class="form-item even" id="crew_type">
                <select class="ad_search_class" name="crew_type">
                  <option value="">Select Crew Type*</option>
                  <option value="Photographer"  @if(isset($input['crew_type']) && $input['crew_type'] == "Photographer") selected @endif>Photographer</option>
                  <option value="Cinematographer"  @if(isset($input['crew_type']) && $input['crew_type'] == "Cinematographer") selected @endif>Cinematographer</option>
                  <option value="Director"  @if(isset($input['crew_type']) && $input['crew_type'] == "Director") selected @endif>Director</option>
                  <option value="Writer"  @if(isset($input['crew_type']) && $input['crew_type'] == "Writer") selected @endif>Writer</option>
                  <option value="Editor"  @if(isset($input['crew_type']) && $input['crew_type'] == "Editor") selected @endif>Editor</option>
                  <option value="Composer"  @if(isset($input['crew_type']) && $input['crew_type'] == "Composer") selected @endif>Composer</option>
                  <option value="Make Up Artist"  @if(isset($input['crew_type']) && $input['crew_type'] == "Make Up Artist") selected @endif>Make Up Artist</option>
                  <option value="Other"  @if(isset($input['crew_type']) && $input['crew_type'] == "Other") selected @endif>Other (Sound, Light, Effects, Design)</option>
                </select>
              </div>
            </div>
            <div class="action-wrap">
              <div class="total_result"><strong>{{ $count }}</strong> Results</div>
              <div class="btn-wrap">
                <a href="{{ route('advancesearchlists') }}" class="btn">Reset</a>
                <button type="submit" class="btn">Search</button>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="career-listing profile-listing">
        <div class="isotope items">
          @foreach($searchResult as $user)
          @if($user->user_type=='1')
          <div class="item actors">
            @include('profile.profile_list_part')
          </div>
          @elseif($user->user_type=='2')
          <div class="item models">
            @include('profile.profile_list_part')
          </div>
          @elseif($user->user_type=='3')
          <div class="item musicians">
            @include('profile.profile_list_part')
          </div>
          @elseif($user->user_type=='4')
          <div class="item crew">
            @include('profile.profile_list_part')
          </div>
          @endif
          @endforeach
        </div>
      </div>
      <div class="forum-pagination">
       {{ $searchResult->links() }}
     </div>
   </div>
 </section>
</div>
<script>

  $(document).ready(function(){

    $('.ad_search_class').change(function(event) {
      $('#advance-search').submit();
    });

    $('#actor_user').click(function(event) {
      $('#instrument').hide();
      $('#crew_type').hide();
      $('#acent').show();
      $('#age').show();
      $('#height').show();
      $('#eye_colour').show();
      $('#hair_colour').show();
      $('#genre').show();
      $('#gender').show();

      $('#acent').removeClass('odd');
      $('#acent').addClass('even');
      $('#age').removeClass('even');
      $('#age').addClass('odd');
      $('#height').removeClass('odd');
      $('#height').addClass('even');
      $('#eye_colour').removeClass('even');
      $('#eye_colour').addClass('odd');
      $('#hair_colour').removeClass('odd');
      $('#hair_colour').addClass('even');
    });
    $('#model_user').click(function(event) {
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
    });
    $('#musician_user').click(function(event) {
      $('#instrument').show();
      $('#crew_type').hide();
      $('#acent').hide();
      $('#age').hide();
      $('#height').hide();
      $('#eye_colour').hide();
      $('#hair_colour').hide();
      $('#genre').show();
      $('#gender').show();
    });
    $('#crew_user').click(function(event) {
      $('#crew_type').show();
      $('#instrument').hide();
      $('#acent').hide();
      $('#age').hide();
      $('#height').hide();
      $('#eye_colour').hide();
      $('#hair_colour').hide();
      $('#genre').hide();
      $('#gender').hide();
    });
    $('#all_user').click(function(event) {
      $('#instrument').show();
      $('#acent').show();
      $('#age').show();
      $('#height').show();
      $('#eye_colour').show();
      $('#hair_colour').show();
      $('#crew_type').show();
      $('#genre').show();
      $('#gender').show();

      $('#acent').removeClass('odd');
      $('#acent').addClass('even');
      $('#age').removeClass('even');
      $('#age').addClass('odd');
      $('#height').removeClass('odd');
      $('#height').addClass('even');
      $('#eye_colour').removeClass('even');
      $('#eye_colour').addClass('odd');
      $('#hair_colour').removeClass('odd');
      $('#hair_colour').addClass('even');
    });
  });
</script>
@include('include.footer')
@endsection
