<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
    <!-- Text Logo - Use this if you don't have a graphic logo -->
    <!-- <a class="navbar-brand logo-text page-scroll" href="index.html">Evolo</a> -->

    <!-- Image Logo -->
    <a class="navbar-brand logo-image" href="index.html"><img src="{{ asset('public/images/index/logo.svg') }}" alt="alternative"></a>
    
    <!-- Mobile Menu Toggle Button -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-awesome fas fa-bars"></span>
        <span class="navbar-toggler-awesome fas fa-times"></span>
    </button>
    <!-- end of mobile menu toggle button -->

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link page-scroll" href="#header">Beranda <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="#services">Layanan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="#about">Tentang Kami</a>
            </li>

            <li class="nav-item">
                <a class="nav-link page-scroll" href="#contact">Pesan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link page-scroll" href="#donasi">Donasi</a>
            </li>
            @if(Session::has('adminName'))
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
	                <a class="nav-link page-scroll" href="{{url('/registrasi')}}">Registrasi</a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link page-scroll" href="{{url('/login')}}">Masuk</a>
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
                        <h1><span class="turquoise">KeuanganKu</span> <br/>Managemen Keuangan Pribadi</h1>
                        <p class="p-large"><strong>KeuanganKu</strong> adalah aplikasi untuk pencatatan keuangan pribadi Anda. Pantau terus transaksi Anda dimanapun & kapanpun, agar lebih bijak dalam mengelola keuangan pribadi.</p>
                        <a class="btn-solid-lg page-scroll" href="{{url('/registrasi')}}">REGISTRASI</a>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <div class="image-container">
                        <img class="img-fluid" src="{{ asset('public/images/index/header-teamwork.svg') }}" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of header-content -->
</header> <!-- end of header -->
<!-- end of header