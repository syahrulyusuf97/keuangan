<!DOCTYPE html>
<html lang="en">
<head>
<title>KeuanganKu | @yield('title')</title>
<meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
  <link rel="stylesheet" href="{{ asset('public/css/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('public/css/adminLTE/AdminLTE.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('public/css/adminLTE/Keuangan.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('public/css/skins/_all-skins.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('public/css/bootstrap/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('public/css/style/style.css') }}">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
  <link rel="stylesheet" href="{{ asset('public/fonts/fonts-google-apis/fonts.css') }}">
  <!-- <script data-ad-client="ca-pub-1006524802991381" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
  <div class="cover-spin"></div>
  <div class="wrapper">

    @include('layouts.adminLayout.adminHeader')

    @include('layouts.adminLayout.adminSidebar')
    <div class="content-wrapper" id="content">

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
<script src="{{ asset('public/css/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
<!-- AdminLTE App -->
<!-- <script src="{{ asset('public/js/adminLTE/adminlte.min.js') }}"></script> -->
<script src="{{ asset('public/js/adminLTE/adminlte.js') }}"></script>
<!-- <script src="{{ asset('public/js/adminLTE/app.js') }}"></script> -->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('public/js/adminLTE/dashboard.js') }}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('public/js/adminLTE/demo.js') }}"></script>

<script src="{{ asset('public/js/chart/Chart.js') }}"></script>
<script src="{{ asset('public/js/morris/morris.min.js') }}"></script>
<script src="{{ asset('public/js/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('public/js/dobpicker.js') }}"></script>
<!-- Chartjs -->
<script src="{{ asset('public/js/chart/Chart2-9-3.min.js') }}"></script>
<script src="{{ asset('public/js/chart/utils.js') }}"></script>
<!-- <script src="{{ asset('public/js/apps.js') }}"></script> -->


