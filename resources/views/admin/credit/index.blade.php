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
		<small>Credit</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Credit</a></li>
		<li class="active">Tambah Credit</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-xs-12">
			@if(Session::has('flash_message_error'))
			<div class="alert alert-error alert-block">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>{!! session('flash_message_error') !!}</strong>
			</div>
			@endif
			@if(Session::has('flash_message_success'))
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>{!! session('flash_message_success') !!}</strong>
			</div>
			@endif 
			<div class="col-md-12 box box-danger">
				<div class="col-md-6" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Form Tambah Credit</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="{{ url('/credit') }}">{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-sm-2 control-label">Keperluan</label>

									<div class="col-sm-10">
										<input type="text" class="form-control" id="kep" name="kep" placeholder="Keperluan Credit...">
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-sm-2 control-label">Jumlah</label>

									<div class="col-sm-10">
										<input type="text" class="form-control" id="jumlah" name="jumlah" onkeypress="return isNumberKey(event)" placeholder="Jumlah Credit...">
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-sm-2 control-label">Tanggal</label>

									<div class="col-sm-10">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right" id="datepicker" name="tanggal">
						                </div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right">Submit</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
				<div class="col-md-6" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Akumulasi Total Credit</h3>
							<a style="cursor: pointer;" class="pull-right" id="rincian" data-toggle="modal" data-target="#modal_rincian">Rincian</a>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal">
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-sm-2 control-label">Tampilkan</label>

									<div class="col-sm-10">
										<select class="form-control" id="option_view">
											<option value="Keseluruhan" selected>Keseluruhan</option>
											<option value="Pertanggal">Pertanggal</option>
											<option value="Perbulan">Perbulan</option>
											<option value="Pertahun">Pertahun</option>
										</select>
									</div>
								</div>
								<div class="form-group" id="credit_pertanggal">
									<label for="title" class="col-sm-2 control-label">Tanggal</label>

									<div class="col-sm-10">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right" id="pertanggal">
						                </div>
									</div>
								</div>
								<div class="form-group" id="credit_perbulan">
									<label for="title" class="col-sm-2 control-label">Bulan</label>

									<div class="col-sm-10">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right" id="perbulan">
						                </div>
									</div>
								</div>
								<div class="form-group" id="credit_pertahun">
									<label for="title" class="col-sm-2 control-label">Tahun</label>

									<div class="col-sm-10">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right" id="pertahun">
						                </div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<h3 class="pull-left">Total Credit</h3>
								<h3 class="pull-right" id="txt_total_credit">{{ rupiah($total_credit) }}</h3>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<div class="col-md-12 box box-danger">
				<div class="box-body">
					<table id="example1" class="table table-bordered table-striped table-responsive">
						<thead>
							<tr>
								<th>Nomor</th>
								<th>Keperluan</th>
								<th>Jumlah</th>
								<th>Tanggal</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@foreach($data_credit as $key => $credit)
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ $credit->keperluan }}</td>
								<td>{{ rupiah($credit->jumlah) }}</td>
								<td>{{ $credit->tanggal }}</td>
								<td><a href="{{ url('/credit/delete/'.$credit->credit_id) }}" onclick="return confirm('Apakah anda yakin akan menghapus data ini?\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(')" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>&nbsp;<a href="#" class="btn btn-sm btn-info edit" data-id="{{ $credit->credit_id }}"><i class="fa fa-pencil"></i></a></td>
							</tr>
							@endforeach
						</tbody>
						<tfoot>
							<tr>
								<th>Nomor</th>
								<th>Keperluan</th>
								<th>Jumlah</th>
								<th>Aksi</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="modal_edit">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Edit Credit</h4>
			</div>
			<form class="form-horizontal" method="post" action="{{ url('/credit/update') }}">
				{{ csrf_field() }}
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<fieldset>
						<section>
							<div class="row">
								<label class="col-sm-2" style="padding-top: 5px;">Keperluan</label>
								<div class="col-sm-10">
									<label class="input col-sm-12">
										<input type="text" class="form-control" name="kep_edit" id="kep_edit" />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-sm-2" style="padding-top: 5px;">Jumlah</label>
								<div class="col-sm-10">
									<label class="input col-sm-12">
										<input type="text" class="form-control jml" name="jumlah_edit" onkeypress="return isNumberKey(event)" id="jumlah_edit" />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-sm-2" style="padding-top: 5px;">Tanggal</label>
								<div class="col-sm-10">
									<label class="input col-sm-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right" id="datepicker_edit" name="tanggal_edit">
						                </div>
									</label>
								</div>
							</div>
						</section>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary">Simpan perubahan</button>
				</div>
			</form>
		</div>
			<!-- /.modal-content -->
	</div>
		<!-- /.modal-dialog -->
