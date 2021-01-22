<!-- <div id="scroll"> -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if(auth()->user()->img == "")
          <img src="{{ asset('images/default.jpg') }}" class="img-circle" alt="User Image">
        @else
          <img src="{{ asset('images/'. auth()->user()->img) }}" class="img-circle" alt="User Image">
        @endif
      </div>
      <div class="pull-left info">
        <p>
          {{ auth()->user()->name }}
        </p>
        <a href="#"><i class="fa fa-circle"></i> {{ Helper::userOnlineStatus(Crypt::encrypt(auth()->user()->id)) }}</a>
      </div>
    </div>
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">NAVIGASI UTAMA</li>
      <li>
        <a href="{{ url('/dashboard') }}" data-turbolinks="true">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="treeview">
        <a href="#" data-turbolinks="false">
          <i class="fa fa-database"></i>
          <span>Master</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/master/akun') }}" data-turbolinks="true"><i class="fa fa-book"></i> Akun</a></li>
          <li><a href="{{ url('/master/kategori') }}" data-turbolinks="true"><i class="fa fa-list"></i> Kategori</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#" data-turbolinks="false">
          <i class="fa fa-bank"></i>
          <span>Bank</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/bank/bank-masuk') }}" data-turbolinks="true"><i class="fa fa-arrow-down"></i> Bank Masuk</a></li>
          <li><a href="{{ url('/bank/bank-keluar') }}" data-turbolinks="true"><i class="fa fa-arrow-up"></i> Bank Keluar</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#" data-turbolinks="false">
          <i class="fa fa-money"></i>
          <span>Kas</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/kas/masuk') }}" data-turbolinks="true"><i class="fa fa-arrow-down"></i> Kas Masuk</a></li>
          <li><a href="{{ url('/kas/keluar') }}" data-turbolinks="true"><i class="fa fa-arrow-up"></i> Kas Keluar</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#" data-turbolinks="false">
          <i class="fa fa-list-alt"></i>
          <span>Laporan</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/laporan/chart') }}" data-turbolinks="true"><i class="fa fa fa-bar-chart"></i> Chart/Grafik</a></li>
          <li><a href="{{ url('/laporan/cashflow') }}" data-turbolinks="true"><i class="fa fa-list"></i> Arus Kas/<i>Cashflow</i></a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#" data-turbolinks="false">
          <i class="fa fa-clock-o"></i>
          <span>Riwayat</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/riwayat/aktivitas') }}" data-turbolinks="true"><i class="fa fa-list"></i> Aktivitas</a></li>
          <li><a href="{{ url('/riwayat/bank') }}" data-turbolinks="true"><i class="fa fa-bank"></i> Bank</a></li>
          <li><a href="{{ url('/riwayat/kas') }}" data-turbolinks="true"><i class="fa fa-money"></i> Kas</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- </div> -->