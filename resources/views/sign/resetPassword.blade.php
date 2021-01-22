@extends('layouts.sign.login')
@section('title', 'Reset Password')
@section('content')

<div class="login-box">
	<div class="login-logo">
		<a href="{{url('/')}}" data-turbolinks="true"><b>Keuangan</b>KU</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">Reset Password</p>
		@if(Session::has('flash_message_error'))
		<div class="alert alert-error alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>{!! session('flash_message_error') !!}</strong>
		</div>
		@endif
		@if(Session::has('flash_message_success'))
		<div class="alert alert-success alert-block">
			<!-- <button type="button" class="close" data-dismiss="alert">&times;</button> -->
			<strong>{!! session('flash_message_success') !!}</strong>
		</div>
		@endif

		<form action="{{ url('/password_reset') }}" method="post">
			{{ csrf_field() }}
			<input type="hidden" name="email" value="{{$email}}">
			<input type="hidden" name="token" value="{{$token}}">
			<div class="form-group" id="error_password">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password" required onkeyup="validatePassword()">
				<span class="help-block" id="alert_password"><i>Panjang min. 6 karakter<i></span>
			</div>
			<div class="form-group" id="error_repassword">
				<input type="password" name="repassword" id="repassword" class="form-control" placeholder="Konfirmasi Password" required onkeyup="validateRePassword()">
				<span class="help-block" id="alert_repassword" style="display: none;">Konfirmasi password tidak sesuai</span>
			</div>
			<div class="row">
				<!-- /.col -->
				<div class="col-xs-6">
					<button type="submit" class="btn btn-primary btn-block btn-flat" id="btn_daftar" disabled>Reset Password</button>
				</div>
				<!-- /.col -->
			</div>
		</form>
		<!-- /.social-auth-links -->

		Saya sudah punya akun! <a href="{{ route('login') }}" data-turbolinks="true">Login Disini</a><br>

		<div class="text-center" style="margin-top: 10px;">
			<i class="fa fa-home"><a href="{{ url('/') }}" data-turbolinks="true">Beranda</a><br></i>
		</div>

	</div>
	<!-- /.login-box-body -->
</div>

@endsection

@section('extra_script')
<script type="text/javascript">
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