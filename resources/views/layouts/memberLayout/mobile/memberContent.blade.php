<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KeuanganKu</title>
    @yield('stylesheet')
    <link rel="stylesheet" href="{{ asset('public/css/bootstrap/bootstrap-datepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/mobile/style.css') }}">
    <link rel="stylesheet" href="{{ asset('public/css/font-awesome/css/font-awesome.min.css') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="KeuanganKu">
    <meta name="keywords" content="KeuanganKu" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('public/images/icon/keuanganku.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('public/images/icon/keuanganku.png') }}" sizes="32x32">
    <link rel="shortcut icon" href="{{ asset('public/images/icon/keuanganku.png') }}">
</head>

<body>
    <div class="loading">Loading&#8230;</div>
    <!-- loader -->
    <div id="loader">
        <!-- <img src="assets/img/logo-icon.png" alt="icon" class="loading-icon"> -->
        <img src="{{ asset('public/images/icon/keuanganku.png') }}" alt="icon" class="loading-icon">
    </div>
    <!-- * loader -->

    <!-- Header Meber -->

    @yield('content')

    <!-- App Bottom Menu -->
    <div class="appBottomMenu">
        <a href="{{url('/dashboard')}}" class="item page-redirect">
            <div class="col">
                <ion-icon name="home-outline"></ion-icon>
                <strong>Dashboard</strong>
            </div>
        </a>
        <a href="{{ url('/laporan/cashflow') }}" class="item">
            <div class="col">
                <ion-icon name="book-outline"></ion-icon>
                <strong>Cashflow</strong>
            </div>
        </a>
        <a href="{{ url('/laporan/chart') }}" class="item">
            <div class="col">
                <ion-icon name="bar-chart-outline"></ion-icon>
                <strong>Statistik</strong>
            </div>
        </a>
        <a href="{{url('/profil')}}" class="item page-redirect">
            <div class="col">
                <ion-icon name="person-outline"></ion-icon>
                <strong>Profile</strong>
            </div>
        </a>
        <a href="javascript:;" class="item" data-toggle="modal" data-target="#sidebarPanel">
            <div class="col">
                <ion-icon name="menu-outline"></ion-icon>
                <strong>Menu</strong>
            </div>
        </a>
    </div>
    <!-- * App Bottom Menu -->

    @include('layouts.memberLayout.mobile.memberSidebar')

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

    <!-- Warning Message -->
    <div class="modal fade dialogbox" id="warning_message" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-danger">
                    <ion-icon name="help-circle-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">....</h5>
                </div>
                <div class="modal-body">
                    ....
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-danger" data-dismiss="modal">
                            TIDAK
                        </a>
                        <a href="#" class="btn btn-text-primary url page-redirect">
                            IYA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Warning Message -->

    <!-- Confirm Message -->
    <div class="modal fade dialogbox" id="confirm_message" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-icon text-danger">
                    <ion-icon name="help-circle-outline"></ion-icon>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title">....</h5>
                </div>
                <div class="modal-body">
                    ....
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <a href="#" class="btn btn-text-danger" data-dismiss="modal">
                            TIDAK
                        </a>
                        <form id="form_confirm">
                            {{csrf_field()}}
                            <input type="hidden" name="confirm_id" id="confirm_id">
                            <input type="hidden" name="confirm_url" id="confirm_url">
                        </form>
                        <a href="#" class="btn btn-text-primary" data-dismiss="modal" onclick="confirmed()">
                            IYA
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Confirm Message -->

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
    <!-- Jquery -->
    <script src="{{ asset('public/js/mobile/lib/jquery-3.4.1.min.js') }}"></script>
    <!-- Bootstrap-->
    <script src="{{ asset('public/js/mobile/lib/popper.min.js') }}"></script>
    <script src="{{ asset('public/js/mobile/lib/bootstrap.min.js') }}"></script>
    <!-- Ionicons -->
    <script src="https://unpkg.com/ionicons@5.0.0/dist/ionicons.js"></script>
    <!-- Owl Carousel -->
    <script src="{{ asset('public/js/mobile/plugins/owl-carousel/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>
    <!-- Base Js File -->
    <script src="{{ asset('public/js/mobile/base.js') }}"></script>
    <script type="text/javascript">
        var baseUrl = "{{url('/')}}", table = "";

        // Logout
        $(".logout").click(function(evt){
            evt.preventDefault();
            warningMessage('Konfirmasi', 'Keluar dari aplikasi?', baseUrl+'/logout');
        })
        // End logout

        // configurasi datepicker
        $.fn.datepicker.dates['id'] = {
          days: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
          daysShort: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
          daysMin: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
          months: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
          monthsShort: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
          today: "Hari Ini",
          clear: "Bersihkan",
          format: "yyyy-mm-dd",
          titleFormat: "MM yyyy",
          weekStart: 0
        };

        $(".input-datepicker").datepicker({
          autoclose: true,
          format: 'dd MM yyyy',
          todayHighlight: true,
          language: 'id'
        });
    </script>
    @yield('script')
</body>
</html>