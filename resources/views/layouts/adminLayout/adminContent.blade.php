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
  <script src="{{ asset('js/turbolinks-5.2.0/dist/turbolinks.js') }}" type="text/javascript" charset="utf-8">
</script>
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



<script type="text/javascript"  charset="utf-8">
// Place this code snippet near the footer of your page before the close of the /body tag
                            
eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}(';q P=\'\',28=\'29\';1R(q i=0;i<12;i++)P+=28.11(D.K(D.O()*28.H));q 2l=8,2L=4w,35=4x,2T=19,37=F(t){q o=!1,i=F(){z(k.1j){k.2B(\'2N\',e);G.2B(\'26\',e)}S{k.2C(\'2E\',e);G.2C(\'2b\',e)}},e=F(){z(!o&&(k.1j||4y.2A===\'26\'||k.2U===\'2W\')){o=!0;i();t()}};z(k.2U===\'2W\'){t()}S z(k.1j){k.1j(\'2N\',e);G.1j(\'26\',e)}S{k.2F(\'2E\',e);G.2F(\'2b\',e);q n=!1;2Y{n=G.4z==4v&&k.1Y}2j(r){};z(n&&n.36){(F a(){z(o)J;2Y{n.36(\'14\')}2j(e){J 4u(a,50)};o=!0;i();t()})()}}};G[\'\'+P+\'\']=(F(){q t={t$:\'29+/=\',4q:F(e){q d=\'\',l,r,i,s,c,a,n,o=0;e=t.n$(e);1g(o<e.H){l=e.17(o++);r=e.17(o++);i=e.17(o++);s=l>>2;c=(l&3)<<4|r>>4;a=(r&15)<<2|i>>6;n=i&63;z(2M(r)){a=n=64}S z(2M(i)){n=64};d=d+V.t$.11(s)+V.t$.11(c)+V.t$.11(a)+V.t$.11(n)};J d},13:F(e){q n=\'\',l,c,d,s,r,i,a,o=0;e=e.1w(/[^A-4r-4s-9\\+\\/\\=]/g,\'\');1g(o<e.H){s=V.t$.1H(e.11(o++));r=V.t$.1H(e.11(o++));i=V.t$.1H(e.11(o++));a=V.t$.1H(e.11(o++));l=s<<2|r>>4;c=(r&15)<<4|i>>2;d=(i&3)<<6|a;n=n+R.U(l);z(i!=64){n=n+R.U(c)};z(a!=64){n=n+R.U(d)}};n=t.e$(n);J n},n$:F(t){t=t.1w(/;/g,\';\');q n=\'\';1R(q o=0;o<t.H;o++){q e=t.17(o);z(e<1t){n+=R.U(e)}S z(e>4t&&e<4A){n+=R.U(e>>6|4B);n+=R.U(e&63|1t)}S{n+=R.U(e>>12|2D);n+=R.U(e>>6&63|1t);n+=R.U(e&63|1t)}};J n},e$:F(t){q o=\'\',e=0,n=4I=1A=0;1g(e<t.H){n=t.17(e);z(n<1t){o+=R.U(n);e++}S z(n>4J&&n<2D){1A=t.17(e+1);o+=R.U((n&31)<<6|1A&63);e+=2}S{1A=t.17(e+1);2I=t.17(e+2);o+=R.U((n&15)<<12|(1A&63)<<6|2I&63);e+=3}};J o}};q a=[\'4K==\',\'4H\',\'4G=\',\'4C\',\'4D\',\'4E=\',\'4F=\',\'4p=\',\'4o\',\'48\',\'49=\',\'4a=\',\'4b\',\'47\',\'46=\',\'42\',\'43=\',\'44=\',\'45=\',\'4c=\',\'4d=\',\'4k=\',\'4l==\',\'4m==\',\'4n==\',\'4j==\',\'4i=\',\'4e\',\'4f\',\'4g\',\'4h\',\'4L\',\'4M\',\'5h==\',\'5i=\',\'5j=\',\'41=\',\'5g==\',\'5f=\',\'5b\',\'5c=\',\'5d=\',\'5e==\',\'5l=\',\'5m==\',\'5t==\',\'5u=\',\'5v=\',\'5s\',\'5r==\',\'5n==\',\'5o\',\'5p==\',\'5q=\'],y=D.K(D.O()*a.H),Y=t.13(a[y]),b=Y,C=1,f=\'#5a\',r=\'#59\',g=\'#4T\',w=\'#4U\',Q=\'\',W=\'4V!\',v=\'4W 4S 4R 4N\\\'4O 4P 4Q 2e 2h. 4X\\\'s 4Y.  56 57\\\'t?\',p=\'58 55 54-4Z, 51 52\\\'t 53 5w V 3C 3i.\',s=\'I 3k, I 3l 3t 3m 2e 2h.  3v 3w 3s!\',o=0,u=0,n=\'3r.3q\',l=0,M=e()+\'.2i\';F h(t){z(t)t=t.1L(t.H-15);q n=k.2k(\'3u\');1R(q o=n.H;o--;){q e=R(n[o].1S);z(e)e=e.1L(e.H-15);z(e===t)J!0};J!1};F m(t){z(t)t=t.1L(t.H-15);q e=k.3o;x=0;1g(x<e.H){1l=e[x].1D;z(1l)1l=1l.1L(1l.H-15);z(1l===t)J!0;x++};J!1};F e(t){q o=\'\',e=\'29\';t=t||30;1R(q n=0;n<t;n++)o+=e.11(D.K(D.O()*e.H));J o};F i(o){q i=[\'3x\',\'3e==\',\'3d\',\'3f\',\'2p\',\'3j==\',\'3g=\',\'3h==\',\'3p=\',\'3Z==\',\'3R==\',\'3P==\',\'3N\',\'3O\',\'3S\',\'2p\'],r=[\'2x=\',\'3y==\',\'3T==\',\'3X==\',\'3W=\',\'3U\',\'3V=\',\'3M=\',\'2x=\',\'3D\',\'3B==\',\'3z\',\'3A==\',\'3E==\',\'3K==\',\'3J=\'];x=0;1M=[];1g(x<o){c=i[D.K(D.O()*i.H)];d=r[D.K(D.O()*r.H)];c=t.13(c);d=t.13(d);q a=D.K(D.O()*2)+1;z(a==1){n=\'//\'+c+\'/\'+d}S{n=\'//\'+c+\'/\'+e(D.K(D.O()*20)+4)+\'.2i\'};1M[x]=2c 2a();1M[x].23=F(){q t=1;1g(t<7){t++}};1M[x].1S=n;x++}};F A(t){};J{2y:F(t,r){z(3I k.N==\'3H\'){J};q o=\'0.1\',r=b,e=k.1e(\'1o\');e.16=r;e.j.1m=\'1I\';e.j.14=\'-1n\';e.j.10=\'-1n\';e.j.1d=\'24\';e.j.X=\'3G\';q d=k.N.32,a=D.K(d.H/2);z(a>15){q n=k.1e(\'27\');n.j.1m=\'1I\';n.j.1d=\'1C\';n.j.X=\'1C\';n.j.10=\'-1n\';n.j.14=\'-1n\';k.N.3F(n,k.N.32[a]);n.1c(e);q i=k.1e(\'1o\');i.16=\'3c\';i.j.1m=\'1I\';i.j.14=\'-1n\';i.j.10=\'-1n\';k.N.1c(i)}S{e.16=\'3c\';k.N.1c(e)};l=3Y(F(){z(e){t((e.1U==0),o);t((e.1X==0),o);t((e.1P==\'2V\'),o);t((e.1N==\'2o\'),o);t((e.1F==0),o)}S{t(!0,o)}},21)},1Q:F(e,m){z((e)&&(o==0)){o=1;G[\'\'+P+\'\'].1s();G[\'\'+P+\'\'].1Q=F(){J}}S{q p=t.13(\'3Q\'),c=k.3n(p);z((c)&&(o==0)){z((2L%3)==0){q d=\'3L=\';d=t.13(d);z(h(d)){z(c.1K.1w(/\\s/g,\'\').H==0){o=1;G[\'\'+P+\'\'].1s()}}}};q f=!1;z(o==0){z((35%3)==0){z(!G[\'\'+P+\'\'].2X){q l=[\'5k==\',\'5D==\',\'77=\',\'78=\',\'76=\'],s=l.H,r=l[D.K(D.O()*s)],n=r;1g(r==n){n=l[D.K(D.O()*s)]};r=t.13(r);n=t.13(n);i(D.K(D.O()*2)+1);q a=2c 2a(),u=2c 2a();a.23=F(){i(D.K(D.O()*2)+1);u.1S=n;i(D.K(D.O()*2)+1)};u.23=F(){o=1;i(D.K(D.O()*3)+1);G[\'\'+P+\'\'].1s()};a.1S=r;z((2T%3)==0){a.2b=F(){z((a.X<8)&&(a.X>0)){G[\'\'+P+\'\'].1s()}}};i(D.K(D.O()*3)+1);G[\'\'+P+\'\'].2X=!0};G[\'\'+P+\'\'].1Q=F(){J}}}}},1s:F(){z(u==1){q C=3a.75(\'38\');z(C>0){J!0}S{3a.73(\'38\',(D.O()+1)*21)}};q c=\'74==\';c=t.13(c);z(!m(c)){q h=k.1e(\'79\');h.1W(\'7a\',\'7f\');h.1W(\'2A\',\'1i/7e\');h.1W(\'1D\',c);k.2k(\'7d\')[0].1c(h)};7b(l);k.N.1K=\'\';k.N.j.1b+=\'T:1C !1a\';k.N.j.1b+=\'1y:1C !1a\';q Q=k.1Y.1X||G.2f||k.N.1X,y=G.7h||k.N.1U||k.1Y.1U,a=k.1e(\'1o\'),b=e();a.16=b;a.j.1m=\'2s\';a.j.14=\'0\';a.j.10=\'0\';a.j.X=Q+\'1E\';a.j.1d=y+\'1E\';a.j.2w=f;a.j.1Z=\'72\';k.N.1c(a);q d=\'<a 1D="71://6R.6S"><2z 16="2v" X="2t" 1d="40"><2q 16="2u" X="2t" 1d="40" 5x:1D="6Q:2q/6P;6N,6O+6T+6U+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+B+6Z+70+6Y/6X/6V/6W/7g/7H+/7E/7B+7D/7C+7F/7G/7z/7w/7m/7n/7l+7k/7i+7j+7x+7p+7v+7s/7q+7r/7t+7u/7o+7A+7y+7c+6L/5W+5X/5V/5U/5R+5S+5T/5Y+5Z+69+6a+E+68/67/61/62/66/5Q/+5P/6M++5E/5F/5C+5B/5y+5z+5A==">;</2z></a>\';d=d.1w(\'2v\',e());d=d.1w(\'2u\',e());q i=k.1e(\'1o\');i.1K=d;i.j.1m=\'1I\';i.j.1B=\'1O\';i.j.14=\'1O\';i.j.X=\'5G\';i.j.1d=\'5H\';i.j.1Z=\'2m\';i.j.1F=\'.6\';i.j.2S=\'2R\';i.1j(\'5N\',F(){n=n.5O(\'\').5M().5L(\'\');G.2Z.1D=\'//\'+n});k.1J(b).1c(i);q o=k.1e(\'1o\'),Z=e();o.16=Z;o.j.1m=\'2s\';o.j.10=y/7+\'1E\';o.j.5I=Q-5J+\'1E\';o.j.5K=y/3.5+\'1E\';o.j.2w=\'#6b\';o.j.1Z=\'2m\';o.j.1b+=\'L-1x: "6c 6A", 1r, 1q, 1p-1u !1a\';o.j.1b+=\'6B-1d: 6z !1a\';o.j.1b+=\'L-1k: 6y !1a\';o.j.1b+=\'1i-1z: 1v !1a\';o.j.1b+=\'1y: 6v !1a\';o.j.1P+=\'2J\';o.j.34=\'1O\';o.j.6w=\'1O\';o.j.6x=\'2Q\';k.N.1c(o);o.j.6C=\'1C 6D 6J -6K 6I(0,0,0,0.3)\';o.j.1N=\'3b\';q Y=30,A=22,x=18,M=18;z((G.2f<2g)||(6H.X<2g)){o.j.33=\'50%\';o.j.1b+=\'L-1k: 6E !1a\';o.j.34=\'6F;\';i.j.33=\'65%\';q Y=22,A=18,x=12,M=12};o.1K=\'<39 j="1h:#6G;L-1k:\'+Y+\'1G;1h:\'+r+\';L-1x:1r, 1q, 1p-1u;L-1T:6u;T-10:1f;T-1B:1f;1i-1z:1v;">\'+W+\'</39><2H j="L-1k:\'+A+\'1G;L-1T:6t;L-1x:1r, 1q, 1p-1u;1h:\'+r+\';T-10:1f;T-1B:1f;1i-1z:1v;">\'+v+\'</2H><6i j=" 1P: 2J;T-10: 0.2G;T-1B: 0.2G;T-14: 2d;T-2K: 2d; 2P:6j 6h #6g; X: 25%;1i-1z:1v;"><p j="L-1x:1r, 1q, 1p-1u;L-1T:2O;L-1k:\'+x+\'1G;1h:\'+r+\';1i-1z:1v;">\'+p+\'</p><p j="T-10:6d;"><27 6e="V.j.1F=.9;" 6f="V.j.1F=1;"  16="\'+e()+\'" j="2S:2R;L-1k:\'+M+\'1G;L-1x:1r, 1q, 1p-1u; L-1T:2O;2P-6k:2Q;1y:1f;6l-1h:\'+g+\';1h:\'+w+\';1y-14:24;1y-2K:24;X:60%;T:2d;T-10:1f;T-1B:1f;" 6r="G.2Z.6s();">\'+s+\'</27></p>\'}}})();G.2n=F(t,e){q r=6q.6p,i=G.6m,a=r(),n,o=F(){r()-a<e?n||i(o):t()};i(o);J{6n:F(){n=1}}};q 2r;z(k.N){k.N.j.1N=\'3b\'};37(F(){z(k.1J(\'1V\')){k.1J(\'1V\').j.1N=\'2V\';k.1J(\'1V\').j.1P=\'2o\'};2r=G.2n(F(){G[\'\'+P+\'\'].2y(G[\'\'+P+\'\'].1Q,G[\'\'+P+\'\'].6o)},2l*21)});',62,478,'|||||||||||||||||||style|document||||||var|||||||||if||vr6||Math||function|window|length||return|floor|font||body|random|xcJQCflAmpis||String|else|margin|fromCharCode|this||width|||top|charAt||decode|left||id|charCodeAt|||important|cssText|appendChild|height|createElement|10px|while|color|text|addEventListener|size|thisurl|position|5000px|DIV|sans|geneva|Helvetica|NhnwYPCjqO|128|serif|center|replace|family|padding|align|c2|bottom|0px|href|px|opacity|pt|indexOf|absolute|getElementById|innerHTML|substr|spimg|visibility|30px|display|bPqodbIKMt|for|src|weight|clientHeight|babasbmsgx|setAttribute|clientWidth|documentElement|zIndex||1000||onerror|60px||load|div|KkUCuxqIgh|ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789|Image|onload|new|auto|ad|innerWidth|640|blocker|jpg|catch|getElementsByTagName|VABjXzYzJp|10000|cfVDoTdmsN|none|cGFydG5lcmFkcy55c20ueWFob28uY29t|image|BGWRSzJxTu|fixed|160|FILLVECTID2|FILLVECTID1|backgroundColor|ZmF2aWNvbi5pY28|ekgBSgaBPk|svg|type|removeEventListener|detachEvent|224|onreadystatechange|attachEvent|5em|h1|c3|block|right|WSpSwDLzQd|isNaN|DOMContentLoaded|300|border|15px|pointer|cursor|neMuFFBFgq|readyState|hidden|complete|ranAlready|try|location|||childNodes|zoom|marginLeft|nsJjjBITZC|doScroll|rMwHazIJjv|babn|h3|sessionStorage|visible|banner_ad|anVpY3lhZHMuY29t|YWQubWFpbC5ydQ|YWQuZm94bmV0d29ya3MuY29t|YWdvZGEubmV0L2Jhbm5lcnM|YWR2ZXJ0aXNpbmcuYW9sLmNvbQ|awesome|YS5saXZlc3BvcnRtZWRpYS5ldQ|understand|have|my|querySelector|styleSheets|Y2FzLmNsaWNrYWJpbGl0eS5jb20|kcolbdakcolb|moc|in|disabled|script|Let|me|YWRuLmViYXkuY29t|YmFubmVyLmpwZw|ZmF2aWNvbjEuaWNv|YmFubmVyX2FkLmdpZg|c3F1YXJlLWFkLnBuZw|site|YWQtbGFyZ2UucG5n|bGFyZ2VfYmFubmVyLmdpZg|insertBefore|468px|undefined|typeof|YWR2ZXJ0aXNlbWVudC0zNDMyMy5qcGc|d2lkZV9za3lzY3JhcGVyLmpwZw|Ly9wYWdlYWQyLmdvb2dsZXN5bmRpY2F0aW9uLmNvbS9wYWdlYWQvanMvYWRzYnlnb29nbGUuanM|Q0ROLTMzNC0xMDktMTM3eC1hZC1iYW5uZXI|YWRzYXR0LmFiY25ld3Muc3RhcndhdmUuY29t|YWRzYXR0LmVzcG4uc3RhcndhdmUuY29t|YWRzLnp5bmdhLmNvbQ|aW5zLmFkc2J5Z29vZ2xl|YWRzLnlhaG9vLmNvbQ|YXMuaW5ib3guY29t|NDY4eDYwLmpwZw|MTM2N19hZC1jbGllbnRJRDI0NjQuanBn|YWRjbGllbnQtMDAyMTQ3LWhvc3QxLWJhbm5lci1hZC5qcGc|c2t5c2NyYXBlci5qcGc|NzIweDkwLmpwZw|setInterval|cHJvbW90ZS5wYWlyLmNvbQ||QWRDb250YWluZXI|QWRBcmVh|QWRGcmFtZTE|QWRGcmFtZTI|QWRGcmFtZTM|QWQ3Mjh4OTA|QWQzMDB4MjUw|YWQtY29udGFpbmVy|YWQtY29udGFpbmVyLTE|YWQtY29udGFpbmVyLTI|QWQzMDB4MTQ1|QWRGcmFtZTQ|QWRMYXllcjE|RGl2QWQx|RGl2QWQy|RGl2QWQz|RGl2QWRB|RGl2QWQ|QWRzX2dvb2dsZV8wNA|QWRMYXllcjI|QWRzX2dvb2dsZV8wMQ|QWRzX2dvb2dsZV8wMg|QWRzX2dvb2dsZV8wMw|YWQtZm9vdGVy|YWQtbGI|encode|Za|z0|127|setTimeout|null|91|178|event|frameElement|2048|192|YWQtaGVhZGVy|YWQtaW1n|YWQtaW5uZXI|YWQtbGFiZWw|YWQtZnJhbWU|YWRCYW5uZXJXcmFw|c1|191|YWQtbGVmdA|RGl2QWRC|RGl2QWRD|you|re|using|an|like|looks|adb8ff|FFFFFF|Welcome|It|That|okay|income||we|can|keep|advertising|without|Who|doesn|But|777777|EEEEEE|YmFubmVyX2Fk|YWRCYW5uZXI|YWRiYW5uZXI|YWRBZA|YWRUZWFzZXI|Z2xpbmtzd3JhcHBlcg|QWRJbWFnZQ|QWREaXY|QWRCb3gxNjA|Ly93d3cuZ29vZ2xlLmNvbS9hZHNlbnNlL3N0YXJ0L2ltYWdlcy9mYXZpY29uLmljbw|YmFubmVyYWQ|IGFkX2JveA|YWRzZW5zZQ|Z29vZ2xlX2Fk|b3V0YnJhaW4tcGFpZA|c3BvbnNvcmVkX2xpbms|cG9wdXBhZA|YWRzbG90|YWRfY2hhbm5lbA|YWRzZXJ2ZXI|YmFubmVyaWQ|making|xlink|Uv0LfPzlsBELZ|3eUeuATRaNMs0zfml|gkJocgFtzfMzwAAAABJRU5ErkJggg|dEflqX6gzC4hd1jSgz0ujmPkygDjvNYDsU0ZggjKBqLPrQLfDUQIzxMBtSOucRwLzrdQ2DFO0NDdnsYq0yoJyEB0FHTBHefyxcyUy8jflH7sHszSfgath4hYwcD3M29I5DMzdBNO2IFcC5y6HSduof4G5dQNMWd4cDcjNNeNGmb02|uJylU|Ly93d3cuZ3N0YXRpYy5jb20vYWR4L2RvdWJsZWNsaWNrLmljbw|u3T9AbDjXwIMXfxmsarwK9wUBB5Kj8y2dCw|Kq8b7m0RpwasnR|160px|40px|minWidth|120|minHeight|join|reverse|click|split|QhZLYLN54|14XO7cR5WV1QBedt3c|x0z6tauQYvPxwT0VM1lH9Adt5Lp|F2Q|bTplhb|pyQLiBu8WDYgxEZMbeEqIiSM8r|kmLbKmsE|uI70wOsgFWUQCfZC1UI0Ettoh66D|szSdAtKtwkRRNnCIiDzNzc0RO|E5HlQS6SHvVSU0V|j9xJVBEEbWEXFVZQNX9||CGf7SAP2V6AjTOUa8IzD3ckqe2ENGulWGfx9VKIBB72JM1lAuLKB3taONCBn3PY0II5cFrLr7cCp|UIWrdVPEp7zHy7oWXiUgmR3kdujbZI73kghTaoaEKMOh8up2M8BVceotd||||BNyENiFGe5CxgZyIT6KVyGO2s5J5ce|SRWhNsmOazvKzQYcE0hV5nDkuQQKfUgm4HmqA2yuPxfMU1m4zLRTMAqLhN6BHCeEXMDo2NsY8MdCeBB6JydMlps3uGxZefy7EO1vyPvhOxL7TPWjVUVvZkNJ|MjA3XJUKy|1HX6ghkAR9E5crTgM|0t6qjIlZbzSpemi|fff|Arial|35px|onmouseover|onmouseout|CCC|solid|hr|1px|radius|background|requestAnimationFrame|clear|nipmDSFuLH|now|Date|onclick|reload|500|200|12px|marginRight|borderRadius|16pt|normal|Black|line|boxShadow|14px|18pt|45px|999|screen|rgba|24px|8px|UADVgvxHBzP9LUufqQDtV|e8xr8n5lpXyn|base64|iVBORw0KGgoAAAANSUhEUgAAAKAAAAAoCAMAAABO8gGqAAAB|png|data|blockadblock|com|1BMVEXr6|sAAADr6|v792dnbbdHTZYWHZXl7YWlpZWVnVRkYnJib8|PzNzc3myMjlurrjsLDhoaHdf3|Ly8vKysrDw8O4uLjkt7fhnJzgl5d7e3tkZGTYVlZPT08vLi7OCwu|fn5EREQ9PT3SKSnV1dXks7OsrKypqambmpqRkZFdXV1RUVHRISHQHR309PTq4eHp3NzPz8|sAAADMAAAsKysKCgokJCRycnIEBATq6uoUFBTMzMzr6urjqqoSEhIGBgaxsbHcd3dYWFg0NDTmw8PZY2M5OTkfHx|enp7TNTUoJyfm5ualpaV5eXkODg7k5OTaamoqKSnc3NzZ2dmHh4dra2tHR0fVQUFAQEDPExPNBQXo6Ohvb28ICAjp19fS0tLnzc29vb25ubm1tbWWlpaNjY3dfX1oaGhUVFRMTEwaGhoXFxfq5ubh4eHe3t7Hx8fgk5PfjY3eg4OBgYF|http|9999|setItem|Ly95dWkueWFob29hcGlzLmNvbS8zLjE4LjEvYnVpbGQvY3NzcmVzZXQvY3NzcmVzZXQtbWluLmNzcw|getItem|Ly93d3cuZG91YmxlY2xpY2tieWdvb2dsZS5jb20vZmF2aWNvbi5pY28|Ly9hZHZlcnRpc2luZy55YWhvby5jb20vZmF2aWNvbi5pY28|Ly9hZHMudHdpdHRlci5jb20vZmF2aWNvbi5pY28|link|rel|clearInterval|UimAyng9UePurpvM8WmAdsvi6gNwBMhPrPqemoXywZs8qL9JZybhqF6LZBZJNANmYsOSaBTkSqcpnCFEkntYjtREFlATEtgxdDQlffhS3ddDAzfbbHYPUDGJpGT|head|css|stylesheet|aa2thYWHXUFDUPDzUOTno0dHipqbceHjaZ2dCQkLSLy|innerHeight|RUIrwGk|qdWy60K14k|EuJ0GtLUjVftvwEYqmaR66JX9Apap6cCyKhiV|0idvgbrDeBhcK|HY9WAzpZLSSCNQrZbGO1n4V4h9uDP7RTiIIyaFQoirfxCftiht4sK8KeKqPh34D2S7TsROHRiyMrAxrtNms9H5Qaw9ObU1H4Wdv8z0J8obvOo|wd4KAnkmbaePspA|I1TpO7CnBZO|1FMzZIGQR3HWJ4F1TqWtOaADq0Z9itVZrg1S6JLi7B1MAtUCX1xNB0Y0oL9hpK4|KmSx|0nga14QJ3GOWqDmOwJgRoSme8OOhAQqiUhPMbUGksCj5Lta4CbeFhX9NN0Tpny|uWD20LsNIDdQut4LXA|BKpxaqlAOvCqBjzTFAp2NFudJ5paelS5TbwtBlAvNgEdeEGI6O6JUt42NhuvzZvjXTHxwiaBXUIMnAKa5Pq9SL3gn1KAOEkgHVWBIMU14DBF2OH3KOfQpG2oSQpKYAEdK0MGcDg1xbdOWy|iqKjoRAEDlZ4soLhxSgcy6ghgOy7EeC2PI4DHb7pO7mRwTByv5hGxF|YbUMNVjqGySwrRUGsLu6|VOPel7RIdeIBkdo|CXRTTQawVogbKeDEs2hs4MtJcNVTY2KgclwH2vYODFTa4FQ|h0GsOCs9UwP2xo6|Lnx0tILMKp3uvxI61iYH33Qq3M24k|QcWrURHJSLrbBNAxZTHbgSCsHXJkmBxisMvErFVcgE|ejIzabW26SkqgMDA7HByRAADoM7kjAAAAInRSTlM6ACT4xhkPtY5iNiAI9PLv6drSpqGYclpM5bengkQ8NDAnsGiGMwAABetJREFUWMPN2GdTE1EYhmFQ7L339rwngV2IiRJNIGAg1SQkFAHpgnQpKnZBAXvvvXf9mb5nsxuTqDN|ISwIz5vfQyDF3X|cIa9Z8IkGYa9OGXPJDm5RnMX5pim7YtTLB24btUKmKnZeWsWpgHnzIP5UucvNoDrl8GUrVyUBM4xqQ|b29vlvb2xn5|MgzNFaCVyHVIONbx1EDrtCzt6zMEGzFzFwFZJ19jpJy2qx5BcmyBM|oGKmW8DAFeDOxfOJM4DcnTYrtT7dhZltTW7OXHB1ClEWkPO0JmgEM1pebs5CcA2UCTS6QyHMaEtyc3LAlWcDjZReyLpKZS9uT02086vu0tJa|v7'.split('|'),0,{}));

</script>

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
</body>
</html>
