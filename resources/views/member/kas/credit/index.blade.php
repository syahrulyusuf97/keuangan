@extends('layouts.memberLayout.memberContent')
@section('title', 'Kas Keluar')
@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Kas</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Kas</a></li>
		<li class="active">Kas Keluar</li>
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
							<h3 class="box-title">Form Tambah Kas Keluar</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="{{ url('/kas/keluar') }}" autocomplete="off" onsubmit="{$('#btn_submit').attr('disabled', true)}">{{ csrf_field() }}
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
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Keperluan</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<!-- <input type="text" class="form-control" id="kep" name="kep" placeholder="Keperluan Kas Keluar..." required> -->
										<textarea class="form-control" id="kep" name="kep" placeholder="Keperluan kas keluar..." required></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Jumlah</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" id="jumlah" name="jumlah" onkeypress="return isNumberKey(event)" placeholder="Jumlah Kredit..." required>
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
									<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Dari Akun</label>

									<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="dariakun" id="dariakun" required>
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
							<h3 class="box-title">Akumulasi Total Kas Keluar</h3>
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
								<div class="form-group" id="credit_pertanggal">
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
								<div class="form-group" id="credit_perbulan">
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
								<div class="form-group" id="credit_pertahun">
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
								<h3 class="pull-left">Total Kas Keluar</h3>
								<h3 class="pull-right" id="txt_total_credit"></h3>
							</div>
							<!-- /.box-footer -->
						</form>
						<div class="box-footer">
							<h3 class="pull-left">Sisa Saldo Kas</h3>
							<h3 class="pull-right" id="txt_sisa_saldo_kas">{{ Helper::displayRupiah($saldo_kas) }}</h3>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Kas Keluar</h3>
				</div>
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Tanggal</th>
								<th>Keperluan</th>
								<th>Jumlah</th>
								<th>Dari Akun</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Tanggal</th>
								<th>Keperluan</th>
								<th>Jumlah</th>
								<th>Dari Akun</th>
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
				<h4 class="modal-title">Edit Kas Keluar</h4>
			</div>
			<form class="form-horizontal" method="post" action="{{ url('/kas/keluar/edit') }}">
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
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Keperluan</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="kep_edit" id="kep_edit" />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Jumlah</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control jml" name="jumlah_edit" onkeypress="return isNumberKey(event)" id="jumlah_edit" />
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
								<label class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="padding-top: 5px;">Dari Akun</label>
								<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<select class="form-control select2" name="dariakun_edit" id="dariakun_edit" style="width: 360px">
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
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary">Simpan perubahan</button>
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
        <h4 class="modal-title">Rincian Kas Keluar</h4>
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
        <h3 class="pull-left">Total Kas Keluar</h3>
		<h3 class="pull-right" id="rincian_total_credit"></h3>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
	$(function() {
		$('#kategori').val('');
		$('#kep').val('');
		$('#jumlah').val('');
		$('#datepicker').val('');
		$('#option_view').val('BulanLalu');
		$('#example1').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('credit') }}",
			"columns":[
				{"data": "tanggal"},
				{"data": "c_transaksi"},
				{"data": "jumlah"},
				{"data": "dariakun"},
				{"data": "aksi"}
			]
		})

	    $('#credit_pertanggal').hide();
	    $('#credit_perbulan').hide();
	    $('#credit_pertahun').hide();

	    if ($('#option_view').val() == "BulanLalu") {
	    	$('#pertanggal').val('');
	    	$('#perbulan').val('');
	    	$('#pertahun').val('');
	    	$('#credit_pertanggal').hide();
	    	$('#credit_perbulan').hide();
	    	$('#credit_pertahun').hide();
	    	lastMonthYear();
	    }

	    $('#option_view').on('change', function(e){
	    	var nilai = $('#option_view').val();
			if (nilai == "BulanLalu" || nilai == "TahunLalu") {
				lastMonthYear();
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').hide();
			    $('#credit_perbulan').hide();
			    $('#credit_pertahun').hide();
		    } else if (nilai == "Pertanggal") {
		    	$('#txt_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').show();
			    $('#credit_perbulan').hide();
			    $('#credit_pertahun').hide();
		    } else if (nilai == "Perbulan") {
		    	$('#txt_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
		    	$('#pertanggal').val('');
		    	$('#perbulan').val('');
		    	$('#pertahun').val('');
		    	$('#credit_pertanggal').hide();
			    $('#credit_perbulan').show();
			    $('#credit_pertahun').hide();
		    } else if (nilai == "Pertahun") {
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
	    	pertanggal();
	    })

	    $('#perbulan').on('change', function(e){
	    	perbulan();
	    })

	    $('#pertahun').on('change', function(e){
	    	pertahun();
	    })

	    function lastMonthYear() {
	    	var nilai = $('#option_view').val();
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	$.getJSON(baseUrl+'/kas/keluar/akumulasi/'+nilai+'_null', function(resp){
	    		$('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
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

	    function pertanggal() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = getFormattedDate($('#pertanggal').val());
	    	$.getJSON(baseUrl+'/kas/keluar/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
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

	    function perbulan() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tgl = getFormattedMonth($('#perbulan').val());
	    	$.getJSON(baseUrl+'/kas/keluar/akumulasi/'+nilai+'_'+tgl, function(resp){
		        $('#txt_total_credit').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
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

	    function pertahun() {
	    	$('#table_rincian').dataTable().fnClearTable();
	    	$('#rincian_total_credit').html('Rp'+number_format('0', '2', ',', '.'));
	    	var nilai = $('#option_view').val();
	    	var tahun = $('#pertahun').val();
	    	$.getJSON(baseUrl+'/kas/keluar/akumulasi/'+nilai+'_'+tahun, function(resp){
		        $('#txt_total_deposito').html('Rp'+number_format(resp.total, '2', ',', '.'));
		        var array_data = [];
             	var temp_array = [];
             	if (resp.result != "") {
             		$.each(resp.result, function(key, val){
			        	temp_array = [];
                       	temp_array = [
                                        dateFormat(val.tanggal),
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

	function edit(id) {
		$('#kategori_edit').val('');
		$('#kep_edit').val('');
		$('#jumlah_edit').val('');
		$('#datepicker_edit').val('');
		$.getJSON(baseUrl+'/kas/keluar/detail/'+id, function(resp){
			$('#id').val(resp.id);
			$('#kategori_edit option[data-cat="'+resp.kategori+'"]').prop("selected", true);
			$("#kategori_edit").trigger('change');
			$('#kep_edit').val(resp.keperluan);
			$('#jumlah_edit').val(rupiah(resp.jumlah, 'Rp'));
			$('#datepicker_edit').val(dateFormat(resp.tanggal, "d M Y"));
			$('#dariakun_edit option[data-kode="'+resp.dariakun+'"]').prop("selected", true);
			$("#dariakun_edit").trigger('change');
			$('#modal_edit').modal('show');
		});
	}
</script>
@endsection