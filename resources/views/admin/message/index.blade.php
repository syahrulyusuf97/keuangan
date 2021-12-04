@extends('layouts.adminLayout.adminContent')
@section('title', 'Pesan Masuk')

@section('content')
<section class="content-header">
	<h1>
		Dashboard
		<small>Pesan Masuk</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-users"></i> Pesan</a></li>
		<li class="active">Pesan masuk</li>
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
					  <li class="active"><a href="#tab_1" data-toggle="tab">Belum Dibaca ({{Helper::countUnread()}})</a></li>
					  <li><a href="#tab_2" data-toggle="tab">Sudah Dibaca ({{Helper::countRead()}})</a></li>
					  <li><a href="#tab_3" data-toggle="tab">Bookmark Pesan ({{Helper::countBookmark()}})</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="box-header with-border">
								<h3 class="box-title">Pesan Masuk</h3>
							</div>
							<div class="box-body">
								<table id="tb_psn_unread" class="table table-bordered table-striped table-responsive">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
											<th>Aksi</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<div class="box-header with-border">
								<h3 class="box-title">Pesan Masuk Sudah Dibaca</h3>
							</div>
							<div class="box-body">
								<table id="tb_psn_read" class="table table-bordered table-striped table-responsive" style="width: 100%">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
											<th>Aksi</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						<div class="tab-pane" id="tab_3">
							<div class="box-header with-border">
								<h3 class="box-title">Bookmark Pesan Masuk</h3>
							</div>
							<div class="box-body">
								<table id="tb_psn_bookmark" class="table table-bordered table-striped table-responsive" style="width: 100%">
									<thead>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
											<th>Aksi</th>
										</tr>
									</thead>
									<tbody>
									</tbody>
									<tfoot>
										<tr>
											<th>Tanggal</th>
											<th>Nama</th>
											<th>Email</th>
											<th>Subyek</th>
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
	var tb_psn_unread, tb_psn_read, tb_psn_bookmark;
	$(function(){
		tb_psn_unread = $('#tb_psn_unread').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('message.unread') }}",
			"columns":[
				{"data": "date"},
				{"data": "name"},
				{"data": "email"},
				{"data": "subject"},
				{"data": "action"}
			]
		});

		tb_psn_read = $('#tb_psn_read').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('message.read') }}",
			"columns":[
				{"data": "date"},
				{"data": "name"},
				{"data": "email"},
				{"data": "subject"},
				{"data": "action"}
			]
		});

		tb_psn_bookmark = $('#tb_psn_bookmark').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('message.bookmark') }}",
			"columns":[
				{"data": "date"},
				{"data": "name"},
				{"data": "email"},
				{"data": "subject"},
				{"data": "action"}
			]
		});
	})
</script>
@endsection