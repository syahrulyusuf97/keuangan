@extends('layouts.memberLayout.mobile.memberContent')
@section('title', 'Master Akun')

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
        Master Akun
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
            <h2 class="section-title">Master Akun</h2>
        </div>
        <div class="wide-block pt-2 pb-2">
        	<p><a class="btn btn-primary" onclick="addAkun()"><ion-icon name="add-outline"></ion-icon>Tambah Data</a></p>
        	<hr>
            <div class="table-responsive">
                <table id="example1" class="table">
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
    <!-- * Transactions -->

</div>
<!-- * App Capsule -->

<!-- Action Sheet -->
<div class="modal fade action-sheet" id="modal_akun" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">...</h5>
            </div>
            <div class="modal-body">
                <div class="action-sheet-content">
                    <form id="form_akun">
                    	{{csrf_field()}}
                    	<input type="hidden" name="id" id="id">
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="jenis_akun">Jenis Akun</label>
                                <select class="form-control custom-select" id="jenis_akun" name="jenis_akun">
                                    <option value="Kas">Kas</option>
									<option value="Bank">Bank</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group basic">
                            <div class="input-wrapper mb-3">
                                <label class="label">Nama Akun</label>
                                <input type="text" class="form-control form-control-lg" id="nama_akun" name="nama_akun" placeholder="Nama Akun" autocomplete="off" required>
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
			"ajax": "{{ route('akun') }}",
			"columns":[
				{"data": "kode"},
				{"data": "akun"},
				{"data": "jenis_akun"},
				{"data": "aksi"}
			]
		})
	})

	function addAkun() {
		$("#jenis_akun").attr('disabled', false);
		$('#id').val("");
		$("#form_akun")[0].reset();
		$("#modal_akun .modal-title").html('Tambah Master Akun');
		$("#modal_akun").modal('show');
	}

	function edit(id) {
		$("#modal_akun .modal-title").html('Edit Master Akun');
		$("#jenis_akun").attr('disabled', true);
        $.getJSON(baseUrl+'/master/akun/detail?aid='+id, function(resp){
            $('#id').val(resp.id);
            $('#jenis_akun').val(resp.jenis_akun);
            $('#nama_akun').val(resp.nama_akun);
            $('#modal_akun').modal('show');
        });
    }

	$("#form_akun").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/master/akun/add", $("#form_akun").serialize(), "#modal_akun").done(function(response){
			if (response.status == "success") {$("#form_akun")[0].reset();$('#id').val("");table.api().ajax.reload();}
		})
	})
</script>
@endsection