@extends('layouts.memberLayout.memberContent')
@section('title', 'Master Kategori')
@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Master</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Master </a></li>
		<li class="active">Kategori</li>
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
							<h3 class="box-title">Form Tambah Kategori</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="{{ url('/master/kategori') }}">{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Jenis Transaksi</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<select class="form-control" id="jenis_transaksi" name="jenis_transaksi">
											<option value="Pemasukan">Pemasukan</option>
											<option value="Pengeluaran">Pengeluaran</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Nama Kategori</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama Kategori" required />
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Keterangan</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Warna Label</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<div class="input-group my-colorpicker2">
										  <input type="text" class="form-control" id="warna" name="warna" placeholder="Warn Label" required />

										  <div class="input-group-addon">
										    <i></i>
										  </div>
										</div>
									</div>
								</div>
							</div>
							<!-- /.box-body -->
							<div class="box-footer">
								<button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> Simpan</button>
							</div>
							<!-- /.box-footer -->
						</form>
					</div> 
				</div>
			</div>
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Kategori</h3>
				</div>
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Jenis Transaksi</th>
								<th>Kategori</th>
								<th>Keterangan</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Jenis Transaksi</th>
								<th>Kategori</th>
								<th>Keterangan</th>
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
				<h4 class="modal-title">Edit Master Kategori</h4>
			</div>
			<form class="form-horizontal" method="post" action="{{ url('/master/kategori/edit') }}">
				{{ csrf_field() }}
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<fieldset>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Jenis Transaksi</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="jenis_transaksi" id="jenis_transaksi_edit" readonly />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Nama Kategori</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="nama" id="nama_edit" required />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Nama Keterangan</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<textarea class="form-control" name="keterangan" id="keterangan_edit" rows="4"></textarea>
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Warna Label</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<div class="input-group my-colorpicker2">
										  <input type="text" class="form-control" name="warna" id="warna_edit" required />

										  <div class="input-group-addon">
										    <i id="wl"></i>
										  </div>
										</div>
									</label>
								</div>
							</div>
						</section>
					</fieldset>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
					<button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
				</div>
			</form>
		</div>
			<!-- /.modal-content -->
	</div>
		<!-- /.modal-dialog -->
</div>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>

<script type="text/javascript">
	$(function() {
		$('.my-colorpicker2').colorpicker()

		$('#nama').val('');
		$('#keterangan').val('');
		$('#warna').val('');
		$('#example1').dataTable({
			"processing": true,
			// "serverSide": true,
			"ajax": "{{ route('kategori') }}",
			"columns":[
				{"data": "jenis_transaksi"},
				{"data": "nama"},
				{"data": "keterangan"},
				{"data": "aksi"}
			]
		})

	})

    function edit(id) {
        $('#nama_akun_edit').val('');
        $.getJSON(baseUrl+'/master/kategori/detail?kid='+id, function(resp){
        	console.log(resp);
            $('#id').val(resp.id);
            $('#jenis_transaksi_edit').val(resp.jenis_transaksi);
            $('#nama_edit').val(resp.nama);
            $('#keterangan_edit').val(resp.keterangan);
            $('#warna_edit').val(resp.warna);
            $("#wl").css('background-color', resp.warna);
            $('#modal_edit').modal('show');
        });
    }
</script>
@endsection