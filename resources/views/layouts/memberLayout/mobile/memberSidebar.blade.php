App Sidebar -->
<div class="modal fade panelbox panelbox-left" id="sidebarPanel" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body p-0">
                <!-- profile box -->
                <div class="profileBox pt-2 pb-2">
                    <div class="image-wrapper">
                        @if(auth()->user()->img == "")
                        <img src="{{ asset('images/default.jpg') }}" alt="image" class="imaged w36">
                        @else
                        <img src="{{ asset('images/'. auth()->user()->img) }}" alt="image" class="imaged  w36">
                        @endif
                    </div>
                    <div class="in">
                        <strong>
                            {{ auth()->user()->name }}
                        </strong>
                        <div class="text-muted">Member</div>
                    </div>
                    <a href="#" class="btn btn-link btn-icon sidebar-close" data-dismiss="modal" data-turbolinks="false">
                        <ion-icon name="close-outline"></ion-icon>
                    </a>
                </div>
                <!-- * profile box -->
                <!-- balance -->
                <div class="sidebar-balance">
                    <div class="listview-title">Saldo</div>
                    <div class="in">
                        <h1 class="amount total-saldo" style="font-size: 20px;">{{ Helper::displayRupiah(Helper::saldo()) }}</h1>
                    </div>
                </div>
                <!-- * balance -->

                <!-- menu -->
                <ul class="sidebar-k-menu" data-widget="tree">
                  <li class="header">NAVIGASI UTAMA</li>
                  <li>
                    <a href="{{ url('/dashboard') }}" data-turbolinks="true"> <!-- Tambahkan class="page-redirect" untuk memunculkan preloading page -->
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
                  <li class="header">LAINNYA</li>
                  <li>
                    <a href="{{ url('/profil') }}" data-turbolinks="true">
                      <i class="fa fa-gears"></i> <span>Pengaturan</span>
                    </a>
                  </li>
                  <li>
                    <a href="#" data-turbolinks="false" class="logout">
                      <i class="fa fa-sign-out"></i> <span>Keluar</span>
                    </a>
                  </li>
                </ul>

                <!-- 
                <div class="listview-title mt-1">Menu</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="{{url('/dashboard')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="home-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Dashboard
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/master/akun')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="book-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Master Akun
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/master/kategori')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="document-text-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Master Kategori
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/kas/masuk')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="arrow-down-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Kas Masuk
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/kas/keluar')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="arrow-up-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Kas Keluar
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/bank/bank-masuk')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="arrow-down-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Bank Masuk
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{url('/bank/bank-keluar')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="arrow-up-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Bank Keluar
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/laporan/chart') }}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="bar-chart-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Statistik
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/laporan/cashflow') }}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="book-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Arus Kas
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/riwayat/aktivitas') }}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="list-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Riwayat Aktivitas
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/riwayat/kas') }}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="wallet-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Riwayat Kas
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/riwayat/bank') }}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="card-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Riwayat Bank
                            </div>
                        </a>
                    </li>
                </ul> 
                -->
                <!-- * menu -->

                <!-- others -->
                <!--
                <div class="listview-title mt-1">Lainnya</div>
                <ul class="listview flush transparent no-line image-listview">
                    <li>
                        <a href="{{url('/profil')}}" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="settings-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Pengaturan
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="component-messages.html" class="item page-redirect">
                            <div class="icon-box bg-primary">
                                <ion-icon name="chatbubble-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Bantuan
                            </div>
                        </a>
                    </li> 
                    <li>
                        <a href="#" class="item logout">
                            <div class="icon-box bg-primary">
                                <ion-icon name="log-out-outline"></ion-icon>
                            </div>
                            <div class="in">
                                Keluar
                            </div>
                        </a>
                    </li>
                </ul>
                -->
                <!-- * others -->

            </div>
        </div>
    </div>
</div>
<!-- * App Sidebar