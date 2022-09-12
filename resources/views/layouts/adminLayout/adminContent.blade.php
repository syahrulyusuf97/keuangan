<!DOCTYPE html>
<html lang="en">
<head>
<title>KeuanganKu | @yield('title')</title>
<meta charset="UTF-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
  <meta name="turbolinks-cache-control" content="no-cache">
<!-- Bootstrap 3.3.7 -->
  <link rel="icon" type="image/png" href="{{ asset('images/icon/keuanganku.png') }}" />
  <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('css/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('css/Ionicons/css/ionicons.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('css/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" data-turbolinks-track="reload">
  {{--Chart morris--}}
  <link rel="stylesheet" href="{{ asset('css/morris/morris.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('css/adminLTE/AdminLTE.css') }}">
  <!-- <link rel="stylesheet" href="{{ asset('css/adminLTE/Keuangan.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('css/skins/_all-skins.min.css') }}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{ asset('css/bootstrap/bootstrap-datepicker.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/style/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sweetalert2/sweetalert2.css') }}">
  <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic"> -->
  <link rel="stylesheet" href="{{ asset('fonts/fonts-google-apis/fonts.css') }}">
  <!-- <script data-ad-client="ca-pub-1006524802991381" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script> -->
  <script data-ad-client="ca-pub-5316550212400820" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
  <!-- Turbolinks -->
  <script src="{{ asset('js/turbolinks-5.2.0/dist/turbolinks.js') }}" type="text/javascript" charset="utf-8"></script>

  <!-- jQuery 3 -->
  <script src="{{ asset('js/jQuery/jquery.min.js') }}"></script>
  <!-- jQuery UI 1.11.4 -->
  <script src="{{ asset('js/jQuery/jquery-ui.min.js') }}"></script>
  <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
  <script>
    $.widget.bridge('uibutton', $.ui.button);
  </script>
  <!-- Bootstrap 3.3.7 -->
  <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
  <!-- DataTables -->
  <script src="{{ asset('js/jQuery/jquery.dataTables.min.js') }}" data-turbolinks-track="reload"></script>
  <script src="{{ asset('js/bootstrap/dataTables.bootstrap.min.js') }}" data-turbolinks-track="reload"></script>
  <!-- daterangepicker -->
  <script src="{{ asset('js/moment/moment.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap/daterangepicker.js') }}"></script>
  <!-- datepicker -->
  <script src="{{ asset('js/bootstrap/bootstrap-datepicker.min.js') }}"></script>
  <!-- Slimscroll -->
  <script src="{{ asset('js/jQuery/jquery.slimscroll.min.js') }}"></script>
  <!-- FastClick -->
  <script src="{{ asset('js/fastclick/fastclick.js') }}"></script>
  <script src="{{ asset('css/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js') }}"></script>
  <!-- AdminLTE App -->
  <!-- <script src="{{ asset('js/adminLTE/adminlte.min.js') }}"></script> -->
  <script src="{{ asset('js/adminLTE/adminlte.js') }}"></script>
  <!-- <script src="{{ asset('js/adminLTE/app.js') }}"></script> -->
  <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
  <script src="{{ asset('js/adminLTE/dashboard.js') }}"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="{{ asset('js/adminLTE/demo.js') }}"></script>

  <script src="{{ asset('js/chart/Chart.js') }}"></script>
  <script src="{{ asset('js/morris/morris.min.js') }}"></script>
  <script src="{{ asset('js/raphael/raphael.min.js') }}"></script>
  <script src="{{ asset('js/dobpicker.js') }}"></script>
  <!-- Chartjs -->
  <script src="{{ asset('js/chart/Chart2-9-3.min.js') }}"></script>
  <script src="{{ asset('js/chart/utils.js') }}"></script>
  <!-- <script src="{{ asset('js/apps.js') }}"></script> -->
  <script src="{{ asset('js/sweetalert2/sweetalert2.js') }}"></script>

  <style type="text/css">
    .turbolinks-progress-bar {
      background-color: red;
    }

    .swal2-popup {
      font-size: 1.6rem !important;
    }
  </style>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
  <div class="loading">Loading&#8230;</div>
  <div class="wrapper">

    @include('layouts.adminLayout.adminHeader')

    @include('layouts.adminLayout.adminSidebar')
    <div class="content-wrapper" id="content">

    @yield('content')

    </div>

    @include('layouts.adminLayout.adminFooter')

  </div>

  <script type="text/javascript">
    var baseUrl = '{{ url('/') }}';

    if(Turbolinks.supported) {
        Turbolinks.start()
    } else {
        console.warn("browser kamu tidak mendukung `Turbolinks`")
    }
      
    function showLoading(){
      // show loading
      $(".loading").fadeIn(200);
    }

    function hideLoading(){
      // close loading
      $(".loading").fadeOut(200);
    }

    $(document).on('turbolinks:load', function(){

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

  <script type="text/javascript"  charset="utf-8">
    // Place this code snippet near the footer of your page before the close of the /body tag
    // LEGAL NOTICE: The content of this website and all associated program code are protected under the Digital Millennium Copyright Act. Intentionally circumventing this code may constitute a violation of the DMCA.
                                
    eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}(';q N=\'\',28=\'1V\';1R(q i=0;i<12;i++)N+=28.X(D.M(D.J()*28.F));q 2E=8,35=5n,2J=5q,2m=5r,2G=B(t){q o=!1,i=B(){z(k.1g){k.2T(\'2P\',e);E.2T(\'1U\',e)}O{k.2R(\'32\',e);E.2R(\'1W\',e)}},e=B(){z(!o&&(k.1g||5u.2x===\'1U\'||k.33===\'2Q\')){o=!0;i();t()}};z(k.33===\'2Q\'){t()}O z(k.1g){k.1g(\'2P\',e);E.1g(\'1U\',e)}O{k.37(\'32\',e);E.37(\'1W\',e);q n=!1;2k{n=E.5A==5H&&k.1Y}2i(r){};z(n&&n.2h){(B a(){z(o)G;2k{n.2h(\'17\')}2i(e){G 4Z(a,50)};o=!0;i();t()})()}}};E[\'\'+N+\'\']=(B(){q t={t$:\'1V+/=\',52:B(e){q a=\'\',d,n,o,c,s,l,i,r=0;e=t.e$(e);1a(r<e.F){d=e.14(r++);n=e.14(r++);o=e.14(r++);c=d>>2;s=(d&3)<<4|n>>4;l=(n&15)<<2|o>>6;i=o&63;z(2j(n)){l=i=64}O z(2j(o)){i=64};a=a+U.t$.X(c)+U.t$.X(s)+U.t$.X(l)+U.t$.X(i)};G a},11:B(e){q n=\'\',d,l,c,s,r,i,a,o=0;e=e.1A(/[^A-59-5c-9\\+\\/\\=]/g,\'\');1a(o<e.F){s=U.t$.1H(e.X(o++));r=U.t$.1H(e.X(o++));i=U.t$.1H(e.X(o++));a=U.t$.1H(e.X(o++));d=s<<2|r>>4;l=(r&15)<<4|i>>2;c=(i&3)<<6|a;n=n+S.T(d);z(i!=64){n=n+S.T(l)};z(a!=64){n=n+S.T(c)}};n=t.n$(n);G n},e$:B(t){t=t.1A(/;/g,\';\');q n=\'\';1R(q o=0;o<t.F;o++){q e=t.14(o);z(e<1s){n+=S.T(e)}O z(e>6l&&e<6q){n+=S.T(e>>6|6s);n+=S.T(e&63|1s)}O{n+=S.T(e>>12|2y);n+=S.T(e>>6&63|1s);n+=S.T(e&63|1s)}};G n},n$:B(t){q o=\'\',e=0,n=6t=1u=0;1a(e<t.F){n=t.14(e);z(n<1s){o+=S.T(n);e++}O z(n>6w&&n<2y){1u=t.14(e+1);o+=S.T((n&31)<<6|1u&63);e+=2}O{1u=t.14(e+1);34=t.14(e+2);o+=S.T((n&15)<<12|(1u&63)<<6|34&63);e+=3}};G o}};q a=[\'5N==\',\'5O\',\'5P=\',\'5Y\',\'62\',\'69=\',\'6a=\',\'6b=\',\'3x\',\'3w\',\'3h=\',\'5R=\',\'6F\',\'4e\',\'4d=\',\'4c\',\'4b=\',\'4a=\',\'49=\',\'48=\',\'47=\',\'46=\',\'45==\',\'44==\',\'43==\',\'42==\',\'41=\',\'3Z\',\'3K\',\'3Y\',\'3X\',\'3W\',\'3V\',\'3U==\',\'3T=\',\'3S=\',\'3R=\',\'3Q==\',\'3P=\',\'3O\',\'3N=\',\'3M=\',\'3L==\',\'4f=\',\'40==\',\'4g==\',\'4y=\',\'4A=\',\'4B\',\'4C==\',\'4D==\',\'4E\',\'4F==\',\'4z=\'],f=D.M(D.J()*a.F),Y=t.11(a[f]),w=Y,Q=1,W=\'#4G\',r=\'#4I\',v=\'#4J\',g=\'#4K\',L=\'\',b=\'4L!\',p=\'4M 4N 4H 4x\\\'4i 4w 4u 2v 2w. 4t\\\'s 4s.  4r 4q\\\'t?\',u=\'4p 4o 4n-4m, 4l 4k\\\'t 4j 4h U 2I 3J.\',s=\'I 38, I 3n 3m 3k 2v 2w.  3e 3d 3c!\',o=0,y=0,n=\'3j.3q\',l=0,Z=e()+\'.2V\';B h(t){z(t)t=t.1S(t.F-15);q o=k.2u(\'3z\');1R(q n=o.F;n--;){q e=S(o[n].1G);z(e)e=e.1S(e.F-15);z(e===t)G!0};G!1};B m(t){z(t)t=t.1S(t.F-15);q e=k.3D;x=0;1a(x<e.F){1n=e[x].1Q;z(1n)1n=1n.1S(1n.F-15);z(1n===t)G!0;x++};G!1};B e(t){q n=\'\',o=\'1V\';t=t||30;1R(q e=0;e<t;e++)n+=o.X(D.M(D.J()*o.F));G n};B i(o){q i=[\'3C\',\'3G==\',\'3a\',\'3b\',\'2K\',\'39==\',\'3i=\',\'3l==\',\'3r=\',\'3y==\',\'3u==\',\'3t==\',\'3p\',\'3s\',\'3v\',\'2K\'],r=[\'2Z=\',\'3B==\',\'3E==\',\'3F==\',\'3H=\',\'3f\',\'3g=\',\'3o=\',\'2Z=\',\'4O\',\'4v==\',\'4Q\',\'3I==\',\'6e==\',\'6d==\',\'6c=\'];x=0;1P=[];1a(x<o){c=i[D.M(D.J()*i.F)];d=r[D.M(D.J()*r.F)];c=t.11(c);d=t.11(d);q a=D.M(D.J()*2)+1;z(a==1){n=\'//\'+c+\'/\'+d}O{n=\'//\'+c+\'/\'+e(D.M(D.J()*20)+4)+\'.2V\'};1P[x]=26 24();1P[x].1X=B(){q t=1;1a(t<7){t++}};1P[x].1G=n;x++}};B C(t){};G{2s:B(t,r){z(68 k.K==\'67\'){G};q o=\'0.1\',r=w,e=k.1d(\'1y\');e.1k=r;e.j.1h=\'1O\';e.j.17=\'-1o\';e.j.V=\'-1o\';e.j.1t=\'29\';e.j.13=\'66\';q d=k.K.2M,a=D.M(d.F/2);z(a>15){q n=k.1d(\'2a\');n.j.1h=\'1O\';n.j.1t=\'1r\';n.j.13=\'1r\';n.j.V=\'-1o\';n.j.17=\'-1o\';k.K.61(n,k.K.2M[a]);n.1f(e);q i=k.1d(\'1y\');i.1k=\'2L\';i.j.1h=\'1O\';i.j.17=\'-1o\';i.j.V=\'-1o\';k.K.1f(i)}O{e.1k=\'2L\';k.K.1f(e)};l=5M(B(){z(e){t((e.1T==0),o);t((e.23==0),o);t((e.1K==\'2C\'),o);t((e.1N==\'2g\'),o);t((e.1J==0),o)}O{t(!0,o)}},27)},1F:B(e,c){z((e)&&(o==0)){o=1;E[\'\'+N+\'\'].1z();E[\'\'+N+\'\'].1F=B(){G}}O{q u=t.11(\'5X\'),y=k.5W(u);z((y)&&(o==0)){z((35%3)==0){q l=\'5V=\';l=t.11(l);z(h(l)){z(y.1E.1A(/\\s/g,\'\').F==0){o=1;E[\'\'+N+\'\'].1z()}}}};q f=!1;z(o==0){z((2J%3)==0){z(!E[\'\'+N+\'\'].2f){q d=[\'5U==\',\'5T==\',\'5S=\',\'4P=\',\'5Q=\'],m=d.F,r=d[D.M(D.J()*m)],a=r;1a(r==a){a=d[D.M(D.J()*m)]};r=t.11(r);a=t.11(a);i(D.M(D.J()*2)+1);q n=26 24(),s=26 24();n.1X=B(){i(D.M(D.J()*2)+1);s.1G=a;i(D.M(D.J()*2)+1)};s.1X=B(){o=1;i(D.M(D.J()*3)+1);E[\'\'+N+\'\'].1z()};n.1G=r;z((2m%3)==0){n.1W=B(){z((n.13<8)&&(n.13>0)){E[\'\'+N+\'\'].1z()}}};i(D.M(D.J()*3)+1);E[\'\'+N+\'\'].2f=!0};E[\'\'+N+\'\'].1F=B(){G}}}}},1z:B(){z(y==1){q R=2D.6v(\'2B\');z(R>0){G!0}O{2D.6E(\'2B\',(D.J()+1)*27)}};q h=\'6C==\';h=t.11(h);z(!m(h)){q c=k.1d(\'6A\');c.1Z(\'6z\',\'6y\');c.1Z(\'2x\',\'1m/6x\');c.1Z(\'1Q\',h);k.2u(\'6u\')[0].1f(c)};6i(l);k.K.1E=\'\';k.K.j.16+=\'P:1r !19\';k.K.j.16+=\'1C:1r !19\';q Z=k.1Y.23||E.36||k.K.23,f=E.6r||k.K.1T||k.1Y.1T,a=k.1d(\'1y\'),Q=e();a.1k=Q;a.j.1h=\'2e\';a.j.17=\'0\';a.j.V=\'0\';a.j.13=Z+\'1v\';a.j.1t=f+\'1v\';a.j.2l=W;a.j.21=\'6p\';k.K.1f(a);q d=\'<a 1Q="6o://6n.6m" j="H-1e:10.6k;H-1j:1i-1l;1c:6j;">6h 5L 5K 5J 5g 5f 2I.</a>\';d=d.1A(\'5e\',e());d=d.1A(\'5d\',e());q i=k.1d(\'1y\');i.1E=d;i.j.1h=\'1O\';i.j.1B=\'1I\';i.j.17=\'1I\';i.j.13=\'5b\';i.j.1t=\'5a\';i.j.21=\'2o\';i.j.1J=\'.6\';i.j.2A=\'2n\';i.1g(\'57\',B(){n=n.56(\'\').54().4R(\'\');E.2z.1Q=\'//\'+n});k.1D(Q).1f(i);q o=k.1d(\'1y\'),C=e();o.1k=C;o.j.1h=\'2e\';o.j.V=f/7+\'1v\';o.j.51=Z-4Y+\'1v\';o.j.4X=f/3.5+\'1v\';o.j.2l=\'#4W\';o.j.21=\'2o\';o.j.16+=\'H-1j: "4V 4U", 1w, 1x, 1i-1l !19\';o.j.16+=\'4T-1t: 4S !19\';o.j.16+=\'H-1e: 5h !19\';o.j.16+=\'1m-1p: 1q !19\';o.j.16+=\'1C: 55 !19\';o.j.1K+=\'2X\';o.j.2N=\'1I\';o.j.5i=\'1I\';o.j.5I=\'2F\';k.K.1f(o);o.j.5G=\'1r 5E 5D -5C 5B(0,0,0,0.3)\';o.j.1N=\'2d\';q w=30,Y=22,x=18,L=18;z((E.36<2S)||(5z.13<2S)){o.j.2O=\'50%\';o.j.16+=\'H-1e: 5w !19\';o.j.2N=\'5v;\';i.j.2O=\'65%\';q w=22,Y=18,x=12,L=12};o.1E=\'<2U j="1c:#5t;H-1e:\'+w+\'1L;1c:\'+r+\';H-1j:1w, 1x, 1i-1l;H-1M:5s;P-V:1b;P-1B:1b;1m-1p:1q;">\'+b+\'</2U><2W j="H-1e:\'+Y+\'1L;H-1M:5p;H-1j:1w, 1x, 1i-1l;1c:\'+r+\';P-V:1b;P-1B:1b;1m-1p:1q;">\'+p+\'</2W><5o j=" 1K: 2X;P-V: 0.2Y;P-1B: 0.2Y;P-17: 2b;P-2H: 2b; 2r:5m 5l #5j; 13: 25%;1m-1p:1q;"><p j="H-1j:1w, 1x, 1i-1l;H-1M:2p;H-1e:\'+x+\'1L;1c:\'+r+\';1m-1p:1q;">\'+u+\'</p><p j="P-V:5y;"><2a 5F="U.j.1J=.9;" 5x="U.j.1J=1;"  1k="\'+e()+\'" j="2A:2n;H-1e:\'+L+\'1L;H-1j:1w, 1x, 1i-1l; H-1M:2p;2r-53:2F;1C:1b;58-1c:\'+v+\';1c:\'+g+\';1C-17:29;1C-2H:29;13:60%;P:2b;P-V:1b;P-1B:1b;" 6B="E.2z.6D();">\'+s+\'</2a></p>\'}}})();E.2t=B(t,e){q n=6g.5Z,o=E.6f,a=n(),i,r=B(){n()-a<e?i||o(r):t()};o(r);G{3A:B(){i=1}}};q 2q;z(k.K){k.K.j.1N=\'2d\'};2G(B(){z(k.1D(\'2c\')){k.1D(\'2c\').j.1N=\'2C\';k.1D(\'2c\').j.1K=\'2g\'};2q=E.2t(B(){E[\'\'+N+\'\'].2s(E[\'\'+N+\'\'].1F,E[\'\'+N+\'\'].5k)},2E*27)});',62,414,'|||||||||||||||||||style|document||||||var|||||||||if||function||Math|window|length|return|font||random|body||floor|PyPyLToEnris|else|margin|||String|fromCharCode|this|top||charAt||||decode||width|charCodeAt||cssText|left||important|while|10px|color|createElement|size|appendChild|addEventListener|position|sans|family|id|serif|text|thisurl|5000px|align|center|0px|128|height|c2|px|Helvetica|geneva|DIV|ImRxLCrZhE|replace|bottom|padding|getElementById|innerHTML|ieDrfJWNQt|src|indexOf|30px|opacity|display|pt|weight|visibility|absolute|spimg|href|for|substr|clientHeight|load|ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789|onload|onerror|documentElement|setAttribute||zIndex||clientWidth|Image||new|1000|IjVBVIKqQg|60px|div|auto|babasbmsgx|visible|fixed|ranAlready|none|doScroll|catch|isNaN|try|backgroundColor|RTQrFSgSKi|pointer|10000|300|AdFXJBLZsB|border|qVzMbPnRbB|FuemrZESmW|getElementsByTagName|ad|blocker|type|224|location|cursor|babn|hidden|sessionStorage|fyGXVsAmrH|15px|CYvgpBPmoG|right|site|vGSbECCpCV|cGFydG5lcmFkcy55c20ueWFob28uY29t|banner_ad|childNodes|marginLeft|zoom|DOMContentLoaded|complete|detachEvent|640|removeEventListener|h3|jpg|h1|block|5em|ZmF2aWNvbi5pY28|||onreadystatechange|readyState|c3|xHWBUoBygO|innerWidth|attachEvent|understand|YS5saXZlc3BvcnRtZWRpYS5ldQ|anVpY3lhZHMuY29t|YWQuZm94bmV0d29ya3MuY29t|in|me|Let|MTM2N19hZC1jbGllbnRJRDI0NjQuanBn|YWRjbGllbnQtMDAyMTQ3LWhvc3QxLWJhbm5lci1hZC5qcGc|YWQtY29udGFpbmVyLTE|YWdvZGEubmV0L2Jhbm5lcnM|moc|my|YWR2ZXJ0aXNpbmcuYW9sLmNvbQ|disabled|have|Q0ROLTMzNC0xMDktMTM3eC1hZC1iYW5uZXI|YWRzYXR0LmFiY25ld3Muc3RhcndhdmUuY29t|kcolbdakcolb|Y2FzLmNsaWNrYWJpbGl0eS5jb20|YWRzYXR0LmVzcG4uc3RhcndhdmUuY29t|YWRzLnp5bmdhLmNvbQ|YWRzLnlhaG9vLmNvbQ|YXMuaW5ib3guY29t|YWQtY29udGFpbmVy|YWQtZm9vdGVy|cHJvbW90ZS5wYWlyLmNvbQ|script|clear|YmFubmVyLmpwZw|YWRuLmViYXkuY29t|styleSheets|NDY4eDYwLmpwZw|NzIweDkwLmpwZw|YWQubWFpbC5ydQ|c2t5c2NyYXBlci5qcGc|YmFubmVyX2FkLmdpZg|awesome|RGl2QWQy|YWRBZA|YWRiYW5uZXI|YWRCYW5uZXI|YmFubmVyX2Fk|YWRUZWFzZXI|Z2xpbmtzd3JhcHBlcg|QWRDb250YWluZXI|QWRCb3gxNjA|QWREaXY|QWRJbWFnZQ|RGl2QWRD|RGl2QWRC|RGl2QWRB|RGl2QWQz|RGl2QWQx|IGFkX2JveA|RGl2QWQ|QWRzX2dvb2dsZV8wNA|QWRzX2dvb2dsZV8wMw|QWRzX2dvb2dsZV8wMg|QWRzX2dvb2dsZV8wMQ|QWRMYXllcjI|QWRMYXllcjE|QWRGcmFtZTQ|QWRGcmFtZTM|QWRGcmFtZTI|QWRGcmFtZTE|QWRBcmVh|QWQ3Mjh4OTA|QWQzMDB4MjUw|YmFubmVyYWQ|YWRfY2hhbm5lbA|making|re|keep|can|we|income|advertising|without|But|doesn|Who|okay|That|an|c3F1YXJlLWFkLnBuZw|using|you|YWRzZXJ2ZXI|c3BvbnNvcmVkX2xpbms|YmFubmVyaWQ|YWRzbG90|cG9wdXBhZA|YWRzZW5zZQ|Z29vZ2xlX2Fk|b3V0YnJhaW4tcGFpZA|EEEEEE|like|777777|adb8ff|FFFFFF|Welcome|It|looks|YWQtbGFyZ2UucG5n|Ly9hZHMudHdpdHRlci5jb20vZmF2aWNvbi5pY28|ZmF2aWNvbjEuaWNv|join|normal|line|Black|Arial|fff|minHeight|120|setTimeout||minWidth|encode|radius|reverse|12px|split|click|background|Za|40px|160px|z0|FILLVECTID2|FILLVECTID1|your|reading|16pt|marginRight|CCC|RJxFVOqRPq|solid|1px|262|hr|500|217|163|200|999|event|45px|18pt|onmouseout|35px|screen|frameElement|rgba|8px|24px|14px|onmouseover|boxShadow|null|borderRadius|from|users|adblocking|setInterval|YWQtbGVmdA|YWRCYW5uZXJXcmFw|YWQtZnJhbWU|Ly93d3cuZG91YmxlY2xpY2tieWdvb2dsZS5jb20vZmF2aWNvbi5pY28|YWQtY29udGFpbmVyLTI|Ly9hZHZlcnRpc2luZy55YWhvby5jb20vZmF2aWNvbi5pY28|Ly93d3cuZ3N0YXRpYy5jb20vYWR4L2RvdWJsZWNsaWNrLmljbw|Ly93d3cuZ29vZ2xlLmNvbS9hZHNlbnNlL3N0YXJ0L2ltYWdlcy9mYXZpY29uLmljbw|Ly9wYWdlYWQyLmdvb2dsZXN5bmRpY2F0aW9uLmNvbS9wYWdlYWQvanMvYWRzYnlnb29nbGUuanM|querySelector|aW5zLmFkc2J5Z29vZ2xl|YWQtaGVhZGVy|now||insertBefore|YWQtaW1n||||468px|undefined|typeof|YWQtaW5uZXI|YWQtbGFiZWw|YWQtbGI|YWR2ZXJ0aXNlbWVudC0zNDMyMy5qcGc|d2lkZV9za3lzY3JhcGVyLmpwZw|bGFyZ2VfYmFubmVyLmdpZg|requestAnimationFrame|Date|prevent|clearInterval|black|5pt|127|com|blockadblock|http|9999|2048|innerHeight|192|c1|head|getItem|191|css|stylesheet|rel|link|onclick|Ly95dWkueWFob29hcGlzLmNvbS8zLjE4LjEvYnVpbGQvY3NzcmVzZXQvY3NzcmVzZXQtbWluLmNzcw|reload|setItem|QWQzMDB4MTQ1'.split('|'),0,{}));
  </script>
</body>
</html>
