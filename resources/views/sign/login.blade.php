@extends('layouts.sign.login')
@section('title', 'Login')
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
				<h4>Silahkan <i>login</i> untuk memulai sesi Anda</h4>
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
						<form class="form-horizontal" method="POST" action="{{ url('/login') }}">
							@csrf
							<div class="form-group">
								<label for="username">Username</label>
								<input type="text" name="username" id="username" class="form-control" autocomplete="off" required>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" name="password" id="password" class="form-control" required>
								<div class="invalid-feedback"></div>
							</div>
							<div class="form-group text-center">
								<button class="btn btn-primary" type="submit">Masuk</button>
							</div>
							<div class="login-invite-text text-center">
							  "Belum punya akun?"
							  <a href="{{ route('registrasi') }}" data-turbolinks="true">Registrasi Disini</a>
							  .
							</div>
						</form>
					</div>
				</div>
				<div class="password-reset-link text-center">
				  <a href="#" data-toggle="modal" data-target="#modal_reset_password">Lupa Password?</a>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal modal-default fade" id="modal_reset_password">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      	<h4 class="modal-title">Reset Password</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
      	<form method="POST" action="{{url('/reset_password')}}">
      		{{csrf_field()}}
	      	<div class="input-group">
	        	<input type="email" class="form-control" name="email" placeholder="example@email.com">
	            <span class="input-group-btn">
	              <button type="submit" class="btn btn-info btn-flat">Kirim</button>
	            </span>
	     	</div>
     	</form>
      </div>
    </div>
  </div>
</div>
@endsection