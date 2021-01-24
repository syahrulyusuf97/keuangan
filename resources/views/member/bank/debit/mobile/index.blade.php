@extends('layouts.memberLayout.mobile.memberContent')
@section('title', 'Bank Masuk')

@section('extra_style')
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
        <a href="#" class="headerButton goBack" data-turbolinks="true">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Bank Masuk
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
            <h2 class="section-title">Bank Masuk</h2>
        </div>
        <div class="wide-block pt-2 pb-2">
        	<p><a class="btn btn-primary" onclick="add()"><ion-icon name="add-outline"></ion-icon>Tambah Data</a></p>
        	<hr>
            <div class="table-responsive">
                <table id="bank_debet_mobile" class="table">
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
    <!-- * Transactions -->

</div>
<!-- * App Capsule -->

<!-- Action Sheet -->
<div class="modal fade modal-fullscreen action-sheet" id="modal_form" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">...</h5>
            </div>
            <form id="form_data">
            <div class="modal-body">
                <div class="action-sheet-content">
                	{{csrf_field()}}
                	<input type="hidden" name="id" id="id">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="kategori">Kategori</label>
                            <select class="form-control custom-select" name="kategori" id="kategori" required>
                                
                            </select>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="ket">Keterangan</label>
                            <textarea class="form-control form-control-lg" id="ket" name="ket" placeholder="Keterangan bank masuk..." required></textarea>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="jumlah">Jumlah</label>
                            <input type="text" class="form-control form-control-lg nominal" id="jumlah" name="jumlah" onkeypress="return isNumberKey(event)" placeholder="Jumlah deposit..." autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="tanggal">Tanggal</label>
                            <input type="text" class="form-control form-control-lg input-datepicker" id="tanggal" name="tanggal" autocomplete="off" required>
                        </div>
                    </div>

                    <div class="form-group basic">
                        <div class="input-wrapper mb-3">
                            <label class="label" for="keakun">Ke Akun</label>
                            <select class="form-control custom-select" name="keakun" id="keakun" required>
                                
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="col-6">
                    <button type="button" class="btn btn-outline-danger btn-block btn-lg" data-dismiss="modal">BATAL</button>
                </div>
                <div class="col-6">
                    <button type="submit" id="btn_submit" class="btn btn-outline-primary btn-block btn-lg">SIMPAN</button>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
<!-- * Action Sheet -->
@endsection

@section('extra_script')
<script type="text/javascript">
    function getKategori() {
        $.getJSON(baseUrl+'/mobile/get-kategori/Pemasukan', function(resp){
            $('#kategori').html(resp);
        });
    }

    function getAkun() {
        $.getJSON(baseUrl+'/mobile/get-akun/Bank', function(resp){
            $('#keakun').html(resp);
        });
    }

    function add() {
        $('#id').val("");
        $("#form_data")[0].reset();
        $("#modal_form .modal-title").html('Tambah Bank Masuk');
        $("#modal_form").modal('show');
    }

    function edit(id) {
        $("#modal_form .modal-title").html('Edit Bank Masuk');
        $.getJSON(baseUrl+'/bank/masuk/detail/'+id, function(resp){
            $('#id').val(resp.id);
            $('#kategori option[data-cat="'+resp.kategori+'"]').prop("selected", true);
            $('#ket').val(resp.keterangan);
            $('#jumlah').val(rupiah(resp.jumlah, 'Rp'));
            $('#tanggal').val(dateFormat(resp.tanggal, "d M Y"));
            $('#keakun option[data-kode="'+resp.keakun+'"]').prop("selected", true);
            $('#modal_form').modal('show');
        });
    }
    
	$(document).on('turbolinks:load', function(){
		table = $('#bank_debet_mobile').dataTable({
			"processing": true,
			"serverSide": true,
            "destroy": true,
			"ajax": "{{ route('debitBank') }}",
			"columns":[
				{"data": "tanggal"},
				{"data": "c_transaksi"},
				{"data": "jumlah"},
				{"data": "keakun"},
				{"data": "aksi"}
			]
		})

		getKategori();
		getAkun();
	})

    $("#form_data").submit(function(evt){
        evt.preventDefault();
        postData(baseUrl+"/mobile/bank/masuk/add", $("#form_data").serialize(), "#modal_form").done(function(response){
            if (response.status == "success") {$("#form_data")[0].reset();$('#id').val("");table.api().ajax.reload();$(".total-saldo").text(response.data.saldo);}
        })
    })
</script>
@endsection