<script type="text/javascript">
  var baseUrl = '{{ url('/') }}';
  var h_s = $(window).height()-140;
  var h_mh = $('.main-header').height()*0.5;
  $(document).ready(function(){
    $(".sidebar-k-menu").slimScroll({
        height: h_s-h_mh+'px'
      });
  })

  $(".sidebar-k-toggle").click(function(e){
    e.preventDefault();
    if ($("body").hasClass("sidebar-k-open")) {
      $("body").removeClass('sidebar-k-open');
    } else {
      $("body").addClass('sidebar-k-open');
    }
  })

  $(document).ajaxSend(function(){
    // show loading
    $(".cover-spin").fadeIn(200);
  });

  $(document).ajaxComplete(function(){
    // close loading
    $(".cover-spin").fadeOut(200);
  });

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

  $('.pertanggal').datepicker({
    autoclose: true,
    format: 'dd MM yyyy',
    language: 'id'
  })

  $('.perbulan').datepicker({
    autoclose: true,
    format: 'MM yyyy',
    language: 'id',
    viewMode: 'months',
    minViewMode: 'months'
  })

  $('.pertahun').datepicker({
    autoclose: true,
    format: 'yyyy',
    language: 'id',
    viewMode: 'years',
    minViewMode: 'years'
  })

  function isNumberKey(evt) {
      var charCode = (evt.which) ? evt.which : evt.keyCode;
      if (charCode > 31 && (charCode < 48 || charCode > 57))
          return false;
      return true;
  }

  function number_format(number, decimals, dec_point, thousands_sep) {
      // http://kevin.vanzonneveld.net
      // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
      // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
      // +     bugfix by: Michael White (http://getsprink.com)
      // +     bugfix by: Benjamin Lupton
      // +     bugfix by: Allan Jensen (http://www.winternet.no)
      // +    revised by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
      // +     bugfix by: Howard Yeend
      // +    revised by: Luke Smith (http://lucassmith.name)
      // +     bugfix by: Diogo Resende
      // +     bugfix by: Rival
      // +      input by: Kheang Hok Chin (http://www.distantia.ca/)
      // +   improved by: davook
      // +   improved by: Brett Zamir (http://brett-zamir.me)
      // +      input by: Jay Klehr
      // +   improved by: Brett Zamir (http://brett-zamir.me)
      // +      input by: Amir Habibi (http://www.residence-mixte.com/)
      // +     bugfix by: Brett Zamir (http://brett-zamir.me)
      // +   improved by: Theriault
      // +   improved by: Drew Noakes
      // *     example 1: number_format(1234.56);
      // *     returns 1: '1,235'
      // *     example 2: number_format(1234.56, 2, ',', ' ');
      // *     returns 2: '1 234,56'
      // *     example 3: number_format(1234.5678, 2, '.', '');
      // *     returns 3: '1234.57'
      // *     example 4: number_format(67, 2, ',', '.');
      // *     returns 4: '67,00'
      // *     example 5: number_format(1000);
      // *     returns 5: '1,000'
      // *     example 6: number_format(67.311, 2);
      // *     returns 6: '67.31'
      // *     example 7: number_format(1000.55, 1);
      // *     returns 7: '1,000.6'
      // *     example 8: number_format(67000, 5, ',', '.');
      // *     returns 8: '67.000,00000'
      // *     example 9: number_format(0.9, 0);
      // *     returns 9: '1'
      // *    example 10: number_format('1.20', 2);
      // *    returns 10: '1.20'
      // *    example 11: number_format('1.20', 4);
      // *    returns 11: '1.2000'
      // *    example 12: number_format('1.2000', 3);
      // *    returns 12: '1.200'
      var n = !isFinite(+number) ? 0 : +number, 
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          toFixedFix = function (n, prec) {
              // Fix for IE parseFloat(0.55).toFixed(0) = 0;
              var k = Math.pow(10, prec);
              return Math.round(n * k) / k;
          },
          s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
      if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
  }

  function month(string)
  {
      var bulan = [];
      bulan["Januari"]    = "01";
      bulan["Februari"]   = "02";
      bulan["Maret"]      = "03";
      bulan["April"]      = "04";
      bulan["Mei"]        = "05";
      bulan["Juni"]       = "06";
      bulan["Juli"]       = "07";
      bulan["Agustus"]    = "08";
      bulan["September"]  = "09";
      bulan["Oktober"]    = "10";
      bulan["November"]   = "11";
      bulan["Desember"]   = "12";

      return bulan[string];
  }

  function getFormattedDate(data) {
    // var date = new Date(data);
    // var year = date.getFullYear();

    // var month = (1 + date.getMonth()).toString();
    // month = month.length > 1 ? month : '0' + month;

    // var day = date.getDate().toString();
    // day = day.length > 1 ? day : '0' + day;
    // return year + '-' + month + '-' + day;
    var date_split = data.split(" ");
    return date_split[2]+'-'+month(date_split[1])+'-'+date_split[0];
  }

  function getFormattedMonth(data) {
    // var date = new Date(Date.parse(data));
    //   var year = date.getFullYear();

    //   var month = (1 + date.getMonth()).toString();
    //   month = month.length > 1 ? month : '0' + month;
    
    //   return year + '-' + month;
    var date_split = data.split(" ");
    return month(date_split[0])+'-'+date_split[1];
  }

  function dateFormat(data, format="d-m-Y") {
    var date = new Date(data);

    var monthNames = [
        "Januari", "Februari", "Maret",
        "April", "Mei", "Juni", "Juli",
        "Agustus", "September", "Oktober",
        "November", "Desember"
      ];

    var year = date.getFullYear();

    var monthIndex = date.getMonth();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    if (format == "d-m-Y") {
      return day+'-'+month+'-'+year;
    } else if (format == "Y-m-d") {
      return year + '-' + month + '-' + day;
    } else if (format == "d M Y") {
      return day + ' ' + monthNames[monthIndex] + ' ' + year;
    }
  }

  function formatRupiah(angka, prefix)
  {
    var number_string = angka.replace(/[^,\d]/g, '').toString(),
      split = number_string.split(','),
      sisa  = split[0].length % 3,
      rupiah  = split[0].substr(0, sisa),
      ribuan  = split[0].substr(sisa).match(/\d{3}/gi);
      
    if (ribuan) {
      separator = sisa ? '.' : '';
      rupiah += separator + ribuan.join('.');
    }
    
    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
  }

  function rupiah(angka, prefix)
  {
      var number_string = angka.toString(),
          split = number_string.split(','),
          sisa  = split[0].length % 3,
          rupiah  = split[0].substr(0, sisa),
          ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

      if (ribuan) {
          separator = sisa ? '.' : '';
          rupiah += separator + ribuan.join('.');
      }

      rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
      return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
  }

  function toRupiah(angka) {
      parseInt(angka);
      var rupiah = '';
      var angkarev = angka.toString().split('').reverse().join('');
      for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
      var hasil = rupiah.split('',rupiah.length-1).reverse().join('');
      return hasil;
  }
</script>
</body>
</html>
