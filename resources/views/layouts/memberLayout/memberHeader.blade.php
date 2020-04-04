<header class="main-header">
	<!-- Logo -->
	<a href="{{url('/')}}" class="logo">
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
				<!-- User Account: style can be found in dropdown.less -->
				<li class="dropdown tasks-menu">
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
		              <!-- <i class="fa fa-flag-o"></i>
		              <span class="label label-danger">9</span> -->
		              <span class="saldo">Saldo = {{ Helper::displayRupiah(Helper::saldo()) }}</span>
		            </a>
		            <ul class="dropdown-menu">
		              <li class="header">Detail Saldo</li>
		              <li>
		                <!-- inner menu: contains the actual data -->
		                <ul class="menu">
		                  <li><!-- Task item -->
		                    <a href="{{url('/dashboard/detail-saldo/bank')}}">
		                      <h3>
		                        Bank
		                        <span class="pull-right">{{ Helper::displayRupiah(Helper::saldoBank()) }}</span>
		                      </h3>
		                      <!-- <div class="progress xs">
		                        <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar"
		                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          <span class="sr-only">20% Complete</span>
		                        </div>
		                      </div> -->
		                    </a>
		                  </li>
		                  <!-- end task item -->
		                  <li><!-- Task item -->
		                    <a href="{{url('/dashboard/detail-saldo/kas')}}">
		                      <h3>
		                        Kas
		                        <span class="pull-right">{{ Helper::displayRupiah(Helper::saldoKas()) }}</span>
		                      </h3>
		                      <!-- <div class="progress xs">
		                        <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar"
		                             aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
		                          <span class="sr-only">40% Complete</span>
		                        </div>
		                      </div> -->
		                    </a>
		                  </li>
		                </ul>
		              </li>
		              <li class="footer">
		                <!-- <a href="#">View all tasks</a> -->
		              </li>
		            </ul>
          		</li>
				<li class="dropdown user user-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						@if(auth()->user()->img == "")
							<img src="{{ asset('public/images/default.jpg') }}" class="user-image" alt="User Image">
						@else
							<img src="{{ asset('public/images/'.auth()->user()->img) }}" class="user-image" alt="User Image">
						@endif
						<span class="hidden-xs">
							@if(Session::has('adminName'))
								{!! auth()->user()->name !!}
					        @endif
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
								@if(Session::has('adminName')){!! auth()->user()->name !!}@endif
								<small>@if(auth()->user()->level == 1) Admin @elseif(auth()->user()->level == 2) Member @endif KeuanganKu</small>
							</p>
						</li>
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="{{ url('/profil') }}" class="btn btn-default btn-flat">Profil</a>
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