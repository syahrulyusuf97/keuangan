@extends('layouts.adminLayout.adminContent')
@section('title', 'Data Member')

@section('content')
<section class="content-header">
	<h1>
		Dashboard
		<small>Member</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-users"></i> Member</a></li>
		<li class="active">Data Member</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if(Session::has('flash_message_error'))
			<div class="alert alert-error alert-block">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>{!! session('flash_message_error') !!}</strong>
			</div>
			@endif
			@if(Session::has('flash_message_success'))
			<div class="alert alert-success alert-block">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>{!! session('flash_message_success') !!}</strong>
			</div>
			@endif
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
					  <li class="active"><a href="#tab_1" data-toggle="tab">Member Aktif</a></li>
					  <li><a href="#tab_2" data-toggle="tab">Member Tidak Aktif</a></li>
					  <li><a href="#tab_3" data-toggle="tab">Member Ditangguhkan/Suspend</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="box-header with-border">
								<h3 class="box-title">Daftar Member Aktif</h3>
							</div>
							<div class="box-body table-responsive">
								<table id="tb_member_active" class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Last Login (WEB)</th>
											<th>Last Login (MOBILE)</th>
											<th>Online Status</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Last Login (WEB)</th>
											<th>Last Login (MOBILE)</th>
											<th>Online Status</th>
											<th>Aksi</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<div class="box-header with-border">
								<h3 class="box-title">Daftar Member Tidak Aktif</h3>
							</div>
							<div class="box-body table-responsive">
								<table id="tb_member_nonactive" class="table table-bordered table-striped" style="width: 100%">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Aksi</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab_3">
							<div class="box-header with-border">
								<h3 class="box-title">Daftar Member Ditangguhkan/Suspend</h3>
							</div>
							<div class="box-body table-responsive">
								<table id="tb_member_suspend" class="table table-bordered table-striped" style="width: 100%">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Last Login (WEB)</th>
											<th>Last Login (MOBILE)</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Username</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
											<th>Last Login (WEB)</th>
											<th>Last Login (MOBILE)</th>
											<th>Aksi</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
</section>

<script src="{{ asset('js/jQuery/jquery.min.js') }}"></script>
<script type="text/javascript">
	var tb_member_active, tb_member_nonactive, tb_member_suspend;
	$(function(){
		tb_member_active = $('#tb_member_active').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('member_active') }}",
			"columns":[
				{"data": "nama"},
				{"data": "jekel"},
				{"data": "username"},
				{"data": "email"},
				{"data": "tanggal"},
				{"data": "last_login_web"},
				{"data": "last_login_mobile"},
				{"data": "is_online"},
				{"data": "aksi"}
			]
		})

		tb_member_nonactive = $('#tb_member_nonactive').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('member_nonactive') }}",
			"columns":[
				{"data": "nama"},
				{"data": "jekel"},
				{"data": "username"},
				{"data": "email"},
				{"data": "tanggal"},
				{"data": "aksi"}
			]
		})

		tb_member_suspend = $('#tb_member_suspend').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('member_suspend') }}",
			"columns":[
				{"data": "nama"},
				{"data": "jekel"},
				{"data": "username"},
				{"data": "email"},
				{"data": "tanggal"},
				{"data": "last_login_web"},
				{"data": "last_login_mobile"},
				{"data": "aksi"}
			]
		})
	})
</script>
@endsection