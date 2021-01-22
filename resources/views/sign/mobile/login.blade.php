@extends('layouts.sign.mobile.login')
@section('title', 'Login')
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
    </div>
</div>
<!-- * App Header -->

<!-- App Capsule -->
<div id="appCapsule">

    <div class="section mt-2 text-center">
        <h1>Log in</h1>
        <h4>Silahkan <i>login</i> untuk memulai sesi Anda</h4>
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
        
        <form action="#" method="post" id="form_login">
            {{csrf_field()}}
            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username" autocomplete="off" required>
                    <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                </div>
            </div>

            <div class="form-group basic">
                <div class="input-wrapper">
                    <label class="label" for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <i class="clear-input"><ion-icon name="close-circle"></ion-icon></i>
                </div>
            </div>

            <div class="form-links mt-2">
                <div>
                    <a href="{{ route('registrasi') }}" class="page-redirect">Registrasi Sekarang</a>
                </div>
                <div><a data-toggle="modal" data-target="#DialogForm" class="text-muted">Lupa Password?</a></div>
            </div>

            <div class="form-button-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg">Log in</button>
            </div>

        </form>
    </div>

</div>
<!-- * App Capsule -->

<!-- Dialog Form -->
<div class="modal fade dialogbox" id="DialogForm" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lupa Password?</h5>
            </div>
            <form action="#" method="post" id="form_resetpwd">
                {{csrf_field()}}
                <div class="modal-body text-left mb-2">
                    <div class="form-group basic">
                        <div class="input-wrapper">
                            <label class="label" for="email_rpwd">E-mail</label>
                            <input type="email" class="form-control" id="email_rpwd" name="email_rpwd" placeholder="Masukkan e-mail Anda" required>
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
<!-- * Dialog Form -->

@endsection

@section('extra_script')

<script type="text/javascript">
    $("#form_login").submit(function(evt){
        evt.preventDefault();
        if ($("#username").val() == "") {
            infoMessage('Info', 'Username tidak boleh kosong');
        } else if ($("#password").val() == "") {
            infoMessage('Info', 'Password tidak boleh kosong');
        } else {
            $.ajax({
                url : baseUrl+"/mobile/login",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response){
                    if (response.status == "success") {
                        // successMessage('Sukses', response.message);
                        $("#loader").fadeToggle(250);
                        location.href = baseUrl+response.message;
                    } else if (response.status == "failed") {
                        infoMessage('Gagal', response.message)
                    }
                },
                error: function(xhr, status, errorThrown) {
                    // console.log(xhr);
                    // console.log(status);
                    // console.log(errorThrown);
                    errorMessage('Error', '#'+xhr.status+' - '+xhr.statusText);
                }
            });
        }
    })

    $("#form_resetpwd").submit(function(evt){
        evt.preventDefault();
        if ($("#email_rpwd").val() == "") {
            infoMessage('Info', 'Silahkan mengisi kolom email untuk mengatur ulang password Anda');
        } else {
            $.ajax({
                url : baseUrl+"/mobile/reset_password",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response){
                    if (response.status == "success") {
                        $("#form_resetpwd")[0].reset();
                        $("#DialogForm").modal('hide');
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