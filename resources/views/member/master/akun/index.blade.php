@extends('layouts.memberLayout.memberContent')
@section('title', 'Master Akun')
@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Master</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Master </a></li>
		<li class="active">Akun</li>
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
							<h3 class="box-title">Form Tambah Akun</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form class="form-horizontal" method="post" action="{{ url('/master/akun') }}">{{ csrf_field() }}
							<div class="box-body">
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Jenis Akun</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<select class="form-control" id="jenis_akun" name="jenis_akun">
											<option value="Kas">Kas</option>
											<option value="Bank">Bank</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="title" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 control-label">Nama Akun</label>

									<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
										<input type="text" class="form-control" id="nama_akun" name="nama_akun" placeholder="Nama Akun" required />
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
					<h3 class="box-title">Daftar Akun</h3>
				</div>
				<div class="box-body table-responsive">
					<table id="master_akun_dashboard" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>Kode</th>
								<th>Akun</th>
								<th>Jenis Akun</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th>Kode</th>
								<th>Akun</th>
								<th>Jenis Akun</th>
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
				<h4 class="modal-title">Edit Master Akun</h4>
			</div>
			<form class="form-horizontal" method="post" action="{{ url('/master/akun/edit') }}">
				{{ csrf_field() }}
				<div class="modal-body">
					<input type="hidden" name="id" id="id">
					<fieldset>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Jenis Akun</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="jenis_akun" id="jenis_akun_edit" readonly />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Kode Akun</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="kode_akun" id="kode_akun_edit" readonly />
									</label>
								</div>
							</div>
						</section>
						<section>
							<div class="row">
								<label class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="padding-top: 5px;">Nama Akun</label>
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
									<label class="input col-lg-10 col-md-10 col-sm-10 col-xs-12">
										<input type="text" class="form-control" name="nama_akun" id="nama_akun_edit" required />
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
@endsection

@section('extra_script')
<script type="text/javascript">

	$(document).on('turbolinks:load', function(){
		$('#nama_akun').val('');
		$('#master_akun_dashboard').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('akun') }}",
			"columns":[
				{"data": "kode"},
				{"data": "akun"},
				{"data": "jenis_akun"},
				{"data": "aksi"}
			]
		})

	})

    function edit(id) {
        $('#nama_akun_edit').val('');
        $.getJSON(baseUrl+'/master/akun/detail?aid='+id, function(resp){
            $('#id').val(resp.id);
            $('#jenis_akun_edit').val(resp.jenis_akun);
            $('#kode_akun_edit').val(resp.kode_akun);
            $('#nama_akun_edit').val(resp.nama_akun);
            $('#modal_edit').modal('show');
        });
    }
</script>
@endsection