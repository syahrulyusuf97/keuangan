@extends('layouts.adminLayout.adminContent')
@section('content')

<?php 
	function rupiah($angka){
		$hasil_rupiah = "Rp" . number_format($angka,2,',','.');
		return $hasil_rupiah;
	}
?>

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

<!-- Main content -->
<section class="content">
	<!-- Small boxes (Stat box) -->
	<div class="row">
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-aqua">
				<div class="inner">
					<h3>{{ rupiah($saldo) }}</h3>

					<p>Saldo Anda</p>
				</div>
				<div class="icon">
					<i class="ion ion-bag"></i>
				</div>
				<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{ rupiah($deposito) }}</h3>

					<p>Total Deposito</p>
				</div>
				<div class="icon">
					<i class="ion ion-stats-bars"></i>
				</div>
				<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{ rupiah($credit_last_month) }}</h3>

					<p>Total Credit Last Month</p>
				</div>
				<div class="icon">
					<i class="ion ion-person-add"></i>
				</div>
				<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
		<div class="col-lg-3 col-xs-6">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{ rupiah($credit) }}</h3>

					<p>Total Credit</p>
				</div>
				<div class="icon">
					<i class="ion ion-pie-graph"></i>
				</div>
				<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
		<!-- ./col -->
	</div>
	<!-- /.row -->
	<!-- Main row -->
	<div class="row">
		<!-- /.Left col -->
		<!-- right col (We are only adding the ID to make the widgets sortable)-->
		<section class="col-lg-12 connectedSortable">
			<!-- Calendar -->
			<div class="box box-solid bg-green-gradient">
				<div class="box-header">
					<i class="fa fa-calendar"></i>
					<h3 class="box-title">Calendar</h3>
					<!-- tools box -->
					<div class="pull-right box-tools">
						<!-- button with a dropdown -->
						<div class="btn-group">
							<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bars"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li><a href="#">Add new event</a></li>
								<li><a href="#">Clear events</a></li>
								<li class="divider"></li>
								<li><a href="#">View calendar</a></li>
							</ul>
						</div>
						<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
						</button>
					</div>
						<!-- /. tools -->
				</div>
				<!-- /.box-header -->
				<div class="box-body no-padding">
					<!--The calendar -->
					<div id="calendar" style="width: 100%"></div>
				</div>
				<!-- /.box-body -->
				<div class="box-footer text-black">
					<div class="row">
						<div class="col-sm-6">
							<!-- Progress bars -->
							<h4 class="text-center">Riwayat deposito hari ini</h4>
							<hr>
							<div class="table-responsive">
								<table id="table_deposito" class="table table-bordered">
									<thead>
										<tr>
											<td>Tanggal</td>
											<td>Keterangan</td>
											<td>Jumlah</td>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<!-- /.col -->
						<div class="col-sm-6">
							<h4 class="text-center">Riwayat credit hari ini</h4>
							<hr>
							<div class="table-responsive">
								<table id="table_credit" class="table table-bordered">
									<thead>
										<tr>
											<td>Tanggal</td>
											<td>Keperluan</td>
											<td>Jumlah</td>
										</tr>
									</thead>
									<tbody></tbody>
								</table>
							</div>
						</div>
						<!-- /.col -->
					</div>
					<!-- /.row -->
				</div>
			</div>
				<!-- /.box -->
		</section>
			<!-- right col -->
	</div>
		<!-- /.row (main row) -->
</section>
<!-- /.content -->
@endsection
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script type="text/javascript">
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

	function getFormattedDate(data) {
		var date = new Date(data);
	  	var year = date.getFullYear();

	  	var month = (1 + date.getMonth()).toString();
	  	month = month.length > 1 ? month : '0' + month;

	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	  
	  	return year + '-' + month + '-' + day;
	}

	function getFormattedMonth(data) {
		var date = new Date(Date.parse(data));
	  	var year = date.getFullYear();

	  	var month = (1 + date.getMonth()).toString();
	  	month = month.length > 1 ? month : '0' + month;
	  
	  	return year + '-' + month;
	}

	$(function(){
		var baseUrl = '{{ url('/') }}';
		$('#table_deposito').dataTable();
		$('#table_credit').dataTable();
		$('#calendar').datepicker({format: 'yyyy-mm-dd',todayHighlight: true});
		$('#calendar').datepicker('update', new Date());
		var tgl = $('#calendar').datepicker('getFormattedDate');
		$.getJSON(baseUrl+'/dashboard/riwayat/'+tgl, function(resp){
	        console.log(resp);
	        var array_data_credit = [];
         	var temp_array_credit = [];
         	var array_data_deposito = [];
         	var temp_array_deposito = [];
         	if (resp.result_credit != "") {
         		$.each(resp.result_credit, function(key, val){
		        	// console.log(val.tanggal);
		        	temp_array_credit = [];
                   	temp_array_credit = [
                                    val.tanggal,
                                    val.keperluan,
                                    'Rp'+number_format(val.jumlah, '2', ',', '.')
                            	];
                    array_data_credit[array_data_credit.length] = temp_array_credit;
		        })
		        $('#table_credit').dataTable().fnAddData(array_data_credit);
              	$('#table_credit').dataTable().fnDraw();
         	}

         	if (resp.result_deposito != "") {
         		$.each(resp.result_deposito, function(key, val){
		        	// console.log(val.tanggal);
		        	temp_array_deposito = [];
                   	temp_array_deposito = [
                                    val.tanggal,
                                    val.keterangan,
                                    'Rp'+number_format(val.jumlah, '2', ',', '.')
                            	];
                    array_data_deposito[array_data_deposito.length] = temp_array_deposito;
		        })
		        $('#table_deposito').dataTable().fnAddData(array_data_deposito);
              	$('#table_deposito').dataTable().fnDraw();
         	}
	    });
	})
</script>