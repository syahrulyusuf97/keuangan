<?php $url = url()->current(); ?>
<!-- <div id="scroll"> -->
<aside class="main-sidebar-k">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar-k">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        @if(auth()->user()->img == "")
          <img src="{{ asset('public/images/default.jpg') }}" class="img-circle" alt="User Image">
        @else
          <img src="{{ asset('public/images/'. auth()->user()->img) }}" class="img-circle" alt="User Image">
        @endif
      </div>
      <div class="pull-left info">
        <p>
          @if(Session::has('adminName'))
            {!! auth()->user()->name !!}
          @endif
        </p>
        <a href="#"><i class="fa fa-circle text-success"></i> {{ Helper::userOnlineStatus(Crypt::encrypt(auth()->user()->id)) }}</a>
      </div>
    </div>
    <!-- search form -->
    {{--<form action="#" method="get" class="sidebar-k-form">--}}
      {{--<div class="input-group">--}}
        {{--<input type="text" name="q" class="form-control" placeholder="Search...">--}}
        {{--<span class="input-group-btn">--}}
              {{--<button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>--}}
              {{--</button>--}}
            {{--</span>--}}
      {{--</div>--}}
    {{--</form>--}}
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-k-menu" data-widget="tree">
      <li class="header">NAVIGASI UTAMA</li>
      <li>
        <a href="{{ url('/dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-database"></i>
          <span>Master</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/master/akun') }}"><i class="fa fa-book"></i> Akun</a></li>
          <li><a href="{{ url('/master/kategori') }}"><i class="fa fa-list"></i> Kategori</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-money"></i>
          <span>Kas</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/kas/masuk') }}"><i class="fa fa-arrow-down"></i> Kas Masuk</a></li>
          <li><a href="{{ url('/kas/keluar') }}"><i class="fa fa-arrow-up"></i> Kas Keluar</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-bank"></i>
          <span>Bank</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/bank/bank-masuk') }}"><i class="fa fa-arrow-down"></i> Bank Masuk</a></li>
          <li><a href="{{ url('/bank/bank-keluar') }}"><i class="fa fa-arrow-up"></i> Bank Keluar</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-list-alt"></i>
          <span>Laporan</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/laporan/chart') }}"><i class="fa fa fa-bar-chart"></i> Chart/Grafik</a></li>
          <li><a href="{{ url('/laporan/cashflow') }}"><i class="fa fa-list"></i> Arus Kas/<i>Cashflow</i></a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-clock-o"></i>
          <span>Riwayat</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/riwayat/aktivitas') }}"><i class="fa fa-list"></i> Aktivitas</a></li>
          <li><a href="{{ url('/riwayat/kas') }}"><i class="fa fa-money"></i> Kas</a></li>
          <li><a href="{{ url('/riwayat/bank') }}"><i class="fa fa-bank"></i> Bank</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- </div> -->