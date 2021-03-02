@extends('layouts.app')
@section('title','Contact Us')
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

    <div class="main-content contact-page main">
        <div class="contact-us">
            <div class="left-content">
                <span>If you need any help at all please use the form to contact us. We have plenty of guides on our
                forum on explaining how to use the platform in depth as well as a feature list you are able to
                find in your dashboard. Alternatively you can write to us at the address shown below. Thank
                    you.</span>
            </div>
            <div class="right-content">
                @include('contact-form')
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
