@extends('layouts.memberLayout.memberContent')
@section('title', 'Dashboard')

@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="small-box bg-blue">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($saldo_kas) }}</h3>

					<p>Sisa Saldo Kas</p>
				</div>
				<div class="icon">
					<i class="ion ion-cash"></i>
				</div>
				<a href="{{url('/dashboard/detail-saldo/kas')}}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="small-box bg-blue">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($saldo_bank) }}</h3>

					<p>Sisa Saldo Bank</p>
				</div>
				<div class="icon">
					<i class="ion ion-home"></i>
				</div>
				<a href="{{url('/dashboard/detail-saldo/bank')}}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($debit_kas_last_month) }}</h3>

					<p>Kas Masuk Bulan Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-down-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($credit_kas_last_month) }}</h3>

					<p>Kas keluar Bulan Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-up-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($debit_kas_last_year) }}</h3>

					<p>Kas Masuk Tahun Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-down-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($credit_kas_last_year) }}</h3>

					<p>Kas Keluar Tahun Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-up-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($debit_bank_last_month) }}</h3>

					<p>Bank Masuk Bulan Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-down-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($credit_bank_last_month) }}</h3>

					<p>Bank Keluar Bulan Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-up-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($debit_bank_last_year) }}</h3>

					<p>Bank Masuk Tahun Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-down-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ Helper::displayRupiah($credit_bank_last_year) }}</h3>

					<p>Bank Keluar Tahun Lalu</p>
				</div>
				<div class="icon">
					<i class="ion ion-arrow-up-a"></i>
				</div>
				<a href="{{ url('/laporan/chart') }}" class="small-box-footer">Info lebih lanjut <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>

	<div class="row">
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-b-l">Laporan Kas Masuk Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="line-chart-debit-kas" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-b-l">Laporan Kas Keluar Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="line-chart-kredit-kas" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-b-m-b-l">Laporan Bank Masuk Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="line-chart-debit-bank" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-b-m-b-l">Laporan Bank Keluar Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="line-chart-kredit-bank" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Kas Masuk (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="box-chart-responsive">
						<canvas class="chart" id="dnt-ch-k-m-b-l" style="height: 300px;"></canvas>
					</div>
					
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_km_b_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Kas Keluar (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-k-k-b-l" style="height: 300px;"></canvas>
					<div class="clearfix"></div>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_kk_b_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Bank Masuk (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-b-m-b-l" style="height: 300px;"></canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_bm_b_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Bank Keluar (Kategori) Bulan Lalu ({{ Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month') }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-b-k-b-l" style="height: 300px;"></canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_bk_b_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Kas Masuk (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-k-m-t-l" style="height: 300px;"></canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_km_t_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Kas Keluar (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-k-k-t-l" style="height: 300px;"></canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_kk_t_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Bank Masuk (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-b-m-t-l" style="height: 300px;">Test</canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_bm_t_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title l-k-m-ktg-b-l">Lap. Bank Keluar (Kategori) Tahun Lalu ({{ date('Y', strtotime('-1 year')) }})</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<canvas class="chart" id="dnt-ch-b-k-t-l" style="height: 300px;"></canvas>
					<div class="col-lg-12 table-responsive" style="margin-top: 10px;">
						<table class="table table-bordered" id="tb_bk_t_l">
							<tr>
			                  <th>Kategori</th>
			                  <th>Jumlah</th>
			                  <th>Persen(%)</th>
			                </tr>
						</table>
					</div>
				</div>
	      	</div>
        </section>
    </div>

	<div class="row">
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title analisa">Laporan Keuangan Dari Akun Kas Tahun {{ date('Y', strtotime('-1 year')) }}</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="bar-chart-kas" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>  
    </div>

    <div class="row">
        <section class="col-lg-12 col-md-12 col-sm-12 col-xs-12 connectedSortable">
	      	<div class="box box-primary">
		        <div class="box-header with-border">
		          <h3 class="box-title analisa">Laporan Keuangan Dari Akun Bank Tahun {{ date('Y', strtotime('-1 year')) }}</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
				<div class="box-body chart-responsive">
					<div class="chart" id="bar-chart-bank" style="height: 300px;"></div>
				</div>
	      	</div>
        </section>  
    </div>
</section>

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>

<script type="text/javascript">
	$(function(){
		// Grafik Kas
	    $.ajax({
	    	url: baseUrl+'/dashboard/grafik-kas',
	    	dataType: 'json',
	    }).done(function (results){
			if (results.length == 0) {
				$("#bar-chart-kas").html('<h1 class="text-center">Tidak ada transaksi</h1>');
			} else {
				var bar = new Morris.Bar({
					element: 'bar-chart-kas',
					resize: true,
					data: results,
					barColors: ['#00a65a', '#f56954'],
					xkey: 'month',
					ykeys: ['debit', 'kredit'],
					labels: ['DEBIT', 'KREDIT'],
					hideHover: 'auto',
					parseTime: false
				});
			}
	    });

	    // Grafik Bank
	    $.ajax({
	    	url: baseUrl+'/dashboard/grafik-bank',
	    	dataType: 'json',
	    }).done(function (results){
			if (results.length == 0) {
				$("#bar-chart-bank").html('<h1 class="text-center">Tidak ada transaksi</h1>');
			} else {
				var bar = new Morris.Bar({
					element: 'bar-chart-bank',
					resize: true,
					data: results,
					barColors: ['#00a65a', '#f56954'],
					xkey: 'month',
					ykeys: ['debit', 'kredit'],
					labels: ['DEBIT', 'KREDIT'],
					hideHover: 'auto',
					parseTime: false
				});
			}
	    });

	    // Grafik debit kas
	    $.ajax({
            url: baseUrl+'/dashboard/laporan-bulan-lalu-debit-kas',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
               $("#line-chart-debit-kas").html('<h2 class="text-center">Tidak ada transaksi</h2>');
            } else {
                var linedebit = new Morris.Line({
                    element: 'line-chart-debit-kas',
                    resize: true,
                    data: results,
                    xkey: 'date',
                    ykeys: ['debit'],
                    labels: ['Debit'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto',
                    parseTime: false
                });
            }
        });

        // Grafik debit bank
	    $.ajax({
            url: baseUrl+'/dashboard/laporan-bulan-lalu-debit-bank',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
               $("#line-chart-debit-bank").html('<h2 class="text-center">Tidak ada transaksi</h2>');
            } else {
                var linedebit = new Morris.Line({
                    element: 'line-chart-debit-bank',
                    resize: true,
                    data: results,
                    xkey: 'date',
                    ykeys: ['debit'],
                    labels: ['Debit'],
                    lineColors: ['#3c8dbc'],
                    hideHover: 'auto',
                    parseTime: false
                });
            }
        });

        // Grafik kredit kas
        $.ajax({
            url: baseUrl+'/dashboard/laporan-bulan-lalu-kredit-kas',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
                $("#line-chart-kredit-kas").html('<h2 class="text-center">Tidak ada transaksi</h2>');
            } else {
                var linekredit = new Morris.Line({
                    element: 'line-chart-kredit-kas',
                    resize: true,
                    data: results,
                    xkey: 'date',
                    ykeys: ['kredit'],
                    labels: ['Kredit'],
                    lineColors: ['#d9534f'],
                    hideHover: 'auto',
                    parseTime: false
                });
            }
        });

        // Grafik kredit bank
        $.ajax({
            url: baseUrl+'/dashboard/laporan-bulan-lalu-kredit-bank',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
                $("#line-chart-kredit-bank").html('<h2 class="text-center">Tidak ada transaksi</h2>');
            } else {
                var linekredit = new Morris.Line({
                    element: 'line-chart-kredit-bank',
                    resize: true,
                    data: results,
                    xkey: 'date',
                    ykeys: ['kredit'],
                    labels: ['Kredit'],
                    lineColors: ['#d9534f'],
                    hideHover: 'auto',
                    parseTime: false
                });
            }
        });

	    // Pie
	    var data_kd = [], data_kk = [], data_bd = [], data_bk = [],
	    	data_t_kd = [], data_t_kk = [], data_t_bd = [], data_t_bk = [], 
	    	label_kd = [], label_kk = [], label_bd = [], label_bk = [],
	    	label_t_kd = [], label_t_kk = [], label_t_bd = [], label_t_bk = [],
	    	warna_kd = [], warna_kk = [], warna_bd = [], warna_bk = [],
	    	warna_t_kd = [], warna_t_kk = [], warna_t_bd = [], warna_t_bk = [];

	    $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-kas-debit-bulan-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-k-m-b-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
                	data_kd.push(element.persen);
                	label_kd.push(element.kategori);
                	warna_kd.push(element.warna);
                	row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                	total += parseFloat(element.jumlah);
                	tot_percent += parseFloat(element.persen);
                })

                row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-kas-kredit-bulan-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-k-k-b-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
                	data_kk.push(element.persen);
                	label_kk.push(element.kategori);
                	warna_kk.push(element.warna);
                	row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                	total += parseFloat(element.jumlah);
                	tot_percent += parseFloat(element.persen);
                })

                row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-bank-debit-bulan-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-b-m-b-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-bank-kredit-bulan-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-b-k-b-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-kas-debit-tahun-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-k-m-t-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-kas-kredit-tahun-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-k-k-t-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-bank-debit-tahun-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-b-m-t-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });

        $.ajax({
            url: baseUrl+'/dashboard/grafik-kategori-bank-kredit-tahun-lalu',
            dataType: 'json',
        }).done(function (results){
            if (results.length == 0) {
            	var ctx = document.getElementById('dnt-ch-b-k-t-l').getContext('2d');
            	ctx.font = "30px Arial";
            	ctx.fillText("Tidak ada transaksi", 10, 50);
            } else {
            	var row = "";
            	var total = 0;
            	var tot_percent = 0;
                results.forEach(function(element){
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
        });
	})	
</script>
@endsection