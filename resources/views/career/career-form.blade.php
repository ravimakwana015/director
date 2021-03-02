@extends('layouts.app')
@section('title','Career Details')
@if(isset(careerpage()[0]))
    <?php
    $careerpage = careerpage()[0];
    ?>
    @section('meta-title',strip_tags($careerpage['seo_title']))
@section('meta-keywords',strip_tags($careerpage['seo_keyword']))
@section('meta-description',strip_tags($careerpage['seo_description']))
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

    <div class="main-content career-details contact-page main">
        <div class="contact-us">
            <div class="left-content">
                <div class="contact-text-wrap">
                    <div class="contact-form-wrap">
                        <div class="contact-text-wrap">
                            <div class="contact-info">
                                <h2>{!! ucfirst($career->title) !!}</h2>
                            </div>
                        </div>
                        <h2>Apply using the form below</h2>
                        @include('admin.include.message')
                        <form method="POST" action="{{ route('send.application') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="career_id" value="{{ $career->id }}">
                            <div class="form-item">
                                {{ Form::textarea('cover_letter', null, ['placeholder' => "Additional Comments",'rows'=>'2']) }}
                            </div>
                            <div class="form-group">
                                {{ Form::label('cv', 'Add your CV', ['required']) }}
                                <label class="custom-file">
                                    <input type="file" name="cv" id="cv-1" class="custom-file-input">
                                    <span class="custom-file-control">Choose file</span>
                                </label>
                                <label id="file-name"></label>
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
                    <h2>{!! ucfirst($career->title) !!}</h2>
                    {!! $career->description !!}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelector("#cv-1").onchange = function () {
            document.querySelector("#file-name").textContent = this.files[0].name;
        }
    </script>
    @include('include.footer')
@endsection
