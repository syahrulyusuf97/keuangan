<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <!-- Text Logo - Use this if you don't have a graphic logo -->
    <a class="navbar-brand logo-text page-scroll" href="{{url('/')}}">KeuanganKu</a>

    <!-- Image Logo -->
    <!-- <a class="navbar-brand logo-image" href="index.html"><img src="{{ asset('images/index/logo.svg') }}" alt="alternative"></a> -->
    
    <!-- Mobile Menu Toggle Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-awesome fas fa-bars"></span>
        <span class="navbar-toggler-awesome fas fa-times"></span>
    </button>
    <!-- end of mobile menu toggle button -->

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/')}}/#header" data-turbolinks="true">Beranda <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/article/')}}" data-turbolinks="true">Artikel</a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/')}}/#services" data-turbolinks="true">Layanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/')}}/#syarat" data-turbolinks="true">Syarat & Ketentuan</a>
            </li>

            <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/')}}/#contact" data-turbolinks="true">Pesan</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link page-scroll" href="{{url('/')}}/#donasi">Donasi</a>
            </li> -->
            @if(Auth::check())
	            @if(auth()->user()->level == 1)
			    <li class="nav-item">
	                <a class="nav-link page-scroll" href="{{url('/admin/dashboard')}}">{{auth()->user()->name}}</a>
	            </li>
	            @elseif(auth()->user()->level == 2)
	            <li class="nav-item">
	                <a class="nav-link page-scroll" href="{{url('/dashboard')}}">{{auth()->user()->name}}</a>
	            </li>
	            @endif
			@else
	            <li class="nav-item">
	                <a class="nav-link" href="{{url('/registrasi')}}" data-turbolinks="true">Registrasi</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link" href="{{url('/login')}}" data-turbolinks="true">Masuk</a>
	            </li>
            @endif
        </ul>
    </div>
</nav> <!-- end of navbar -->
<!-- end of navigation -->


<!-- Header -->
<header id="header" class="header">
    <div class="header-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="text-container">
                        <!-- <h1><span class="turquoise">KeuanganKu</span> <br/>Managemen Keuangan Pribadi</h1> -->
                        <h1>{{$identitas->title}}</h1>
                        <p class="p-large">{{$identitas->deskripsi}}</p>
                        <a class="btn-solid-lg page-scroll" href="{{url('/registrasi')}}">REGISTRASI</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <div class="image-container">
                        <img class="img-fluid" src="{{ asset('images/index/header-teamwork.svg') }}" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of header-content -->
</header> <!-- end of header -->
<!-- end of header