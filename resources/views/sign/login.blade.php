@extends('layouts.sign.login')
@section('content')

<div class="login-box">
	<div class="login-logo">
		<a href="#"><b>Keuangan</b>KU</a>
	</div>
	<!-- /.login-logo -->
	<div class="login-box-body">
		<p class="login-box-msg">Silahkan <i>login</i> untuk memulai sesi Anda</p>
		@if(Session::has('flash_message_error'))
		<div class="alert alert-error alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>{!! session('flash_message_error') !!}</strong>
		</div>
		@endif
		@if(Session::has('flash_message_success'))
		<div class="alert alert-success alert-block">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong>{!! session('flash_message_success') !!}</strong>
		</div>
		@endif 
		<form action="{{ url('/login') }}" method="post">
			{{ csrf_field() }}
			<div class="form-group has-feedback">
				<input type="text" name="username" id="email" class="form-control" autofocus placeholder="Username">
				<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
			</div>
			<div class="form-group has-feedback">
				<input type="password" name="password" id="password" class="form-control" placeholder="Password">
				<span class="glyphicon glyphicon-lock form-control-feedback"></span>
			</div>
			<div class="row">
				<div class="col-xs-8">
					<div class="checkbox icheck">
						<label>
							<input type="checkbox" name="remember" id="remember"> Remember Me
						</label>
					</div>
				</div>
				<!-- /.col -->
				<div class="col-xs-4">
					<button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
				</div>
				<!-- /.col -->
			</div>
		</form>
		<!-- /.social-auth-links -->

		<a href="{{ route('password.request') }}">I forgot my password</a><br>

	</div>
	<!-- /.login-box-body -->
</div>

<script src="{{ asset('assets/js/jQuery/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('assets/js/iCheck/icheck.min.js') }}"></script>
<script>
	$(function () {
		$('input').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
  });
	});
</script>

@endsection