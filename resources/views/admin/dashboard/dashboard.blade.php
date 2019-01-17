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

					<p>Sisa Saldo</p>
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

					<p>Total Kredit Bulan Lalu</p>
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

					<p>Total Kredit</p>
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
        <!-- Left col -->
        <section class="col-lg-12 connectedSortable">
          <!-- BAR CHART -->
	      	<div class="box box-success">
		        <div class="box-header with-border">
		          <h3 class="box-title analisa">Analisa Keuangan Tahun {{ date('Y', strtotime('-1 year')) }}</h3>

		          <div class="box-tools pull-right">
		            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
		            </button>
		          </div>
		        </div>
		        <div class="box-body">
		        	<div class="chart">
		            	<canvas id="barChart" style="height:230px"></canvas>
		          	</div>
		        </div>
		        <!-- /.box-body -->
	      	</div>
          <!-- /.box -->
        </section>  
    </div>

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
						<!-- <div class="btn-group">
							<button type="button" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown">
								<i class="fa fa-bars"></i>
							</button>
							<ul class="dropdown-menu pull-right" role="menu">
								<li><a href="#">Add new event</a></li>
								<li><a href="#">Clear events</a></li>
								<li class="divider"></li>
								<li><a href="#">View calendar</a></li>
							</ul>
						</div> -->
						<button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
						</button>
						<!-- <button type="button" class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i>
						</button> -->
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
</script>

