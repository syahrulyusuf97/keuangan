@extends('layouts.memberLayout.mobile.memberContent')
@section('title', 'Master Kategori')

@section('stylesheet')
<link rel="stylesheet" href="{{ asset('public/css/mobile/dataTables.css') }}">
<style type="text/css">
	table.dataTable thead .sorting, 
	table.dataTable thead .sorting_asc, 
	table.dataTable thead .sorting_desc {
	    background : none;
	}
</style>
@endsection

@section('content')
<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Master Kategori
    </div>
    <div class="right">
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
	<!-- Transactions -->
    <div class="section mt-4">
        <div class="section-heading">
            <h2 class="section-title">Master Kategori</h2>
        </div>
        <div class="wide-block pt-2 pb-2">
        	<p><a class="btn btn-primary" onclick="addKategori()"><ion-icon name="add-outline"></ion-icon>Tambah Data</a></p>
        	<hr>
            <div class="table-responsive">
                <table id="example1" class="table">
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
    <!-- * Transactions -->

</div>
<!-- * App Capsule -->

<!-- Action Sheet -->
<div class="modal fade action-sheet" id="modal_form" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">...</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form id="form_data">
                    	{{csrf_field()}}
                    	<input type="hidden" name="id" id="id">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="account1">Jenis Transaksi</label>
                                <select class="form-control custom-select" id="jenis_transaksi" name="jenis_transaksi">
                                    <option value="Pemasukan">Pemasukan</option>
									<option value="Pengeluaran">Pengeluaran</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Nama Kategori</label>
                                <input type="text" class="form-control form-control-lg" id="nama" name="nama" placeholder="Nama Kategori" autocomplete="off" required>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label">Keterangan</label>
                                <textarea class="form-control form-control-lg" id="keterangan" name="keterangan" placeholder="Keterangan" required></textarea>
                            </div>
                        </div>

                        <div class="form-group basic">
                        	<div class="input-wrapper mb-3">
                                <label class="label">Warna Label</label>
                                <input type="color" class="form-control form-control-lg" name="warna" id="warna" autocomplete="off" required />
                            </div>
                        </div>

                        <div class="form-group">
                        	<button type="submit" class="btn btn-primary btn-block btn-lg">Simpan</button>
                        	<button type="button" class="btn btn-danger btn-block btn-lg" data-dismiss="modal">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- * Action Sheet -->
@endsection

@section('script')
<script src="{{ asset('public/js/jQuery/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
		table = $('#example1').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('kategori') }}",
			"columns":[
				{"data": "jenis_transaksi"},
				{"data": "nama"},
				{"data": "keterangan"},
				{"data": "aksi"}
			]
		})
	})

	function addKategori() {
		$("#jenis_transaksi").attr('disabled', false);
		$('#id').val("");
		$("#form_data")[0].reset();
		$("#modal_form .modal-title").html('Tambah Master Kategori');
		$("#modal_form").modal('show');
	}

	function edit(id) {
		$("#modal_form .modal-title").html('Edit Master Kategori');
		$("#jenis_transaksi").attr('disabled', true);
        $.getJSON(baseUrl+'/master/kategori/detail?kid='+id, function(resp){
            $('#id').val(resp.id);
            $('#jenis_transaksi').val(resp.jenis_transaksi);
            $('#nama').val(resp.nama);
            $('#keterangan').val(resp.keterangan);
            $('#warna').val(resp.warna);
            $('#modal_form').modal('show');
        });
    }

	$("#form_data").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/master/kategori/add", $("#form_data").serialize(), "#modal_form").done(function(response){
			if (response.status == "success") {$("#form_data")[0].reset();$('#id').val("");table.api().ajax.reload();}
		})
	})
</script>
@endsection