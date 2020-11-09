<header class="main-header">
	<!-- Logo -->
	<a href="#" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini"><b>K</b>KU</span>
		<!-- logo for regular state and mobile devices -->
		<span class="logo-lg"><b>Keuangan</b>KU</span>
	</a>
	<!-- Header Navbar: style can be found in header.less -->
	<nav class="navbar navbar-static-top">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-k-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>

		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if(auth()->user()->img == "")
							<img src="{{ asset('public/images/default.jpg') }}" class="user-image" alt="User Image">
						@else
							<img src="{{ asset('public/images/'.auth()->user()->img) }}" class="user-image" alt="User Image">
						@endif
						<span class="hidden-xs">
							{{ auth()->user()->name }}
						</span>
					</a>
					<ul class="dropdown-menu">
						<!-- User image -->
						<li class="user-header">
							@if(auth()->user()->img == "")
								<img src="{{ asset('public/images/default.jpg') }}" class="img-circle" alt="User Image">
							@else
								<img src="{{ asset('public/images/'. auth()->user()->img) }}" class="img-circle" alt="User Image">
							@endif

							<p>
								{{ auth()->user()->name }}
								<small>@if(auth()->user()->level == 1) Admin @elseif(auth()->user()->level == 2) Member @endif KeuanganKu</small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="{{ url('/admin/profile') }}" class="btn btn-default btn-flat">Profil</a>
							</div>
							<div class="pull-right">
								<a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Keluar</a>
							</div>
						</li>
					</ul>
				</li>
				<!-- Control Sidebar Toggle Button -->
				<!-- <li>
					<a href="#" data-toggle="control-sidebar-k"><i class="fa fa-gears"></i></a>
				</li> -->
			</ul>
		</div>
	</nav>
</header>