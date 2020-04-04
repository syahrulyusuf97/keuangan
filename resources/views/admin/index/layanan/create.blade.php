@extends('layouts.adminLayout.adminContent')
@section('title', 'Tambah Layanan')

@section('content')
<section class="content-header">
	<h1>
		Index
		<small>Layanan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Index</a></li>
		<li class="active">Layanan</li>
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
					<h3 class="box-title">Layanan</h3>
					<a href="{{url('/admin/index/layanan')}}" class="btn btn-warning pull-right"><i class="fa fa-arrow-left"></i> Kembali</a>
				</div>
				<form class="form-horizontal" method="post" action="{{$url}}" id="form_submit" enctype="multipart/form-data" onsubmit="{$('#btn_submit').attr('disabled', true)}">{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Judul</label>

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<input type="text" name="title" id="title" class="form-control" value="@if($edit=='true'){{$data->title}}@endif" required />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Icon</label>
							<input type="hidden" name="current_img" value="@if($edit=='true') {{$data->image}} @endif">
							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<input type="file" name="image" id="image" accept="image/*" class="form-control" @if($edit=='false') required @endif>
								@if($edit=='true') 
								<a href="javascript:void(0)" onclick="showImage('{{asset('public/images/index/'.$data->image)}}')">{{$data->image}}</a>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Deskripsi</label>

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<textarea class="form-control" id="deskripsi" name="deskripsi" rows="10" cols="80" required>@if($edit=='true') {{$data->description}} @endif</textarea>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						<button type="submit" class="btn btn-info pull-right" id="btn_submit"><i class="fa fa-save"></i> Simpan</button>
					</div>
					<!-- /.box-footer -->
				</form>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="view_image">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<img src="" id="src_img" style="min-height: 40vh; min-width: 80vh; margin: 0px">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Tutup</button>
			</div>
		</div>
			<!-- /.modal-content -->
	</div>
		<!-- /.modal-dialog -->
</div>

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/vendor/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(function () {
    // Replace the <textarea id="editor1"> with a CKEditor
    // instance, using default configuration.
    CKEDITOR.replace('deskripsi')
  })

	function showImage(link)
	{
		$('#src_img').attr('src', link);
		$('#view_image').modal('show');
	}
</script>
@endsection