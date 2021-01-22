@extends('layouts.sign.mobile.login')
@section('title', 'Registrasi')
@section('content')

<!-- App Header -->
<div class="appHeader no-border">
    <div class="left">
        <a href="javascript:void(0);" class="headerButton goBack" data-turbolinks="true">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"></div>
    <div class="right">
        <a href="{{ route('login') }}" class="headerButton" data-turbolinks="true">
            Login
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Registrasi Sekarang</h1>
        <h4>Lengkapi data form registrasi</h4>
    </div>
    <div class="section mt-2 mb-5 p-3">
        @if(Session::has('flash_message_error'))
        <div class="alert alert-danger mb-1" role="alert">
            <strong>{!! session('flash_message_error') !!}</strong>
            @if(Session::has('resendmail')){!! session('resendmail') !!}@endif
        </div>
        @elseif(Session::has('flash_message_success'))
        <div class="alert alert-success mb-1" role="alert">
            <strong>{!! session('flash_message_success') !!}</strong>
            @if(Session::has('resendmail')){!! session('resendmail') !!}@endif
        </div>
        @endif
        
        <form action="#" method="POST" id="form_registrasi">
            {{ csrf_field() }}
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="name">Nama</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Masukan nama Anda" autocomplete="off" required>
                    <i class="clear-input">
                        <ion-icon name="person-outline"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label>
                      <input type="radio" name="jenis_kelamin" class="flat-red" value="Laki-laki" checked>
                      Laki-laki
                    </label>
                    &nbsp;&nbsp;&nbsp;
                    <label>
                      <input type="radio" name="jenis_kelamin" class="flat-red" value="Perempuan">
                      Perempuan
                    </label>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="email">E-mail</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Masukan e-mail Anda" autocomplete="off" required>
                    <i class="clear-input">
                        <ion-icon name="mail-outline"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Masukan username Anda" autocomplete="off" required>
                    <i class="clear-input">
                        <ion-icon name="person-outline"></ion-icon>
                    </i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Masukan password Anda" required onkeyup="validatePassword()">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
                <span class="help-block" id="alert_password"><i>Panjang min. 6 karakter<i></span>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="repassword">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="repassword" name="repassword" placeholder="Konfirmasi password" required onkeyup="validateRePassword()">
                    <i class="clear-input">
                        <ion-icon name="close-circle"></ion-icon>
                    </i>
                </div>
                <span class="help-block" id="alert_repassword" style="display: none;">Konfirmasi password tidak sesuai</span>
            </div>

            <div class="custom-control custom-checkbox mt-2">
                <input type="checkbox" class="custom-control-input" name="agreement" id="agreement" checked>
                <label class="custom-control-label" for="agreement">
                    Saya menyetujui <a href="#" data-toggle="modal" data-target="#termsModal">Syarat & Ketentuan</a>
                </label>
            </div>

            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn_daftar" disabled>Register</button>
            </div>

        </form>
    </div>

</div>
<!-- * App Capsule -->

<!-- Terms Modal -->
<div class="modal fade modalbox" id="termsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Syarat and Ketentuan</h5>
                <a href="javascript:;" data-dismiss="modal">Close</a>
            </div>
            <div class="modal-body">
                <h2>{{$syarat->title}}</h2>
                {!! $syarat->description !!}
                
                <br>

                <h2>{{$kebijakan->title}}</h2>
                {!! $kebijakan->description !!}
            </div>
        </div>
    </div>
</div>
<!-- * Terms Modal -->

@endsection

@section('extra_script')
<script type="text/javascript">
    function validatePassword() {
        if ($("#password").val().length < 6) {
            $('#repassword').val("");
          $("#error_password").addClass('has-error');
          $("#btn_daftar").attr('disabled', true);
        } else {
            $("#error_password").removeClass('has-error');
            if ($('#repassword').val() != "") {
                $("#error_repassword").removeClass('has-error');
                $("#alert_repassword").hide();
                if($('#repassword').val() != $('#password').val()){
                    $("#error_repassword").addClass('has-error');
                    $("#alert_repassword").show();
                    $("#alert_repassword").html('Konfirmasi password tidak sesuai!');
                    $("#btn_daftar").attr('disabled', true);
                } else {
                    $("#error_repassword").removeClass('has-error');
                    $("#alert_repassword").hide();
                    $("#btn_daftar").attr('disabled', false);
                }
            } else {
                $("#error_repassword").addClass('has-error');
                $("#alert_repassword").show();
                $("#alert_repassword").html('Kolom konfirmasi password tidak boleh kosong!');
                $("#btn_daftar").attr('disabled', true);
            }
        }
    }

    function validateRePassword() {
        if ($("#password").val().length < 6) {
            $('#repassword').val("");
        } else {
            if ($('#repassword').val() != "") {
                $("#error_repassword").removeClass('has-error');
                $("#alert_repassword").hide();
                if($('#repassword').val() != $('#password').val()){
                    $("#error_repassword").addClass('has-error');
                    $("#alert_repassword").show();
                    $("#alert_repassword").html('Konfirmasi password tidak sesuai!');
                    $("#btn_daftar").attr('disabled', true);
                } else {
                    $("#error_repassword").removeClass('has-error');
                    $("#alert_repassword").hide();
                    $("#btn_daftar").attr('disabled', false);
                }
                } else {
                $("#error_repassword").addClass('has-error');
                $("#alert_repassword").show();
                $("#alert_repassword").html('Kolom konfirmasi password tidak boleh kosong!');
                $("#btn_daftar").attr('disabled', true);
            }
        }
    }

    $("#form_registrasi").submit(function(evt){
        evt.preventDefault();
        $.ajax({
            url : baseUrl+"/mobile/registrasi",
            type: "post",
            data: $(this).serialize(),
            dataType: "json",
            success: function(response){
                if (response.status == "success") {
                    $("#form_registrasi")[0].reset();
                    successMessage('Sukses', response.message);
                } else if (response.status == "failed") {
                    infoMessage('Gagal', response.message)
                }
            },
            error: function(xhr, status, errorThrown) {
                errorMessage('Error', '#'+xhr.status+' - '+xhr.statusText);
            }
        });
    })
</script>
@endsection