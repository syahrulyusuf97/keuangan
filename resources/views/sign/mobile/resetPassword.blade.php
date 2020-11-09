@extends('layouts.sign.mobile.login')
@section('title', 'Lupa Password')
@section('content')

<!-- App Header -->
<div class="appHeader no-border">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle"></div>
    <div class="right">
    	<a href="{{ route('login') }}" class="headerButton page-redirect">
            Login
        </a>
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Reset password</h1>
    </div>
    <div class="section mt-2 mb-5 p-3">
        <form action="#" id="form_resetPasword" method="POST">
            {{ csrf_field() }}
            <input type="hidden" name="email" value="{{$email}}">
            <input type="hidden" name="token" value="{{$token}}">
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="new_pwd">Password Baru</label>
                    <input type="password" class="form-control" id="new_pwd" name="new_pwd" placeholder="Masukan password baru" required onkeyup="validatePassword()">
                    <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                </div>
                <span class="help-block" id="alert_password"><i>Panjang min. 6 karakter<i></span>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="conf_pwd">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="conf_pwd" name="conf_pwd" placeholder="Masukan konfirmasi password" required onkeyup="validateRePassword()">
                    <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                </div>
                <span class="help-block" id="alert_repassword" style="display: none;">Konfirmasi password tidak sesuai</span>
            </div>

            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg" id="btn_reset" disabled>Reset</button>
            </div>

        </form>
    </div>

</div>
<!-- * App Capsule -->

@endsection

@section('script')
<script type="text/javascript">
    function validatePassword() {
        if($('#conf_pwd').val() != $('#new_pwd').val()){
            $("#error_repassword").addClass('has-error');
            $("#alert_repassword").show();
            $("#alert_repassword").html('Konfirmasi password tidak sesuai!');
            $("#btn_reset").attr('disabled', true);
        } else {
            if ($("#new_pwd").val().length < 6) {
              $("#error_password").addClass('has-error');
              $("#btn_reset").attr('disabled', true);
            } else {
                $("#error_password").removeClass('has-error');
              $("#error_repassword").removeClass('has-error');
              $("#alert_repassword").hide();
              $("#btn_reset").attr('disabled', false);
            }
        }
    }

    function validateRePassword() {
      if ($('#conf_pwd').val() != "") {
        $("#error_repassword").removeClass('has-error');
        $("#alert_repassword").hide();
        if($('#conf_pwd').val() != $('#new_pwd').val()){
            $("#error_repassword").addClass('has-error');
            $("#alert_repassword").show();
            $("#alert_repassword").html('Konfirmasi password tidak sesuai!');
            $("#btn_reset").attr('disabled', true);
        } else {
            if ($("#new_pwd").val().length < 6) {
              $("#error_password").addClass('has-error');
              $("#btn_reset").attr('disabled', true);
            } else {
              $("#error_repassword").removeClass('has-error');
              $("#alert_repassword").hide();
              $("#btn_reset").attr('disabled', false);
            }
        }
      } else {
        $("#error_repassword").addClass('has-error');
        $("#alert_repassword").show();
        $("#alert_repassword").html('Kolom konfirmasi password tidak boleh kosong!');
        $("#btn_reset").attr('disabled', true);
      }
    }

    $("#form_resetPasword").submit(function(evt){
        evt.preventDefault();
        if ($("#new_pwd").val() == "" || $("#conf_pwd").val() == "") {
            infoMessage('Info', 'Lengkapi form reset password');
        } else {
            $.ajax({
                url : baseUrl+"/mobile/password_reset",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response){
                    if (response.status == "success") {
                        $("#form_resetPasword")[0].reset();
                        successMessage('Sukses', response.message);
                    } else if (response.status == "failed") {
                        infoMessage('Gagal', response.message)
                    }
                },
                error: function(xhr, status, errorThrown) {
                    errorMessage('Error', '#'+xhr.status+' - '+xhr.statusText);
                }
            });
        }
    })
</script>
@endsection