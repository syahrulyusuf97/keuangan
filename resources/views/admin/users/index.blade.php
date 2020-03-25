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
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="box-header with-border">
								<h3 class="box-title">Daftar Member Aktif</h3>
							</div>
							<div class="box-body">
								<table id="tb_member_active" class="table table-bordered table-striped table-responsive">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
											<th>Email</th>
											<th>Tanggal Daftar</th>
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
											<th>Email</th>
											<th>Tanggal Daftar</th>
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
							<div class="box-body">
								<table id="tb_member_nonactive" class="table table-bordered table-striped table-responsive" style="width: 100%">
									<thead>
										<tr>
											<th>Nama</th>
											<th>Jenis Kelamin</th>
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
											<th>Email</th>
											<th>Tanggal Daftar</th>
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

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
		$('#tb_member_active').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('member.active') }}",
			"columns":[
				{"data": "nama"},
				{"data": "sex"},
				{"data": "email"},
				{"data": "tanggal"},
				{"data": "is_onlline"},
				{"data": "aksi"}
			]
		})

		$('#tb_member_nonactive').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('member.nonactive') }}",
			"columns":[
				{"data": "nama"},
				{"data": "sex"},
				{"data": "email"},
				{"data": "tanggal"},
				{"data": "aksi"}
			]
		})
	})
</script>
@endsection