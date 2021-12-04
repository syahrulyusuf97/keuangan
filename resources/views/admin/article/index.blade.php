@extends('layouts.adminLayout.adminContent')
@section('title', 'Daftar Artikel')

@section('content')
<section class="content-header">
	<h1>
		Artikel
		<small>Daftar Artikel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Artikel</a></li>
		<li class="active">Daftar Artikel</li>
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
				<div class="box-header with-border">
					<h3 class="box-title">Daftar Artikel</h3>
					<a href="{{url('/admin/article/create')}}" class="btn btn-info pull-right"><i class="fa fa-plus"></i> Tambah</a>
				</div>
				<div class="box-body table-responsive">
					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th class="text-center">Judul</th>
								<th class="text-center">Status</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
						<tfoot>
							<tr>
								<th class="text-center">Judul</th>
								<th class="text-center">Status</th>
								<th class="text-center">Aksi</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	var table;
	$(document).ready(function(){
		table = $('#example1').dataTable({
			"processing": true,
			"serverSide": true,
			"ajax": "{{ route('getArticle') }}",
			"columns":[
				{"data": "judul"},
				{"data": "status"},
				{"data": "aksi"}
			]
		})
	})

	function statusPublish(id, status)
	{
		$.ajax({
            url: baseUrl+'/admin/article/status/'+id+'/'+status,
            dataType: 'json',
        }).done(function (results){
            if (results.status == "Failed") {
            	Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: results.message
                });
            } else {
            	Swal.fire({
                    // icon: 'error',
                    title: 'Berhasil',
                    text: results.message
                });
                table.api().ajax.reload();
            }
        });
	}
</script>

@endsection