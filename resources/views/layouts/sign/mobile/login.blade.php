<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>KeuanganKu</title>
    <link rel="stylesheet" href="{{ asset('css/mobile/style.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="turbolinks-cache-control" content="no-cache">
    <meta name="description" content="KeuanganKu">
    <meta name="keywords" content="KeuanganKu" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/icon/keuanganku.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/icon/keuanganku.png') }}" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('images/icon/keuanganku.png') }}">
    <style type="text/css">
      .turbolinks-progress-bar {
        background-color: red;
      }
    </style>
    @yield('extra_style')

    <!-- Jquery -->
    <script src="{{ asset('js/mobile/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('js/mobile/lib/popper.min.js') }}"></script>
    <script src="{{ asset('js/mobile/lib/bootstrap.min.js') }}"></script>
    <!-- Ionicons -->
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('js/mobile/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <!-- Turbolinks -->
    <script src="{{ asset('js/turbolinks-5.2.0/dist/turbolinks.js') }}" type="text/javascript" charset="utf-8">
    </script>
</head>

<body class="bg-light">
    <div class="loading">Loading&#8230;</div>
    <!-- loader -->
    <div id="loader">
        <!-- <img src="assets/img/logo-icon.png" alt="icon" class="loading-icon"> -->
        <img src="{{ asset('images/icon/keuanganku.png') }}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    @yield('content')

    <!-- Error Message -->
    <div class="modal fade dialogbox" id="error_message" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-danger">
                    <ion-icon name="close-circle-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">....</h5>
                </div>
                <div class="modal-body">
                    ....
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-dismiss="modal">TUTUP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Error Message -->

    <!-- Info Message -->
    <div class="modal fade dialogbox" id="info_message" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon">
                    <!-- <ion-icon name="alert-circle-outline"></ion-icon> -->
                    <ion-icon name="warning-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">....</h5>
                </div>
                <div class="modal-body">
                    ....
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-dismiss="modal">TUTUP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Info Message -->

    <!-- Success Message -->
    <div class="modal fade dialogbox" id="success_message" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-success">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">....</h5>
                </div>
                <div class="modal-body">
                    ....
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn" data-dismiss="modal">TUTUP</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Success Message -->

    <!-- ///////////// Js Files ////////////////////  -->
    
    <!-- Base Js File -->
    <script src="{{ asset('js/mobile/base.js') }}"></script>

    <script type="text/javascript">
        var baseUrl = "{{url('/')}}";
    </script>

    @yield('extra_script')

</body>
</html>