@extends('layouts.app')
@section('title','Discover')
@if(isset(discoverpage()[0]))
    <?php
    $discoverpage = discoverpage()[0];
    ?>
    @section('meta-title',strip_tags($discoverpage['seo_title']))
@section('meta-keywords',strip_tags($discoverpage['seo_keyword']))
@section('meta-description',strip_tags($discoverpage['seo_description']))
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
    @if($discover->status==2)
        @include('discover.winner')
    @else
        @include('discover.discover_details')
    @endif
    <script>
        $(function () {
            $("#view_winner").on('click', function () {
                $('.winner-profile-wrap').slideToggle(1000);
            })
        });
    </script>
    @include('include.footer')
@endsection
