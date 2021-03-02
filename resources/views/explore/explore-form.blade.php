@extends('layouts.app')
@section('title','Explore Details')
@if(isset(explorepage()[0]))
    <?php
    $explorepage = explorepage()[0];
    ?>
    @section('meta-title',strip_tags($explorepage['seo_title']))
@section('meta-keywords',strip_tags($explorepage['seo_keyword']))
@section('meta-description',strip_tags($explorepage['seo_description']))
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

    <div class="main-content career-details develop-detail contact-page main">
        <div class="contact-us">
            <div class="left-content">
                <div class="contact-text-wrap">
                    <div class="contact-form-wrap">
                        <div class="contact-text-wrap">
                            <div class="contact-info">
                                <h2>{!! ucfirst($explore->title) !!}</h2>
                            </div>
                        </div>
                        <h2>We will get in touch with you shortly</h2>
                        @include('admin.include.message')
                        <form method="POST" action="{{ route('explore.application') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="explore_id" value="{{ $explore->id }}">
                            <div class="form-item">
                                <input type="text" name="name" placeholder="Enter Your Name" value="{{ old('name') }}">
                            </div>
                            <div class="form-item">
                                <input type="text" name="phone" placeholder="Enter Your Contact Number" value="{{ old('phone') }}">
                            </div>
                            <div class="form-item">
                                {{ Form::textarea('cover_letter', null, ['placeholder' => "Message",'rows'=>'2']) }}
                            </div>
                            <div class="form-action">
                                <button type="submit" class="btn">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="right-content">
                <div class="contact-info">
                    <h2>{!! ucfirst($explore->title) !!}</h2>
                    {!! $explore->description !!}
                </div>
                <div class="right-img-wrap">
                    <a href="{!! $explore->link !!}" target="_blank">
                        @if(isset($explore->workshop_image)  && $explore->workshop_image!='' && file_exists('public/img/explore/' . $explore->workshop_image))
                            <img src="{{ asset('public/img/explore/' . $explore->workshop_image) }}" alt="profile-pic" id="profile_img">
                        @else
                            <img src="{{ asset('public/front/images/no-image-available.png') }}" alt="profile-pic" id="profile_img">
                        @endif
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('include.footer')
@endsection
