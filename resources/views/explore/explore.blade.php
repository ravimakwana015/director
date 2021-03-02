@extends('layouts.app')
@section('title','Explore')
@if(isset(explorelist()[0]))
    <?php
    $explorelist = explorelist()[0];
    ?>
    @section('meta-title',strip_tags($explorelist['seo_title']))
@section('meta-keywords',strip_tags($explorelist['seo_keyword']))
@section('meta-description',strip_tags($explorelist['seo_description']))
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
    <div class="main career-page explore">
        <section class="filter-section">
            <div class="custom-container">
                <div class="filter-wrap">
                    <form method="get" id='frm' onkeydown="return event.key != 'Enter';">
                        <div class="form-item">
                           <input type="search" name="search" placeholder="Search Develop" value="{{ request()->get('search') }}" id="search">
                        </div>
                    </form>
                    <ul class="options-tab isotope-filters">
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('explore') }}?usertype=all')"
                               id="all_user" data-user_type="all" class="btn btn-small all active">Popular</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('explore') }}?usertype=Actor')"
                               id="actor_user" data-user_type="Actor" class="btn btn-small red">Acting</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('explore') }}?usertype=Models')"
                               id="model_user" data-user_type="Models" class="btn btn-small blue">Modelling</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" onclick="ajaxUserLoad('{{ route('explore') }}?usertype=Musicians')"
                               id="musician_user" data-user_type="Musicians" class="btn btn-small yellow">Musicians</a>
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
                                    <a href="{{ route('explore') }}" class="btn">Reset</a>
                                    <button type="button" class="btn" id="ad_search_btn">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="content">
                    @include('explore.ajax-develop')
                </div>
            </div>
        </section>
    </div>
    @include('include.footer')
     <script>
        const config = {
            routes: {
                develop: '{{ route('explore') }}',
                get_search_options: '{{ route('get.develop.search.options') }}',
            }
        };
    </script>
    <script src="{{ asset('public/front/js/develop.js?v=4.0.0') }}"></script>
@endsection
