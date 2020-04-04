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
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
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
        <a href="{{ url('/admin/dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-users"></i>
          <span>Member</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/admin/users') }}"><i class="fa fa-users"></i> Data Member</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-user"></i>
          <span>Profil</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/admin/profile') }}"><i class="fa fa-user"></i> Profil Admin</a></li>
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
          <li><a href="{{ url('/admin/riwayat/aktivitas') }}"><i class="fa fa-list"></i> Aktivitas</a></li>
        </ul>
      </li>
      <li>
        <a href="{{ url('/admin/pesan') }}">
          <i class="fa fa-envelope"></i> <span>Pesan Masuk</span>
          @if(Helper::countUnread() > 0)
          <span class="pull-right-container">
            <span class="label label-primary pull-right">{{Helper::countUnread()}}</span>
          </span>
          @endif
        </a>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-home"></i>
          <span>Index</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/admin/index/identitas-app') }}"><i class="fa fa-circle"></i> Identitas APP</a></li>
          <li><a href="{{ url('/admin/index/layanan') }}"><i class="fa fa-circle"></i> Layanan</a></li>
          <li><a href="{{ url('/admin/index/syarat') }}"><i class="fa fa-circle"></i> Syarat & Ketentuan</a></li>
          <li><a href="{{ url('/admin/index/kebijakan') }}"><i class="fa fa-circle"></i> Kebijakan Privasi</a></li>
        </ul>
      </li>
      <li class="treeview">
        <a href="#">
          <i class="fa fa-list"></i>
          <span>Artikel</span>
          <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
          <li><a href="{{ url('/admin/article') }}"><i class="fa fa-circle"></i> Daftar Artikel</a></li>
        </ul>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<!-- </div> -->