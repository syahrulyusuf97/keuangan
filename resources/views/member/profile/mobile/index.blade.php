@extends('layouts.memberLayout.mobile.memberContent')
@section('title', 'Dashboard')

@section('content')
<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="#" class="headerButton goBack" data-turbolinks="true">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        Pengaturan
    </div>
    <div class="right">
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">
    
    <div class="section mt-3 text-center">
        <div class="avatar-section">
            <a data-toggle="modal" data-target="#DialogFormUbahFoto">
            	@if(auth()->user()->img == "")
            	<img src="{{ asset('images/default.jpg') }}" alt="image" class="imaged w100 rounded img_profile">
            	@else
            	<img src="{{ asset('images/'. auth()->user()->img) }}" alt="image" class="imaged w100 rounded img_profile">
            	@endif
                <span class="button">
                    <ion-icon name="camera-outline"></ion-icon>
                </span>
            </a>
        </div>
    </div>

    <div class="listview-title mt-1">Profil Pribadi</div>
    <ul class="listview image-listview text">
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahNama" class="item">
                <div class="in">
                    <div>Ubah Nama</div>
                </div>
            </a>
        </li>
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahEmail" class="item">
                <div class="in">
                    <div>Ubah E-mail</div>
                </div>
            </a>
        </li>
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahUsername" class="item">
                <div class="in">
                    <div>Ubah Username</div>
                    <!-- <span class="text-primary">Edit</span> -->
                </div>
            </a>
        </li>
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahTTL" class="item">
                <div class="in">
                    <div>Ubah Tempat, Tanggal Lahir</div>
                </div>
            </a>
        </li>
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahAlamat" class="item">
                <div class="in">
                    <div>Ubah Alamat</div>
                </div>
            </a>
        </li>
    </ul>

    <div class="listview-title mt-1">Keamanan</div>
    <ul class="listview image-listview text mb-2">
        <li>
            <a data-toggle="modal" data-target="#DialogFormUbahPassword" class="item">
                <div class="in">
                    <div>Ubah Kata Sandi</div>
                </div>
            </a>
        </li>
    </ul>
    

</div>
<!-- * App Capsule -->

<!-- Ubah Nama -->
<div class="modal fade dialogbox" id="DialogFormUbahNama" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Nama Pengguna</h5>
            </div>
            <form action="#" method="post" id="form_ubah_nama">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="nama">Nama</label>
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Masukkan nama Anda" value="{{ auth()->user()->name }}" autocomplete="off" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Nama -->

<!-- Ubah Email -->
<div class="modal fade dialogbox" id="DialogFormUbahEmail" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Alamat E-mail</h5>
            </div>
            <form action="#" method="post" id="form_ubah_email">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan alamat email Anda" value="{{ auth()->user()->email }}" autocomplete="off" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Email -->

<!-- Ubah Username -->
<div class="modal fade dialogbox" id="DialogFormUbahUsername" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Username</h5>
            </div>
            <form action="#" method="post" id="form_ubah_username">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Masukkan username Anda" value="{{ auth()->user()->username }}" autocomplete="off" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Username -->

<!-- Ubah TTL -->
<div class="modal fade dialogbox" id="DialogFormUbahTTL" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Tempat, Tanggal Lahir</h5>
            </div>
            <form action="#" method="post" id="form_ubah_ttl">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="tempat">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat" name="tempat" placeholder="Masukkan tempat lahir Anda" value="{{ auth()->user()->tempat_lahir }}" autocomplete="off" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                    <div class="form-group basic">
                    	<div class="input-wrapper">
                    		<label class="label">Tanggal Lahir</label>
                    		<select id="dobday" class="form-control" name="tanggal"></select>
                    		<select id="dobmonth" class="form-control" name="bulan"></select>
                    		<select id="dobyear" class="form-control" name="tahun"></select>
                    	</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah TTL -->

<!-- Ubah Alamat -->
<div class="modal fade dialogbox" id="DialogFormUbahAlamat" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Alamat</h5>
            </div>
            <form action="#" method="post" id="form_ubah_alamat">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="nama">Alamat</label>
                            <textarea class="form-control" name="alamat" id="alamat" required>{{auth()->user()->address}}</textarea>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Alamat -->

