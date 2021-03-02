@extends('layouts.app')
@section('title','Discover')
@if(isset(discoverlist()[0]))
    <?php
    $discoverlist = discoverlist()[0];
    ?>
    @section('meta-title',strip_tags($discoverlist['seo_title']))
@section('meta-keywords',strip_tags($discoverlist['seo_keyword']))
@section('meta-description',strip_tags($discoverlist['seo_description']))
@else
    @if(isset(generalpage()[0]))
        <?php
        $generalpage = generalpage()[0];
        ?>
        @section('meta-title',strip_tags($generalpage['seo_title']))
@section('meta-keywords',strip_tags($generalpage['seo_keyword']))
@section('meta-description',strip_tags($generalpage['seo_description']))
@endif
@endif

@section('content')
    @include('include.header')
    <div class="main career-page discover">
        <section class="filter-section">
            <div class="custom-container">
                <div class="filter-wrap">
                    @include('admin.include.message')
                    <form method="get" id='frm' onkeydown="return event.key != 'Enter';">
                        <div class="form-item">
                            <input type="search" name="search" placeholder="Search Enter" value="{{ request()->get('search') }}" id="search">
                        </div>
                    </form>
                    <ul class="options-tab isotope-filters">
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('discover') }}?usertype=all')" id="all_user" data-user_type="all" class="btn btn-small
                            all active">Latest</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('discover') }}?usertype=Writing competitions')" id="Writing_competitions_user"
                               data-user_type="Writing competitions" class="btn  btn-small red">Writing</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('discover') }}?usertype=Filming competitions')" id="Filming_competitions_user"
                               data-user_type="Filming competitions" class="btn btn-small  blue">Film</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('discover') }}?usertype=Singing competitions')" id="Singing_competitions_user"
                               data-user_type="Singing competitions" class="btn btn-small yellow">Music</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('discover') }}?usertype=Best photo competitions')" id="Best_photo_competitions_user"
                               data-user_type="Best photo competitions" class="btn  btn-small bg-grey">Photos</a>
                        </li>
                    </ul>
                </div>
{{--                <div class="advance-search">--}}
{{--                    <a href="javascript:;" id="adv-click" class="btn">--}}
{{--					<span class="loader-1">--}}
{{--						<span class="loading-bar"></span>--}}
{{--					</span>--}}
{{--                        Advanced Search</a>--}}
{{--                </div>--}}
                <div class="search-filter-main-wrap" style="display: none;">
                    <div class="filter-wrap">
                        <h2>Advanced Search</h2>
                        <form action="#" method="post" id="advance-search">
                            {{-- @csrf --}}
                            <div class="search-fields">
                                <div class="form-item odd">
                                    <select name="country" class="ad_search_class" id="country_search">
                                        <option value="">Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="action-wrap">
                                <div class="total_result">
                                    {{-- <strong id="result_count">{{ $count }}</strong> Results --}}
                                </div>
                                <div class="btn-wrap">
                                    <a href="{{ route('discover') }}" class="btn">Reset</a>
                                    <button type="button" class="btn" id="ad_search_btn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="content">
                    @include('discover.ajax-discover')
                </div>
            </div>
        </section>
    </div>
    @include('include.footer')
    <script>
        const config = {
            routes: {
                discover: '{{ route('discover') }}',
                get_search_options: '{{ route('get.discover.search.options') }}',
            }
        };
    </script>
    <script src="{{ asset('public/front/js/discover.js?v=4.0.0') }}"></script>
@endsection

@section('after-scripts')
    <script>
        jQuery(document).ready(function ($) {
            @if(!Auth::user())
            $("#myloginModal").modal({backdrop: 'static', keyboard: false});
            @endif
        });
    </script>
@endsection
