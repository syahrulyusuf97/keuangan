@extends('layouts.adminLayout.adminContent')
@section('title', 'Tambah Artikel')

@section('content')
<section class="content-header">
	<h1>
		Artikel
		<small>Tambah Artikel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Artikel</a></li>
		<li class="active">Tambah Artikel</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			@if(isset($flash_message_error) && $flash_message_error != "")
			<div class="alert alert-error alert-block">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>{!! $flash_message_error !!}</strong>
			</div>
			@endif
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
					<h3 class="box-title">Tambah Artikel</h3>
					<a href="{{url('/admin/article')}}" class="btn btn-warning pull-right"><i class="fa fa-arrow-left"></i> Kembali</a>
				</div>
				<form class="form-horizontal" method="post" action="{{$url}}" id="form_submit" enctype="multipart/form-data" onsubmit="{$('#btn_submit').attr('disabled', true)}">{{ csrf_field() }}
					<div class="box-body">
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Judul</label>
							@php
								$title=''; $description='';
								if($title_denied != ''){
									$title=$title_denied;
								}
								if($description_denied != '') {
									$description=$description_denied;
								}
								if($edit=='true') {
									$title=$data->title; $description=$data->description;
								}
							@endphp

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<input type="text" name="title" id="title" class="form-control" value="{{$title}}" required />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Deskripsi</label>

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<textarea class="form-control" id="deskripsi" name="deskripsi" rows="40" cols="80" required>{{$description}}</textarea>
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

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/vendor/ckeditor/ckeditor.js') }}"></script>
<script type="text/javascript">
	$(function () {
	    // Replace the <textarea id="editor1"> with a CKEditor
	    // instance, using default configuration.
	    CKEDITOR.replace('deskripsi')
	})
</script>
@endsection