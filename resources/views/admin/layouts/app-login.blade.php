<!doctype html>
<html class="no-js" lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{ asset('/public/img/favicon/favicon.ico') }}" type="image/x-icon" />
    <title>@yield('title', 'Producers Eye')</title>

    @yield('before-styles')

    {{ Html::style('public/admin/css/bootstrap.min.css') }}
    {{ Html::style('public/admin/css/font-awesome.min.css') }}
    {{ Html::style('public/admin/css/ionicons.min.css') }}
    {{ Html::style('public/admin/css/AdminLTE.min.css') }}
    {{ Html::style('public/admin/css/blue.css') }}
    {{ Html::style('public/admin/css/login.css?v=1.0.0') }}
    @yield('after-styles')
    
    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([ 'csrfToken' => csrf_token() ]) !!};
    </script>
</head>
<body class="hold-transition skin-red sidebar-mini login-page">
    <div id="app">
        <!-- Main content -->
        @yield('content')
    </div>

    <!-- JavaScripts -->
    @yield('before-scripts')
    {{ Html::script('public/admin/js/jquery.min.js') }}
    {{ Html::script('public/admin/js/bootstrap.min.js') }}
    {{ Html::script('public/admin/js/icheck.min.js') }}
    @yield('after-scripts')
    <script>
      $(function () {
        $('input').iCheck({
          checkboxClass: 'icheckbox_square-blue',
          radioClass: 'iradio_square-blue',
          increaseArea: '20%' /* optional */
      });
    });
</script>
</body>
</html>