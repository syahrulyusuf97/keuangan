<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="turbolinks-cache-control" content="no-cache">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="{{$identitas->deskripsi}}">
    <meta name="author" content="{{$identitas->title}}">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
	<meta property="og:site_name" content="{{$identitas->title}}" /> <!-- website name -->
	<meta property="og:site" content="{{url('/')}}" /> <!-- website link -->
	<meta property="og:title" content="{{$identitas->title}}"/> <!-- title shown in the actual shared post -->
	<meta property="og:description" content="{{$identitas->deskripsi}}" /> <!-- description shown in the actual shared post -->
	<meta property="og:image" content="" /> <!-- image link, make sure it's jpg -->
	<meta property="og:url" content="{{url('/')}}" /> <!-- where do you want your post to link to -->
	<meta property="og:type" content="article" />

    <!-- Website Title -->
    <title>KeuanganKu | @yield('title')</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:400,400i,600,700,700i&amp;subset=latin-ext" rel="stylesheet">
    <link href="{{ asset('css/index/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index/swiper.css') }}" rel="stylesheet">
	<link href="{{ asset('css/index/magnific-popup.css') }}" rel="stylesheet">
	<link href="{{ asset('css/index/styles.css') }}" rel="stylesheet">
    <style type="text/css">
      .turbolinks-progress-bar {
        background-color: #6f42c1;
      }
    </style>
    @yield('extra_style')
	
	<!-- Favicon  -->
    <!-- <link rel="icon" href="{{ asset('images/index/favicon.png') }}"> -->
    <!-- <script data-ad-client="ca-pub-1006524802991381" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
    <script data-ad-client="ca-pub-5316550212400820" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
    <!-- Scripts -->
    <script src="{{ asset('js/index/jquery.min.js') }}"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="{{ asset('js/index/popper.min.js') }}"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="{{ asset('js/index/bootstrap.min.js') }}"></script> <!-- Bootstrap framework -->
    <script src="{{ asset('js/index/jquery.easing.min.js') }}"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="{{ asset('js/index/swiper.min.js') }}"></script> <!-- Swiper for image and text sliders -->
    <script src="{{ asset('js/index/jquery.magnific-popup.js') }}"></script> <!-- Magnific Popup for lightboxes -->
    <script src="{{ asset('js/index/validator.min.js') }}"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="{{ asset('js/index/scripts.js') }}"></script> <!-- Custom scripts -->

    <!-- Turbolinks -->
    <script src="{{ asset('js/turbolinks-5.2.0/dist/turbolinks.js') }}" type="text/javascript" charset="utf-8">
    </script>

    <script type="text/javascript"  charset="utf-8">
        var base_url = '{{url("/")}}';
        
        if(Turbolinks.supported) {
            Turbolinks.start()
        } else {
            console.warn("browser kamu tidak mendukung `Turbolinks`")
        }

        $("#refresh").click(function(){
          $.ajax({
             type:"GET",
             url:base_url+"/refreshcaptcha",
             success:function(data){
                $(".captcha span").html(data.captcha);
             }
          });
        });

    </script>
    @yield('extra_script')
</head>
<body data-spy="scroll" data-target=".fixed-top">
    
    <!-- Preloader -->
	<!-- <div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div> -->
    <!-- end of preloader -->
    

    @include('layouts.indexLayout.indexHeader')

    @yield('content')

    @include('layouts.indexLayout.indexFooter')
    
</body>
</html>