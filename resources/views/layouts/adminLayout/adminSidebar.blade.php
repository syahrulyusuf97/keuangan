<?php $url = url()->current(); ?>
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
    <!-- Sidebar user panel -->
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('public/images/user2-160x160.jpg') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>
          @if(Session::has('adminName'))
            {!! session('adminName') !!}
          @endif
        </p>
        <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
      </div>
    </div>
    <!-- search form -->
    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
      </div>
    </form>
    <!-- /.search form -->
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class="header">MAIN NAVIGATION</li>
      <li <?php if(preg_match("/dashboard/i", $url)) { ?> class="active" <?php } ?>>
        <a href="{{ url('/dashboard') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <li <?php if(preg_match("/deposito/i", $url)) { ?> class="active" <?php } ?>>
        <a href="{{ url('/deposito') }}">
          <i class="fa fa-money"></i> <span>Deposito</span>
        </a>
      </li>
      <li <?php if(preg_match("/credit/i", $url)) { ?> class="active" <?php } ?>>
        <a href="{{ url('/credit') }}">
          <i class="fa fa-money"></i> <span>Kredit</span>
        </a>
      </li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>