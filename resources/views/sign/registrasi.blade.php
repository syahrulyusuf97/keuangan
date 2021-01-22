@extends('layouts.sign.login')
@section('title', 'Registrasi')
@section('content')
<div class="d-flex flex-column flex-md-row align-items-md-center py-5">
	<div class="container">
		<div class="row">
			<div class="section-about col-lg-6 mb-4 mb-lg-0">
				<div>
			      <h2><a href="{{url('/')}}" style="color: black">{{$identitas->title}}</a></h2>
			      <p>
			        {{$identitas->deskripsi}}
			        .
			      </p>
			      <p>
			        <a href="{{url('/')}}/#syarat">
			          Syarat & Ketentuan
			        </a>
			      </p>
			    </div>	
			</div>
			<div class="section-login col-lg-6">
				<h4>Registrasi Akun</h4>
				@if(Session::has('flash_message_error'))
				<div class="alert alert-danger">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>{!! session('flash_message_error') !!}</strong>
					@if(Session::has('resendmail')){!! session('resendmail') !!}@endif
				</div>
				@endif
				@if(Session::has('flash_message_success'))
				<div class="alert alert-success">
					<button type="button" class="close" data-dismiss="alert">&times;</button>
					<strong>{!! session('flash_message_success') !!}</strong>
					@if(Session::has('resendmail')){!! session('resendmail') !!}@endif
				</div>
				@endif
				<div class="card-login card mb-3">
					<div class="card-body">
						<form class="form-horizontal" method="POST" action="{{ url('/registrasi') }}">
							@csrf
							<div class="form-group">
								<label for="name">Nama</label>
								<input type="text" name="name" id="name" class="form-control" placeholder="Nama" autocomplete="off" required>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group">
								<label>Jenis Kelamin</label>
								<div>
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
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group">
								<label for="email">Email</label>
								<input type="email" name="email" id="email" class="form-control" placeholder="Email" autocomplete="off" required>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" id="username" class="form-control" placeholder="Username" autocomplete="off" required>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group" id="error_password">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control" placeholder="Password" required onkeyup="validatePassword()">
								<span class="help-block" id="alert_password"><i>Panjang min. 6 karakter</i></span>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group" id="error_repassword">
								<label for="repassword">Konfirmasi Password</label>
								<input type="password" name="repassword" id="repassword" class="form-control" placeholder="Konfirmasi Password" required onkeyup="validateRePassword()">
								<span class="help-block" id="alert_repassword" style="display: none;">Konfirmasi password tidak sesuai</span>
								<div class="invalid-feedback"></div>
							</div>
							<div class="checkbox icheck">
								<label>
									<input type="checkbox" name="agreement" id="agreement" checked> Saya menyetujui <a href="{{url('/').'#syarat'}}">Syarat & Ketentuan</a>
								</label>
							</div>
							<div class="form-group text-center">
								<button class="btn btn-primary" id="btn_daftar" type="submit">Daftar</button>
							</div>
							<div class="login-invite-text text-center">
							  "Saya sudah punya akun?"
							  <a href="{{ route('login') }}" data-turbolinks="true">Login Disini</a>
							  .
							</div>
						</form>
					</div>
				</div>
				<div class="password-reset-link text-center">
				  <i class="fa fa-home"><a href="{{ url('/') }}" data-turbolinks="true">Beranda</a><br></i>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('extra_script')
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
</script>
@endsection