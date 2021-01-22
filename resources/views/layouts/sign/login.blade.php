<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="turbolinks-cache-control" content="no-cache">
    <link rel="icon" type="image/png" href="{{ asset('images/icon/keuanganku.png') }}" />
    <title>KeuanganKu | @yield('title')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/yeti.css') }}" rel="stylesheet"> -->

    <!-- bootstrap V3 -->
    <!-- <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}"> -->
    <!-- bootstrap V4 -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> 
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('css/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <!-- <link rel="stylesheet" href="{{ asset('css/adminLTE/AdminLTE.min.css') }}"> -->
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('css/iCheck/square/blue.css') }}">
    <!-- Google Font -->
    <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
    <link rel="stylesheet" href="{{ asset('fonts/fonts-google-apis/fonts.css') }}">
    <style type="text/css">
      .turbolinks-progress-bar {
        background-color: #6f42c1;
      }
    </style>
    @yield('extra_style')

    <script data-ad-client="ca-pub-5316550212400820" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <script src="{{ asset('js/jQuery/jquery.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/iCheck/icheck.min.js') }}"></script>
    <!-- Turbolinks -->
    <script src="{{ asset('js/turbolinks-5.2.0/dist/turbolinks.js') }}" type="text/javascript" charset="utf-8">
    </script>
    
</head>
<!-- <body class="hold-transition login-page"> -->
<body>
    <div id="app" class="d-flex flex-column">
        @yield('content')
    </div>

    <script type="text/javascript">
        var baseUrl = "{{ url('/') }}";

        if(Turbolinks.supported) {
            Turbolinks.start()
        } else {
            console.warn("browser kamu tidak mendukung `Turbolinks`")
        }

        $(function () {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' // optional
            });
        });
    </script>
    @yield('extra_script')
</body>
</html>
