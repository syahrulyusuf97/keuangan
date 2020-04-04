@extends('layouts.sign.login')
@section('title', 'Registrasi')
@section('content')

<div class="login-box">
	<div class="login-logo">
		<a href="{{url('/')}}"><b>Keuangan</b>KU</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">Registrasi Akun</p>
		@if(Session::has('flash_message_error'))
		<div class="alert alert-error alert-block">
			<strong>{!! session('flash_message_error') !!}</strong>
			@if(Session::has('resendmail')){!! session('resendmail') !!}@endif
		</div>
		@endif
		@if(Session::has('flash_message_success'))
		<div class="alert alert-success alert-block">
			<strong>{!! session('flash_message_success') !!}</strong>
			@if(Session::has('resendmail')){!! session('resendmail') !!}@endif
		</div>
		@endif

		<form action="{{ url('/registrasi') }}" method="post">
			{{ csrf_field() }}
			<div class="form-group">
				<input type="text" name="name" id="name" class="form-control" autofocus placeholder="Nama" autocomplete="off" required>
			</div>
			<div class="form-group text-center">
				<label>
                  <input type="radio" name="sex" class="flat-red" value="Laki-laki" checked>
                  Laki-laki
                </label>
                &nbsp;&nbsp;&nbsp;
				<label>
				  <input type="radio" name="sex" class="flat-red" value="Perempuan">
				  Perempuan
				</label>
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" required>
			</div>
			<div class="form-group">
				<input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" required>
			</div>
			<div class="form-group" id="error_password">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password" required onkeyup="validatePassword()">
				<span class="help-block" id="alert_password"><i>Panjang min. 6 karakter<i></span>
			</div>
			<div class="form-group" id="error_repassword">
				<input type="password" name="repassword" id="repassword" class="form-control" placeholder="Konfirmasi Password" required onkeyup="validateRePassword()">
				<span class="help-block" id="alert_repassword" style="display: none;">Konfirmasi password tidak sesuai</span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="agreement" id="agreement" checked> Saya menyetujui <a href="{{url('/').'#about'}}">Syarat & Ketentuan</a>
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat" id="btn_daftar" disabled>Daftar</button>
				</div>
				<!-- /.col -->
			</div>
		</form>
		<!-- /.social-auth-links -->

		Saya sudah punya akun! <a href="{{ route('login') }}">Login Disini</a><br>

		<div class="text-center" style="margin-top: 10px;">
			<i class="fa fa-home"><a href="{{ url('/') }}">Beranda</a><br></i>
		</div>

	</div>
	<!-- /.login-box-body -->
</div>

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/bootstrap.min.js') }}"></script>
<script src="{{ asset('public/js/iCheck/icheck.min.js') }}"></script>
<script type="text/javascript">
	var resendButton = document.getElementById("btn_resendmail") || "";
	var counter = 60; //Countdown dalam bentuk Seconds
	var newElement = document.createElement("p");
	newElement.innerHTML = "Kirim Ulang (60 detik)"; //Kata-katanya
	var id;

	if (resendButton != "") {
		resendButton.parentNode.replaceChild(newElement, resendButton);

		id = setInterval(function() {
		    counter--;
		    if(counter < 0) {
		        newElement.parentNode.replaceChild(resendButton, newElement);
		        clearInterval(id);
		        id = 0;
		    } else {
		        newElement.innerHTML = "Kirim Ulang (" + counter.toString() + " detik)";
		    }
		}, 1000);
	}

	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
			increaseArea: '20%' // optional
		});
	});

	function validatePassword() {
	  	if ($("#password").val().length < 6) {
	      $("#error_password").addClass('has-error');
	      $("#btn_daftar").attr('disabled', true);
	    } else {
	      $("#error_password").removeClass('has-error');
	    }
	}

	function validateRePassword() {
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
</script>

@endsection