</div>

<div class="modal modal-danger fade" id="modal_rincian">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Rincian Credit</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
        	<table class="table table-bordered" id="table_rincian">
        		<thead>
        			<tr>
            			<th class="text-center">Tanggal</th>
            			<th class="text-center">Keperluan</th>
            			<th class="text-center">Jumlah</th>
            		</tr>
        		</thead>
        		<tbody>
        		</tbody>
        	</table>
        </div>
      </div>
      <div class="modal-footer">
        <h3 class="pull-left">Total Credit</h3>
		<h3 class="pull-right" id="rincian_total_credit"></h3>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<script src="{{ asset('public/js/adminLTE/main.js') }}"></script>
<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>

<script>
	function isNumberKey(evt) {
	    var charCode = (evt.which) ? evt.which : evt.keyCode;
	    if (charCode > 31 && (charCode < 48 || charCode > 57))
	        return false;
	    return true;
	}

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

	function formatDate(data) {
		var date = new Date(data);
	  	var monthNames = [
	    	"January", "February", "March",
	    	"April", "May", "June", "July",
	    	"August", "September", "October",
	    	"November", "December"
	  	];

	  	var day = date.getDate().toString();
	  	day = day.length > 1 ? day : '0' + day;
	  	var monthIndex = date.getMonth();
	  	var year = date.getFullYear();

	  	return day + ' ' + monthNames[monthIndex] + ' ' + year;
	}

	$(function () {
		var baseUrl = '{{ url('/') }}';
		$('#kep').val('');
		$('#jumlah').val('');
		$('#datepicker').val('');
		$('#option_view').val('Keseluruhan');
		$('#example1').dataTable({
			"orderable": false,
			"order": [],
		})
		//Date picker
	    $('#datepicker').datepicker({
	      autoclose: true,
	      format: 'dd MM yyyy',
	      todayHighlight: true
	    })

	    $('#datepicker_edit').datepicker({
	      autoclose: true,
	      format: 'dd MM yyyy',
	      todayHighlight: true
	    })

	    $('#pertanggal').datepicker({
	      autoclose: true,
	      format: 'dd MM yyyy'
	    })

	    $('#perbulan').datepicker({
	      autoclose: true,
	      format: 'MM yyyy',
	      viewMode: 'months',
	      minViewMode: 'months'
	    })

	    $('#pertahun').datepicker({
	      autoclose: true,
	      format: 'yyyy',
	      viewMode: 'years',
	      minViewMode: 'years'
	    })

	    $('#credit_pertanggal').hide();
	    $('#credit_perbulan').hide();
	    $('#credit_pertahun').hide();

	    if ($('#option_view').val() == "Keseluruhan") {
	    	$('#pertanggal').val('');
	    	$('#perbulan').val('');
	    	$('#pertahun').val('');
	    	var nilai = $('#option_view').val();
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	$.getJSON(baseUrl+'/credit/akumulasi/'+nilai+'_null', function(resp){
		        // console.log(resp.total);
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	// console.log(val.tanggal);
			        	temp_array = [];
                       	temp_array = [
                                        val.tanggal,
                                        val.keperluan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		        
		    });
	    }

	    $('#option_view').on('change', function(e){
	    	var nilai = $('#option_view').val();
			if ($('#option_view').val() == "Keseluruhan") {
				$('#table_rincian').dataTable().fnClearTable();
				$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
				$.getJSON(baseUrl+'/credit/akumulasi/'+nilai+'_null', function(resp){
			        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
			        // console.log(resp.total);
			        var array_data = [];
                 	var temp_array = [];
                 	if (resp.result != "") {
                 		$.each(resp.result, function(key, val){
				        	// console.log(val.tanggal);
				        	temp_array = [];
                           	temp_array = [
	                                        val.tanggal,
	                                        val.keperluan,
	                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                    	];
                            array_data[array_data.length] = temp_array;
				        })
				        $('#table_rincian').dataTable().fnAddData(array_data);
                      	$('#table_rincian').dataTable().fnDraw();
                      	$('#rincian_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
                 	}
			        
			    });
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').hide();
			    $('#credit_perbulan').hide();
			    $('#credit_pertahun').hide();
		    } else if ($('#option_view').val() == "Pertanggal") {
		    	$('#txt_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').show();
			    $('#credit_perbulan').hide();
			    $('#credit_pertahun').hide();
		    } else if ($('#option_view').val() == "Perbulan") {
		    	$('#txt_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').hide();
			    $('#credit_perbulan').show();
			    $('#credit_pertahun').hide();
		    } else if ($('#option_view').val() == "Pertahun") {
		    	$('#txt_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').hide();
			    $('#credit_perbulan').hide();
			    $('#credit_pertahun').show();
		    }
	    })

	    $('#pertanggal').on('change', function(e){
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = getFormattedDate($('#pertanggal').val());
	    	$.getJSON(baseUrl+'/credit/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        // console.log(resp.total);
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	// console.log(val);
			        	temp_array = [];
                       	temp_array = [
                                        val.tanggal,
                                        val.keperluan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		        
		    });
	    })

	    $('#perbulan').on('change', function(e){
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = $('#perbulan').val();
	    	$.getJSON(baseUrl+'/credit/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        // console.log(resp);
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	// console.log(val);
			        	temp_array = [];
                       	temp_array = [
                                        val.tanggal,
                                        val.keperluan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		        
		    });
	    })

	    $('#pertahun').on('change', function(e){
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tahun = $('#pertahun').val();
	    	$.getJSON(baseUrl+'/credit/akumulasi/'+nilai+'_'+tahun, function(resp){
		        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        // console.log(resp);
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	// console.log(val);
			        	temp_array = [];
                       	temp_array = [
                                        val.tanggal,
                                        val.keperluan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		    });
	    })

	    $('.edit').on('click', function(){
	    	$('#kep_edit').val('');
	        $('#jumlah_edit').val('');
	        $('#datepicker_edit').val('');
	    	var id = $(this).data('id');
	    	$.getJSON(baseUrl+'/credit/get-current/'+id, function(resp){
		        // console.log(resp.jumlah);
		        $('#id').val(resp.credit_id);
		        $('#kep_edit').val(resp.keperluan);
		        $('#jumlah_edit').val(rupiah(resp.jumlah, 'Rp'));
		        $('#datepicker_edit').val(formatDate(resp.tanggal));
		        $('#modal_edit').modal('show');
		    });
	    	
	    })

	    function rupiah(angka, prefix)
		{
			var number_string = angka.toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);

			if (ribuan) {
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
		}
	})

	var i_jumlah = document.getElementById('jumlah');
	var i_jumlah_edit = document.getElementById('jumlah_edit');

	i_jumlah.addEventListener('keyup', function(e)
	{
		i_jumlah.value = formatRupiah(this.value, 'Rp');
	});
	i_jumlah_edit.addEventListener('keyup', function(e)
	{
		i_jumlah_edit.value = formatRupiah(this.value, 'Rp');
	});
	// window.addEventListener('load', function(e){
	// 	i_jumlah_edit.value 		= formatRupiah(i_jumlah_edit.value, 'Rp');
	// });

	function formatRupiah(angka, prefix)
	{
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split	= number_string.split(','),
			sisa 	= split[0].length % 3,
			rupiah 	= split[0].substr(0, sisa),
			ribuan 	= split[0].substr(sisa).match(/\d{3}/gi);
			
		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}
		
		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp' + rupiah : '');
	}
</script>
@endsection