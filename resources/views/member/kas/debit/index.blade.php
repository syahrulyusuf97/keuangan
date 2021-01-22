@extends('layouts.memberLayout.memberContent')
@section('title', 'Kas Debet')
@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Kas</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Kas </a></li>
		<li class="active">Kas Debet</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="col-md-6" style="margin-top: 30px;">
					<div class="box">

						<div class="box-header with-border">
							<h3 class="box-title">Form Tambah Kas Debet</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<!-- <form class="form-horizontal" method="post" action="{{ url('/kas/masuk') }}" autocomplete="off" onsubmit="{$('#btn_submit').attr('disabled', true)}"> -->
						<form class="form-horizontal" id="form_kas_debet" autocomplete="off">
							{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Kategori</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="kategori" id="kategori" required>
											<option value="">Pilih Kategori</option>
											@foreach($kategori as $key => $value)
											<option value="{{ Crypt::encrypt($value->id) }}">{{ $value->nama }}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Keterangan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<!-- <input type="text" class="form-control" id="ket" name="ket" placeholder="Keterangan kas masuk..." required> -->
										<textarea class="form-control" id="ket" name="ket" placeholder="Keterangan kas debet..." required></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Jumlah</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" id="jumlah" name="jumlah" onkeypress="return isNumberKey(event)" placeholder="Jumlah deposit..." required>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tanggal</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right input-datepicker" id="datepicker" name="tanggal" autocomplete="off" required>
						                </div>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Ke Akun</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="keakun" id="keakun" required>
											<option value="">Pilih Akun</option>
											@foreach($akun as $key => $value)
											<option value="{{ Crypt::encrypt($value->id).'_'.$value->jenis_akun.'_'.'('.$value->kode_akun.') '.$value->nama_akun }}">{{ "(".$value->kode_akun.") ". $value->nama_akun }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right" id="btn_submit"><i class="fa fa-save"></i> Simpan</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
				<div class="col-md-6" style="margin-top: 30px;">
					<div class="box">
						<div class="box-header with-border">
							<h3 class="box-title">Akumulasi Total Kas Debet</h3>
							<a style="cursor: pointer;" class="pull-right" id="rincian" data-toggle="modal" data-target="#modal_rincian">Rincian</a>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal">
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tampilkan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control" id="option_view">
											<option value="BulanLalu" selected>Bulan Lalu</option>
											<option value="TahunLalu">Tahun Lalu</option>
											<option value="Pertanggal">Pertanggal</option>
											<option value="Perbulan">Perbulan</option>
											<option value="Pertahun">Pertahun</option>
										</select>
									</div>
								</div>
								<div class="form-group" id="deposito_pertanggal">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tanggal</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right pertanggal" id="pertanggal">
						                </div>
									</div>
								</div>
								<div class="form-group" id="deposito_perbulan">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Bulan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right perbulan" id="perbulan">
						                </div>
									</div>
								</div>
								<div class="form-group" id="deposito_pertahun">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Tahun</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right pertahun" id="pertahun">
						                </div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<h3 class="pull-left">Total Kas Debet</h3>
								<h3 class="pull-right" id="txt_total_deposito"></h3>
							</div>
							<!-- /.box-footer -->
						</form>
						{{-- <div class="box-footer">
							<h3 class="pull-left">Saldo Kas</h3>
							<h3 class="pull-right" id="txt_saldo_kas">{{ Helper::displayRupiah($saldo_kas) }}</h3>
						</div> --}}
					</div> 
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Kas Debet</h3>
					<div class="pull-right"><button type="button" onclick="refreshTable()" class="btn btn-default"><i class="fa fa-refresh"></i></button></div>
				</div>
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Jumlah</th>
								<th>Ke Akun</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Tanggal</th>
								<th>Keterangan</th>
								<th>Jumlah</th>
								<th>Ke Akun</th>
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
				<h4 class="modal-title">Edit Kas Debet</h4>
			</div>
			<!-- <form class="form-horizontal" method="post" action="{{ url('/kas/masuk/edit') }}"> -->
			<form class="form-horizontal" id="form_kas_debet_edit" method="POST">
				<input type="hidden" name="_method" value="PUT">
				{{ csrf_field() }}
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<fieldset>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Kategori</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="kategori_edit" id="kategori_edit" required style="width: 360px">
											<option value="">Pilih Kategori</option>
											@foreach($kategori as $key => $value)
											<option value="{{ Crypt::encrypt($value->id) }}" data-cat="{{ $value->id }}">{{ $value->nama }}</option>
											@endforeach
										</select>
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Keterangan</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="ket_edit" id="ket_edit" />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Jumlah</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="jumlah_edit" onkeypress="return isNumberKey(event)" id="jumlah_edit" />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Tanggal</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group date">
						                  <div class="input-group-addon">
						                    <i class="fa fa-calendar"></i>
						                  </div>
						                  <input type="text" class="form-control pull-right input-datepicker" id="datepicker_edit" name="tanggal_edit">
						                </div>
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Ke Akun</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="keakun_edit" id="keakun_edit" style="width: 360px">
											@foreach($akun as $key => $value)
											<option value="{{ Crypt::encrypt($value->id).'_'.$value->jenis_akun.'_'.'('.$value->kode_akun.') '.$value->nama_akun }}" data-kode="{{ $value->kode_akun }}">{{ "(".$value->kode_akun.") ". $value->nama_akun }}</option>
											@endforeach
										</select>
									</label>
								</div>
							</div>
						</section>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Batal</button>
					<button type="submit" class="btn btn-primary" id="btn_submit_edit"><i class="fa fa-save"></i> Simpan</button>
				</div>
			</form>
		</div>
			<!-- /.modal-content -->
	</div>
		<!-- /.modal-dialog -->
</div>

<div class="modal modal-primary fade" id="modal_rincian">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Rincian Kas Debet</h4>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
        	<table class="table table-bordered" id="table_rincian">
        		<thead>
        			<tr>
            			<th class="text-center">Tanggal</th>
            			<th class="text-center">Keterangan</th>
            			<th class="text-center">Jumlah</th>
            		</tr>
        		</thead>
        		<tbody>
        		</tbody>
        	</table>
        </div>
      </div>
      <div class="modal-footer">
        <h3 class="pull-left">Total Kas Debet</h3>
		<h3 class="pull-right" id="rincian_total_deposito"></h3>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
@endsection

@section('extra_script')
<script type="text/javascript">
	var table_kas_debet;
	$(function() {
		$('#kategori').val('');
		$('#ket').val('');
		$('#jumlah').val('');
		$('#datepicker').val('');
		$('#option_view').val('BulanLalu');
		table_kas_debet = $('#example1').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('debit') }}",
			"columns":[
				{"data": "tanggal"},
				{"data": "c_transaksi"},
				{"data": "jumlah"},
				{"data": "keakun"},
				{"data": "aksi"}
			]
		})

	    $('#deposito_pertanggal').hide();
	    $('#deposito_perbulan').hide();
	    $('#deposito_pertahun').hide();

	    function lastMonthYear() {
	    	var nilai = $('#option_view').val();
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
	    	$.getJSON(baseUrl+'/kas/masuk/akumulasi/'+nilai+'_null', function(resp){
		        $('#txt_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
                                        val.keterangan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		    });
	    }

	    function pertanggal() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = getFormattedDate($('#pertanggal').val());
	    	$.getJSON(baseUrl+'/kas/masuk/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
                                        val.keterangan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		    });
	    }

	    function perbulan() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = getFormattedMonth($('#perbulan').val());
	    	$.getJSON(baseUrl+'/kas/masuk/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
                                        val.keterangan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		    });
	    }

	    function pertahun() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tahun = $('#pertahun').val();
	    	$.getJSON(baseUrl+'/kas/masuk/akumulasi/'+nilai+'_'+tahun, function(resp){
		        $('#txt_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
                                        val.keterangan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                	];
                        array_data[array_data.length] = temp_array;
			        })
			        $('#table_rincian').dataTable().fnAddData(array_data);
                  	$('#table_rincian').dataTable().fnDraw();
                  	$('#rincian_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
             	}
		    });
	    }

	    if ($('#option_view').val() == "BulanLalu") {
	    	$('#pertanggal').val('');
	    	$('#perbulan').val('');
	    	$('#pertahun').val('');
	    	$('#deposito_pertanggal').hide();
		    $('#deposito_perbulan').hide();
		    $('#deposito_pertahun').hide();
	    	lastMonthYear();
	    }

	    $('#option_view').on('change', function(e){
	    	var nilai = $('#option_view').val();
			if (nilai == "BulanLalu" || nilai == "TahunLalu") {
				lastMonthYear();
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#deposito_pertanggal').hide();
			    $('#deposito_perbulan').hide();
			    $('#deposito_pertahun').hide();
		    } else if (nilai == "Pertanggal") {
		    	$('#txt_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#deposito_pertanggal').show();
			    $('#deposito_perbulan').hide();
			    $('#deposito_pertahun').hide();
		    } else if (nilai == "Perbulan") {
		    	$('#txt_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#deposito_pertanggal').hide();
			    $('#deposito_perbulan').show();
			    $('#deposito_pertahun').hide();
		    } else if (nilai == "Pertahun") {
		    	$('#txt_total_deposito').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#deposito_pertanggal').hide();
			    $('#deposito_perbulan').hide();
			    $('#deposito_pertahun').show();
		    }
	    })

	    $('#pertanggal').on('change', function(e){
	    	pertanggal();
	    })

	    $('#perbulan').on('change', function(e){
	    	perbulan();
	    })

	    $('#pertahun').on('change', function(e){
	    	pertahun();
	    })

	    $("#form_kas_debet").submit(function(evt){
	    	evt.preventDefault();
	    	$('#btn_submit').attr('disabled', true);
	    	showLoading();
	    	axios.post("{{url('/kas/debet/store')}}", $("#form_kas_debet").serialize())
	    	  .then(function (response) {
	    	  	hideLoading();
	    	  	$('#btn_submit').attr('disabled', false);
	    	  	if (response.data.status == "success") {
	    	  		$("#form_kas_debet")[0].reset();
	    	  		$('#kategori').trigger('change');
	    	  		$('#keakun').trigger('change');
	    	  		$('.saldo').text("Saldo = "+response.data.sisa_saldo);
	    	  		$('.sisa_saldo_bank').text(response.data.sisa_saldo_bank);
	    	  		$('.sisa_saldo_kas').text(response.data.sisa_saldo_kas);
	    	  		table_kas_debet.api().ajax.reload();
	    	  		alertSuccess('Berhasil', response.data.message);
	    	  	} else {
	    	  		alertWarning('Gagal', response.data.message);
	    	  	}
	    	  })
	    	  .catch(function (error) {
	    	  	hideLoading();
	    	  	$('#btn_submit').attr('disabled', false);
	    	    alertError('Error', error);
	    	  });
	    })

	    $("#form_kas_debet_edit").submit(function(evt){
	    	evt.preventDefault();
	    	$('#btn_submit_edit').attr('disabled', true);
	    	showLoading();
	    	axios.put("{{url('/kas/debet/edit')}}", $("#form_kas_debet_edit").serialize())
	    	  .then(function (response) {
	    	  	hideLoading();
	    	  	$('#btn_submit_edit').attr('disabled', false);
	    	  	if (response.data.status == "success") {
	    	  		$("#form_kas_debet_edit")[0].reset();
	    	  		$('#kategori_edit').trigger('change');
	    	  		$('#keakun_edit').trigger('change');
	    	  		$('.saldo').text("Saldo = "+response.data.sisa_saldo);
	    	  		$('.sisa_saldo_bank').text(response.data.sisa_saldo_bank);
	    	  		$('.sisa_saldo_kas').text(response.data.sisa_saldo_kas);
	    	  		$('#modal_edit').modal('hide');
	    	  		table_kas_debet.api().ajax.reload();
	    	  		alertSuccess('Berhasil', response.data.message);
	    	  	} else {
	    	  		alertWarning('Gagal', response.data.message);
	    	  	}
	    	  })
	    	  .catch(function (error) {
	    	  	hideLoading();
	    	  	$('#btn_submit_edit').attr('disabled', false);
	    	    alertError('Error', error);
	    	  });
	    })
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

	function refreshTable() {
		table_kas_debet.api().ajax.reload();
	}

    function edit(id) {
        $('#kategori_edit').val('');
        $('#ket_edit').val('');
        $('#jumlah_edit').val('');
        $('#datepicker_edit').val('');
        $.getJSON(baseUrl+'/kas/masuk/detail/'+id, function(resp){
            $('#id').val(resp.id);
            $('#kategori_edit option[data-cat="'+resp.kategori+'"]').prop("selected", true);
            $("#kategori_edit").trigger('change');
            $('#ket_edit').val(resp.keterangan);
            $('#jumlah_edit').val(rupiah(resp.jumlah, 'Rp'));
            $('#datepicker_edit').val(dateFormat(resp.tanggal, "d M Y"));
            $('#keakun_edit option[data-kode="'+resp.keakun+'"]').prop("selected", true);
            $("#keakun_edit").trigger('change');
            $('#modal_edit').modal('show');
        });
    }
</script>
@endsection