<script type="text/javascript">
	$(function(){
		var baseUrl = '{{ url('/') }}';
		var deposito, kredit;
		var labels, data;
		var label = Array();
		$('#table_deposito').dataTable();
		$('#table_credit').dataTable();
		$('#calendar').datepicker({format: 'yyyy-mm-dd',todayHighlight: true});
		$('#calendar').datepicker('update', new Date());
		var tgl = $('#calendar').datepicker('getFormattedDate');
		$.getJSON(baseUrl+'/dashboard/riwayat/'+tgl, function(resp){
	        // console.log(resp);
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

	    var jsonData = $.ajax({
	    	url: baseUrl+'/dashboard/grafik',
	    	dataType: 'json',
	    }).done(function (results){
	    	var labels = [], dataDeposito = [], dataKredit = [];
	    	// console.log(results[0]);
	    	results[0].forEach(function(deposito){
	    		// console.log(deposito.bulan);
	    		if (cekIsiArray(label, deposito.bulan) == 'lanjut') {
	    			label.push(deposito.bulan);
	    		}
	    		dataDeposito.push(deposito.jumlah_deposito);
	    	})

	    	results[1].forEach(function(kredit){
	    		// console.log(deposito.bulan);
	    		if (cekIsiArray(label, kredit.bulan) == 'lanjut') {
	    			label.push(kredit.bulan);
	    		}
	    		dataKredit.push(kredit.jumlah_kredit);
	    	})

	    	var areaChartData = {
		      labels  : label,
		      datasets: [
		        {
		          label               : 'Deposito',
		          fillColor           : 'rgba(210, 214, 222, 1)',
		          strokeColor         : 'rgba(210, 214, 222, 1)',
		          pointColor          : 'rgba(210, 214, 222, 1)',
		          pointStrokeColor    : '#c1c7d1',
		          pointHighlightFill  : '#fff',
		          pointHighlightStroke: 'rgba(220,220,220,1)',
		          data                : dataDeposito
		        },
		        {
		          label               : 'Kredit',
		          fillColor           : 'rgba(60,141,188,0.9)',
		          strokeColor         : 'rgba(60,141,188,0.8)',
		          pointColor          : '#3b8bba',
		          pointStrokeColor    : 'rgba(60,141,188,1)',
		          pointHighlightFill  : '#fff',
		          pointHighlightStroke: 'rgba(60,141,188,1)',
		          data                : dataKredit
		        }
		      ]
		    }

		    var areaChartOptions = {
		      //Boolean - If we should show the scale at all
		      showScale               : true,
		      //Boolean - Whether grid lines are shown across the chart
		      scaleShowGridLines      : false,
		      //String - Colour of the grid lines
		      scaleGridLineColor      : 'rgba(0,0,0,.05)',
		      //Number - Width of the grid lines
		      scaleGridLineWidth      : 1,
		      //Boolean - Whether to show horizontal lines (except X axis)
		      scaleShowHorizontalLines: true,
		      //Boolean - Whether to show vertical lines (except Y axis)
		      scaleShowVerticalLines  : true,
		      //Boolean - Whether the line is curved between points
		      bezierCurve             : true,
		      //Number - Tension of the bezier curve between points
		      bezierCurveTension      : 0.3,
		      //Boolean - Whether to show a dot for each point
		      pointDot                : false,
		      //Number - Radius of each point dot in pixels
		      pointDotRadius          : 4,
		      //Number - Pixel width of point dot stroke
		      pointDotStrokeWidth     : 1,
		      //Number - amount extra to add to the radius to cater for hit detection outside the drawn point
		      pointHitDetectionRadius : 20,
		      //Boolean - Whether to show a stroke for datasets
		      datasetStroke           : true,
		      //Number - Pixel width of dataset stroke
		      datasetStrokeWidth      : 2,
		      //Boolean - Whether to fill the dataset with a color
		      datasetFill             : true,
		      //String - A legend template
		      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].lineColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
		      //Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
		      maintainAspectRatio     : true,
		      //Boolean - whether to make the chart responsive to window resizing
		      responsive              : true
		    }


		    //-------------
		    //- BAR CHART -
		    //-------------
		    var barChartCanvas                   = $('#barChart').get(0).getContext('2d')
		    var barChart                         = new Chart(barChartCanvas)
		    var barChartData                     = areaChartData
		    barChartData.datasets[0].fillColor   = '#00a65a'
		    barChartData.datasets[0].strokeColor = '#00a65a'
		    barChartData.datasets[0].pointColor  = '#00a65a'
		    barChartData.datasets[1].fillColor   = '#DD4B39'
		    barChartData.datasets[1].strokeColor = '#DD4B39'
		    barChartData.datasets[1].pointColor  = '#DD4B39'
		    var barChartOptions                  = {
		      //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
		      scaleBeginAtZero        : true,
		      //Boolean - Whether grid lines are shown across the chart
		      scaleShowGridLines      : true,
		      //String - Colour of the grid lines
		      scaleGridLineColor      : 'rgba(0,0,0,.05)',
		      //Number - Width of the grid lines
		      scaleGridLineWidth      : 1,
		      //Boolean - Whether to show horizontal lines (except X axis)
		      scaleShowHorizontalLines: true,
		      //Boolean - Whether to show vertical lines (except Y axis)
		      scaleShowVerticalLines  : true,
		      //Boolean - If there is a stroke on each bar
		      barShowStroke           : true,
		      //Number - Pixel width of the bar stroke
		      barStrokeWidth          : 2,
		      //Number - Spacing between each of the X value sets
		      barValueSpacing         : 5,
		      //Number - Spacing between data sets within X values
		      barDatasetSpacing       : 1,
		      //String - A legend template
		      legendTemplate          : '<ul class="<%=name.toLowerCase()%>-legend"><% for (var i=0; i<datasets.length; i++){%><li><span style="background-color:<%=datasets[i].fillColor%>"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>',
		      //Boolean - whether to make the chart responsive
		      responsive              : true,
		      maintainAspectRatio     : true
		    }

		    barChartOptions.datasetFill = false
		    barChart.Bar(barChartData, barChartOptions)
	    })


	    

	    function cekIsiArray(array, data){
	        var hitung = array.length;
	        for (var i = 0; i <= hitung; i++) {
	            if (array[i] == data) {
	               return 'sudah';
	            }
	        }
	        return 'lanjut';
	    }
	})
</script>