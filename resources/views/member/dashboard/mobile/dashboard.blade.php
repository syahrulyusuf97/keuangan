@extends('layouts.memberLayout.mobile.memberContent')
@section('title', 'Dashboard')

@section('stylesheet')
<link rel="stylesheet" href="{{ asset('public/css/morris/morris.css') }}">
@endsection

@section('content')

<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        <!-- <img src="{{ asset('public/images/icon/logo.png') }}" alt="logo" class="logo"> -->
        <span class="logo">KeuanganKu</span>
    </div>
    <div class="right">
        <!-- <a href="app-notifications.html" class="headerButton">
            <ion-icon class="icon" name="notifications-outline"></ion-icon>
            <span class="badge badge-danger">4</span>
        </a> -->
        <a href="{{url('/profil')}}" class="headerButton">
            @if(auth()->user()->img == "")
            <img src="{{ asset('public/images/default.jpg') }}" alt="image" class="imaged w32">
            @else
            <img src="{{ asset('public/images/'. auth()->user()->img) }}" alt="image" class="imaged w32">
            @endif
            <!-- <span class="badge badge-danger">6</span> -->
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <!-- Wallet Card -->
    <div class="section wallet-card-section pt-1">
        <div class="wallet-card">
            <!-- Balance -->
            <div class="balance">
                <div class="left">
                    <span class="title">Total Saldo</span>
                    <h1 class="total">{{ Helper::displayRupiah(Helper::saldo()) }}</h1>
                </div>
            </div>
            <!-- * Balance -->
            <!-- Wallet Footer -->
            <div class="wallet-footer">
                <div class="item">
                    <a href="{{url('/kas/masuk')}}" class="page-redirect">
                        <div class="icon-wrapper bg-danger">
                            <ion-icon name="arrow-down-outline"></ion-icon>
                        </div>
                        <strong>Kas Masuk</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{url('/kas/keluar')}}" class="page-redirect">
                        <div class="icon-wrapper">
                            <ion-icon name="arrow-up-outline"></ion-icon>
                        </div>
                        <strong>Kas Keluar</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{url('/bank/bank-masuk')}}" class="page-redirect">
                        <div class="icon-wrapper bg-success">
                            <ion-icon name="arrow-down-outline"></ion-icon>
                        </div>
                        <strong>Bank Masuk</strong>
                    </a>
                </div>
                <div class="item">
                    <a href="{{url('/bank/bank-keluar')}}" class="page-redirect">
                        <div class="icon-wrapper bg-warning">
                            <ion-icon name="arrow-up-outline"></ion-icon>
                        </div>
                        <strong>Bank Keluar</strong>
                    </a>
                </div>

            </div>
            <!-- * Wallet Footer -->
        </div>
    </div>
    <!-- Wallet Card -->

    <!-- Stats -->
    <div class="section">
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Sisa Saldo Bank</div>
                    <div class="value text-success">{{ Helper::displayRupiah($saldo_bank) }}</div>
                    <div class="mt-2">
                        <a href="{{url('/dashboard/detail-saldo/bank')}}" class="btn btn-primary btn-block btn-sm">Detail Saldo</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Sisa Saldo Kas</div>
                    <div class="value text-success">{{ Helper::displayRupiah($saldo_kas) }}</div>
                    <div class="mt-2">
                        <a href="{{url('/dashboard/detail-saldo/kas')}}" class="btn btn-primary btn-block btn-sm">Detail Saldo</a>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Bank Masuk Bulan Lalu</div>
                    <div class="value text-info">{{ Helper::displayRupiah($debit_bank_last_month) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Bank Keluar Bulan Lalu</div>
                    <div class="value text text-danger">{{ Helper::displayRupiah($credit_bank_last_month) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Bank Masuk Tahun Lalu</div>
                    <div class="value text-info">{{ Helper::displayRupiah($debit_bank_last_year) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Bank Keluar Tahun Lalu</div>
                    <div class="value text text-danger">{{ Helper::displayRupiah($credit_bank_last_year) }}</div>
                </div>
            </div>
        </div>
        <hr>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Kas Masuk Bulan Lalu</div>
                    <div class="value text-info">{{ Helper::displayRupiah($debit_kas_last_month) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Kas keluar Bulan Lalu</div>
                    <div class="value text text-danger">{{ Helper::displayRupiah($credit_kas_last_month) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Kas Masuk Tahun Lalu</div>
                    <div class="value text-info">{{ Helper::displayRupiah($debit_kas_last_year) }}</div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <div class="stat-box">
                    <div class="title">Kas Keluar Tahun Lalu</div>
                    <div class="value text text-danger">{{ Helper::displayRupiah($credit_kas_last_year) }}</div>
                </div>
            </div>
        </div>
    </div>
    <!-- * Stats -->

    <!-- Transactions -->
    <div class="section mt-4">
        <div class="section-heading">
            <h2 class="title">Statistik</h2>
        </div>
        <div class="goals">
            <!-- item -->
            <div class="item" style="overflow: auto; padding-right: 24px">
                <div class="" style="width: 200%">
                    <div class="in">
                        <div>
                            <h4 class="l-k-m-b-l">Statistik Mutasi Bank Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="chart-responsive">
                        <div class="chart" id="line-chart-mutasi-bank" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item" style="overflow: auto; padding-right: 24px">
                <div class="" style="width: 200%">
                    <div class="in">
                        <div>
                            <h4 class="l-k-m-b-l">Statistik Mutasi Kas Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="chart-responsive">
                        <div class="chart" id="line-chart-mutasi-kas" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-b-m-ktg-b-l">Statistik Bank Debet (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-b-m-b-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_bm_b_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-b-k-ktg-b-l">Statistik Bank Kredit (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-b-k-b-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_bk_b_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-k-m-ktg-b-l">Statistik Kas Debet (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-k-m-b-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_km_b_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-k-k-ktg-b-l">Statistik Kas Kredit (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-k-k-b-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_kk_b_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-b-m-ktg-t-l">Statistik Bank Debet (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-b-m-t-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_bm_t_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-b-k-ktg-t-l">Statistik Bank Kredit (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-b-k-t-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_bk_t_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-k-m-ktg-t-l">Statistik Kas Debet (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-k-m-t-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_km_t_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item">
                <div class="in">
                    <div>
                        <h4 class="l-k-k-ktg-t-l">Statistik Kas Kredit (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h4>
                    </div>
                </div>
                <hr>
                <div class="chart-responsive">
                    <canvas class="chart" id="dnt-ch-k-k-t-l"></canvas>
                </div>
                <hr>
                <div class="col-12 table-responsive">
                    <table class="table table-bordered" id="tb_kk_t_l">
                        <tr>
                          <th>Kategori</th>
                          <th>Jumlah</th>
                          <th>Persen(%)</th>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item" style="overflow: auto; padding-right: 24px">
                <div style="width: 200%;">
                    <div class="in">
                        <div>
                            <h4 class="analisa">Statistik Mutasi Bank Tahun {{ date('Y', strtotime('-1 year')) }}</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="chart-responsive">
                        <div class="chart" id="bar-chart-bank" style="height: 300px;"></div>
                    </div>
                    <hr>
                </div>
            </div>
            <!-- * item -->
            <!-- item -->
            <div class="item" style="overflow: auto; padding-right: 24px">
                <div style="width: 200%;">
                    <div class="in">
                        <div>
                            <h4 class="analisa">Statistik Mutasi Kas Tahun {{ date('Y', strtotime('-1 year')) }}</h4>
                        </div>
                    </div>
                    <hr>
                    <div class="chart-responsive">
                        <div class="chart" id="bar-chart-kas" style="height: 300px;"></div>
                    </div>
                    <hr>
                </div>
            </div>
            <!-- * item -->
        </div>
    </div>
    <!-- * Transactions -->


    @include('layouts.memberLayout.mobile.memberFooter')

</div>
<!-- * App Capsule -->

@endsection

@section('script')
<script src="{{ asset('public/js/chart/Chart.js') }}"></script>
<script src="{{ asset('public/js/morris/morris.min.js') }}"></script>
<script src="{{ asset('public/js/raphael/raphael.min.js') }}"></script>
<script src="{{ asset('public/js/chart/Chart2-9-3.min.js') }}"></script>
<script src="{{ asset('public/js/chart/utils.js') }}"></script>
<script type="text/javascript">
    var data_kd = [], data_kk = [], data_bd = [], data_bk = [],
        data_t_kd = [], data_t_kk = [], data_t_bd = [], data_t_bk = [], 
        label_kd = [], label_kk = [], label_bd = [], label_bk = [],
        label_t_kd = [], label_t_kk = [], label_t_bd = [], label_t_bk = [],
        warna_kd = [], warna_kk = [], warna_bd = [], warna_bk = [],
        warna_t_kd = [], warna_t_kk = [], warna_t_bd = [], warna_t_bk = [];

    function setStatistikMutasiBank(data) {
        if (data.length == 0) {
           $("#line-chart-mutasi-bank").html('<h2 class="text-center">Tidak ada transaksi</h2>');
        } else {
            new Morris.Line({
                element: 'line-chart-mutasi-bank',
                resize: true,
                data: data,
                xkey: 'date',
                ykeys: ['debit','kredit'],
                labels: ['Debet','Kredit'],
                lineColors: ['#3c8dbc','#d9534f'],
                hideHover: 'auto',
                parseTime: false
            });
        }
    }

    function setStatistikMutasiKas(data) {
        if (data.length == 0) {
           $("#line-chart-mutasi-kas").html('<h2 class="text-center">Tidak ada transaksi</h2>');
        } else {
            new Morris.Line({
                element: 'line-chart-mutasi-kas',
                resize: true,
                data: data,
                xkey: 'date',
                ykeys: ['debit','kredit'],
                labels: ['Debet','Kredit'],
                lineColors: ['#3c8dbc','#d9534f'],
                hideHover: 'auto',
                parseTime: false
            });
        }
    }

    function setStatistikBank(data) {
        if (data.length == 0) {
            $("#bar-chart-bank").html('<h1 class="text-center">Tidak ada transaksi</h1>');
        } else {
            new Morris.Bar({
                element: 'bar-chart-bank',
                resize: true,
                data: data,
                barColors: ['#00a65a', '#f56954'],
                xkey: 'month',
                ykeys: ['debit', 'kredit'],
                labels: ['DEBIT', 'KREDIT'],
                hideHover: 'auto',
                parseTime: false
            });
        }
    }

    function setStatistikKas(data) {
        if (data.length == 0) {
            $("#bar-chart-kas").html('<h1 class="text-center">Tidak ada transaksi</h1>');
        } else {
            new Morris.Bar({
                element: 'bar-chart-kas',
                resize: true,
                data: data,
                barColors: ['#00a65a', '#f56954'],
                xkey: 'month',
                ykeys: ['debit', 'kredit'],
                labels: ['DEBIT', 'KREDIT'],
                hideHover: 'auto',
                parseTime: false
            });
        }
    }

    function setStatistikKategoriBankDebetBulanLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-b-m-b-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_bd.push(element.persen);
                label_bd.push(element.kategori);
                warna_bd.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_bm_b_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_bd,
                        backgroundColor: warna_bd,
                        label: 'Dataset 1'
                    }],
                    labels: label_bd
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-b-m-b-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriBankKreditBulanLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-b-k-b-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_bk.push(element.persen);
                label_bk.push(element.kategori);
                warna_bk.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_bk_b_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_bk,
                        backgroundColor: warna_bk,
                        label: 'Dataset 1'
                    }],
                    labels: label_bk
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-b-k-b-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriKasDebetBulanLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-k-m-b-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_kd.push(element.persen);
                label_kd.push(element.kategori);
                warna_kd.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+Math.round(tot_percent)+'%</td></tr>';

            $("#tb_km_b_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_kd,
                        backgroundColor: warna_kd,
                        label: 'Dataset 1'
                    }],
                    labels: label_kd
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-k-m-b-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriKasKreditBulanLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-k-k-b-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_kk.push(element.persen);
                label_kk.push(element.kategori);
                warna_kk.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+Math.round(tot_percent)+'%</td></tr>';

            $("#tb_kk_b_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_kk,
                        backgroundColor: warna_kk,
                        label: 'Dataset 1'
                    }],
                    labels: label_kk
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-k-k-b-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriBankDebetTahunLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-b-m-t-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_t_bd.push(element.persen);
                label_t_bd.push(element.kategori);
                warna_t_bd.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_bm_t_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_t_bd,
                        backgroundColor: warna_t_bd,
                        label: 'Dataset 1'
                    }],
                    labels: label_t_bd
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-b-m-t-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriBankKreditTahunLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-b-k-t-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_t_bk.push(element.persen);
                label_t_bk.push(element.kategori);
                warna_t_bk.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_bk_t_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_t_bk,
                        backgroundColor: warna_t_bk,
                        label: 'Dataset 1'
                    }],
                    labels: label_t_bk
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-b-k-t-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriKasDebetTahunLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-k-m-t-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_t_kd.push(element.persen);
                label_t_kd.push(element.kategori);
                warna_t_kd.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_km_t_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_t_kd,
                        backgroundColor: warna_t_kd,
                        label: 'Dataset 1'
                    }],
                    labels: label_t_kd
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-k-m-t-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function setStatistikKategoriKasKreditTahunLalu(data) {
        if (data.length == 0) {
            var ctx = document.getElementById('dnt-ch-k-k-t-l').getContext('2d');
            ctx.font = "30px Arial";
            ctx.fillText("Tidak ada transaksi", 10, 50);
        } else {
            var row = "";
            var total = 0;
            var tot_percent = 0;
            data.forEach(function(element){
                data_t_kk.push(element.persen);
                label_t_kk.push(element.kategori);
                warna_t_kk.push(element.warna);
                row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                total += parseFloat(element.jumlah);
                tot_percent += parseFloat(element.persen);
            })

            row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

            $("#tb_kk_t_l").append(row);

            var config = {
                type: 'pie',
                data: {
                    datasets: [{
                        data: data_t_kk,
                        backgroundColor: warna_t_kk,
                        label: 'Dataset 1'
                    }],
                    labels: label_t_kk
                },
                options: {
                    responsive: true,
                    legend: {
                                display: false,
                                position: 'right',
                                align: 'start'
                            },
                    tooltips: {
                        callbacks: {
                          label: function(tooltipItem, data) {

                            var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                            if (label) {
                              label += '%';
                            }

                            return label;
                          }
                        }
                      }
                }
            };

            var ctx = document.getElementById('dnt-ch-k-k-t-l').getContext('2d');
            window.myPie = new Chart(ctx, config);
        }
    }

    function getStatistik() {
        $.ajax({
            url: baseUrl+'/dashboard/statistik',
            dataType: 'json',
            success: function(response){
                if (response.status == "failed") {
                    errorMessage('Warning', response.message);
                } else if (response.status == "error") {
                    errorMessage('Error', response.message);
                } else {
                    // alertSuccess('Berhasil', 'Hore!');
                    setStatistikMutasiBank(response.data.mutasi_bank_bulan_lalu.data)
                    setStatistikMutasiKas(response.data.mutasi_kas_bulan_lalu.data)
                    setStatistikBank(response.data.bank_tahun_lalu.data)
                    setStatistikKas(response.data.kas_tahun_lalu.data)
                    setStatistikKategoriBankDebetBulanLalu(response.data.bank_debit_bulan_lalu_kategori.data)
                    setStatistikKategoriBankKreditBulanLalu(response.data.bank_kredit_bulan_lalu_kategori.data)
                    setStatistikKategoriKasDebetBulanLalu(response.data.kas_debit_bulan_lalu_kategori.data)
                    setStatistikKategoriKasKreditBulanLalu(response.data.kas_kredit_bulan_lalu_kategori.data)
                    setStatistikKategoriBankDebetTahunLalu(response.data.bank_debit_tahun_lalu_kategori.data)
                    setStatistikKategoriBankKreditTahunLalu(response.data.bank_kredit_tahun_lalu_kategori.data)
                    setStatistikKategoriKasDebetTahunLalu(response.data.kas_debit_tahun_lalu_kategori.data)
                    setStatistikKategoriKasKreditTahunLalu(response.data.kas_kredit_tahun_lalu_kategori.data)
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
                errorMessage(textStatus, errorThrown);
                // alert("Status: " + textStatus); alert("Error: " + errorThrown);
            }
        });
    }

    $(function(){
        // Get Statistik
        getStatistik();
    })
</script>
@endsection