@extends('layouts.app')
<?php
if (count(generalpage()) > 0) {
    $generalpage = generalpage()[0];
}
?>
@if(count(generalpage())>0)
    @section('meta-title',strip_tags($generalpage['seo_title']))
@section('meta-keywords',strip_tags($generalpage['seo_keyword']))
@section('meta-description',strip_tags($generalpage['seo_description']))
@endif
@section('content')
    @include('include.header')
    @if(isset($page))
        <div class="main content-page aboutus-page">
            @if($page->form==1)
                <div class="content-page-wrap">
                    <div class="left-content">
                        {!! $page->content !!}
                    </div>
                    <div class="right-content">
                        @include('contact-form')
                    </div>
                </div>
            @else
                <div class="custom-container">
                    <div class="content-page-main-wrap">
                        {!! $page->content !!}
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="main content-page aboutus-page">
            <div class="custom-container">
                <div class="name-header">
                    <h2>Page Not Available</h2>
                </div>
            </div>
        </div>
    @endif
    @include('include.footer')
@endsection
