<!doctype html>
@if(isset(generalpage()[0]))
<?php
$generalpage = generalpage()[0];
?>
@endif
<html class="no-js" lang="en">
<head>
    @php
    header("Cache-Control: no-cache, must-revalidate");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Content-Type: application/xml; charset=utf-8");
    @endphp
    <title>@yield('title','Home') | {{ config('app.name', 'Producers Eye') }}</title>
    <meta name="userId" content="{{ Auth::check() ? Auth::user()->id : 'null' }}">
    <meta charset="utf-8">
    <meta name="google-site-verification" content="GMNSm0I-KdgtrnNy3b37iJkuDl5naJidH30dburoBHg"/>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    @if(isset($generalpage))
    <meta name="title" content="@yield('meta-title',strip_tags($generalpage['seo_title']))"/>
    <meta name="keywords" content="@yield('meta-keywords',strip_tags($generalpage['seo_keyword']))"/>
    <meta name="description" content="@yield('meta-description',strip_tags($generalpage['seo_description']))"/>
    @endif
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
    <link rel="shortcut icon" href="{{ asset('/public/img/favicon/favicon.ico') }}" type="image/x-icon"/>
    <!-- Font-Awesome -->
    <link rel="stylesheet" href="{{ asset('public/front/css/fontawesome.all.css?v=11.1.0') }}">
    <link rel="stylesheet" href="{{ asset('public/front/css-thirdparty/bootstrap.min.css?v=11.1.0') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('public/front/css/main.css?v=17.1.0') }}">
    <link rel="stylesheet" href="{{ asset('public/front/css/custom.css?v=16.1.0') }}">


    @yield('after-styles')
    <script src="{{ asset('public/front/js/thirdparty/jquery-3.2.1.min.js') }}"></script>
    {{ Html::script('https://cloud.tinymce.com/stable/tinymce.min.js') }}
    <script src="{{ asset('public/front/js/app.min.js?v=11.1.0') }}"></script>

    @yield('after-scripts')

    {!! settings()[0]->web_analytics !!}
</head>
<body class="@if(\Request::route()->getName()=='home') home @endif @if(\Request::route()->getName()=='profile') profile-edit-page @endif  @if(Auth::user()) login @endif">
    <div id="loading" style="display: none">
        <img src="{{ URL::asset('public/front/images/loading-profile.gif') }}" style=" z-index: +1;" width="150" height="150" alt="loader"/>
    </div>
    <div id="get-profile-loader" style="display: none">
        <img src="{{ URL::asset('public/front/images/loading-profile.gif') }}" style=" z-index: +1;" width="150" height="150" alt="loader"/>
    </div>

    @if(\Request::route()->getName()=='users' || \Request::route()->getName()=='advancesearchlist' || \Request::route()->getName()=='advancesearchlists' )
    @endif
    <div id="app">
        @yield('content')
    </div>

    @if(\Request::route()->getName()=='chat.index' || \Request::route()->getName()=='user-chat')
    <script src="{{ asset('public/js/app.js') }}"></script>
    @else
    <script src="{{ asset('public/front/js/thirdparty/isotop.min.js?v=11.1.0') }}"></script>
    <script src="{{ asset('public/front/js/thirdparty/bootstrap.min.js?v=11.1.0') }}"></script>
    @endif
    <script src="{{ asset('public/front/js/custom-app.js?v=11.1.0') }}"></script>
    <script src="{{ asset('public/front/js/profile-gallery.js?v=11.1.0') }}"></script>
    <script>
        jQuery(document).ready(function ($) {
            @if(!Auth::user())
            @if(\Request::route()->getName()=='career')
            $("#mycareerloginModal").modal({backdrop: 'static', keyboard: false});
            @endif
            @if(\Request::route()->getName()=='explore')
            $("#mydeveloploginModal").modal({backdrop: 'static', keyboard: false});
            @endif
            @if(\Request::route()->getName()=='discover')
            $("#myenterloginModal").modal({backdrop: 'static', keyboard: false});
            @endif
            @if(\Request::route()->getName()=='forumlist')
            $("#myforumloginModal").modal({backdrop: 'static', keyboard: false});
            @endif
            @if(\Request::route()->getName()=='users')
            $("#myprofileloginModal").modal();
            @endif
            @endif
        });

        setTimeout(function () {
            var chat_pro = jQuery('.my-profile-intro .person-img img').attr('src');
            jQuery('.my-profile-intro').css('background-image', 'url(' + chat_pro + ')');
        }, 1000);
    </script>

</body>
</html>