<!-- Ubah Password -->
<div class="modal fade dialogbox" id="DialogFormUbahPassword" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Kata Sandi</h5>
            </div>
            <form action="#" method="post" id="form_ubah_password">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="oldpassword">Kata Sandi Lama</label>
                            <input type="password" class="form-control" id="oldpassword" name="oldpassword" placeholder="Masukkan kata sandi lama" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="newpassword">Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="newpassword" name="newpassword" placeholder="Masukkan kata sandi baru" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="confnewpassword">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" class="form-control" id="confnewpassword" name="confnewpassword" placeholder="Konfirmasi kata sandi baru" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Password -->

<!-- Ubah Foto -->
<div class="modal fade dialogbox" id="DialogFormUbahFoto" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ubah Foto Profil</h5>
            </div>
            <form action="#" method="post" id="form_ubah_foto">
                {{csrf_field()}}
                <input type="hidden" name="current_img" value="{{auth()->user()->img}}">
                <div class="modal-body text-left mb-2">
                	<div class="section mt-3 text-center">
                	    <div class="avatar-section">
                	        <img src="{{ asset('images/default.jpg') }}" alt="image" class="imaged w100 rounded" id="newimage">
                	    </div>
                	</div>

                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="loadFile(event)" required>
                            <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-inline">
                        <button type="button" class="btn btn-text-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-text-primary">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- * Ubah Foto -->
@endsection

@section('extra_script')
<script type="text/javascript">
    function loadFile(event)
    {
        $("#newimage").attr("src", URL.createObjectURL(event.target.files[0]));
    }

	$(document).on('turbolinks:load', function(){
		$.dobPicker({
            // Selectopr IDs
            daySelector: '#dobday',
            monthSelector: '#dobmonth',
            yearSelector: '#dobyear',

            // Default option values
            dayDefault: 'Tanggal',
            monthDefault: 'Bulan',
            yearDefault: 'Tahun',

            // Minimum age
            minimumAge: 0,

            // Maximum age
            maximumAge: 80
        });
        $('#dobday').val('{{ $day }}');
        $('#dobmonth').val('{{ $month }}');
        $('#dobyear').val('{{ $year }}');
	})

	$("#form_ubah_nama").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/profil/update-nama", $("#form_ubah_nama").serialize(), "#DialogFormUbahNama").done(function(response){
			if (response.status == "success") {$("#nama").val(response.data.nama)}
		});
	})

	$("#form_ubah_email").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/profil/update-email", $("#form_ubah_email").serialize(), "#DialogFormUbahEmail").done(function(response){
			if (response.status == "success") {$("#email").val(response.data.email)}
		});
	})

	$("#form_ubah_username").submit(function(evt){
		evt.preventDefault();
		if (hasWhiteSpace($("#username").val())) {
			infoMessage('Info', 'Username tidak boleh mengandung spasi');
		} else {
			postData(baseUrl+"/mobile/profil/update-username", $("#form_ubah_username").serialize(), "#DialogFormUbahUsername").done(function(response){
				if (response.status == "success") {$("#username").val(response.data.username)}
			});
		}
	})

	$("#form_ubah_ttl").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/profil/update-ttl", $("#form_ubah_ttl").serialize(), "#DialogFormUbahTTL").done(function(response){
			if (response.status == "success") {
				$("#tempat").val(response.data.tempat);
				$('#dobday').val(response.data.tanggal);
				$('#dobmonth').val(response.data.bulan);
				$('#dobyear').val(response.data.tahun);
			}
		});
	})

	$("#form_ubah_alamat").submit(function(evt){
		evt.preventDefault();
		postData(baseUrl+"/mobile/profil/update-alamat", $("#form_ubah_alamat").serialize(), "#DialogFormUbahAlamat").done(function(response){
			if (response.status == "success") {$("#alamat").val(response.data.alamat)}
		});
	})

	$("#form_ubah_password").submit(function(evt){
		evt.preventDefault();
		if ($("#newpassword").val().length < 6) {
			infoMessage('Info', 'Panjang kata sandi baru min. 6 karakter');
		} else {
			postData(baseUrl+"/mobile/profil/update-password", $("#form_ubah_password").serialize(), "#DialogFormUbahPassword").done(function(response){
				if (response.status == "success") {$("#form_ubah_password")[0].reset()}
			});
		}
	})

	$("#form_ubah_foto").submit(function(evt){
		evt.preventDefault();
		if ($("#foto").val() == "") {
			infoMessage('Info', 'Pilih foto Anda');
		} else {
			postData(baseUrl+"/mobile/profil/update-foto", this, "#DialogFormUbahFoto", true).done(function(response){
				if (response.status == "success") {$("#foto").val(""); $(".img_profile").attr('src', "{{ asset('images/') }}/"+response.data.image)}
			});
		}
	})
</script>
@endsection