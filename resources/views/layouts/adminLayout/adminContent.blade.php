<!DOCTYPE html>
<html lang="en">
<head>
<title>KeuanganKu</title>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<!-- Bootstrap 3.3.7 -->
  <link rel="icon" type="image/png" href="{{ asset('public/images/icon/keuanganku.png') }}" />
  <link rel="stylesheet" href="{{ asset('public/css/bootstrap/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('public/css/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('public/css/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('public/css/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  {{--Chart morris--}}
  <link rel="stylesheet" href="{{ asset('public/css/morris/morris.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/css/adminLTE/AdminLTE.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/skins/_all-skins.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('public/css/bootstrap/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/style/style.css') }}">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="skin-red-light sidebar-mini fixed">
  <div class="wrapper">

    @include('layouts.adminLayout.adminHeader')

    @include('layouts.adminLayout.adminSidebar')
    <div class="content-wrapper">

    @yield('content')

    </div>

    @include('layouts.adminLayout.adminFooter')

  </div>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ asset('public/js/jQuery/jquery-ui.min.js') }}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('public/js/bootstrap/bootstrap.min.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('public/js/jQuery/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/dataTables.bootstrap.min.js') }}"></script>
<!-- daterangepicker -->
<script src="{{ asset('public/js/moment/moment.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/daterangepicker.js') }}"></script>
<!-- datepicker -->
<script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('public/js/jQuery/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('public/js/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('public/js/adminLTE/adminlte.min.js') }}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('public/js/adminLTE/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/js/adminLTE/demo.js') }}"></script>

<script src="{{ asset('public/js/chart/Chart.js') }}"></script>
  <script src="{{ asset('public/js/morris/morris.min.js') }}"></script>
  <script src="{{ asset('public/js/raphael/raphael.min.js') }}"></script>

</body>
</html>
