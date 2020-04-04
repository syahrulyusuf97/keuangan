@extends('layouts.adminLayout.adminContent')
@section('title', 'Syarat & Ketentuan')

@section('content')
<section class="content-header">
	<h1>
		Index
		<small>Syarat & Ketentuan</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-home"></i> Index</a></li>
		<li class="active">Syarat & Ketentuan</li>
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
					<h3 class="box-title">Syarat & Ketentuan</h3>
					@if($edit == "true")
					<a href="{{url('/admin/index/syarat')}}" class="btn btn-warning pull-right"><i class="fa fa-times"></i> Batal</a>
					@else
					<a href="{{url('/admin/index/syarat?edit=true')}}" class="btn btn-warning pull-right"><i class="fa fa-pencil"></i> Edit</a>
					@endif
				</div>
				<form class="form-horizontal" method="post" action="{{url('/admin/index/syarat')}}" id="form_submit" onsubmit="{$('#btn_submit').attr('disabled', true)}">{{ csrf_field() }}
					<input type="hidden" name="id" value="{{Crypt::encrypt($data->id)}}">
					<div class="box-body">
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Judul</label>

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<input type="text" name="title" id="title" class="form-control" value="{{$data->title}}" required @if($edit == "false") readonly @endif />
							</div>
						</div>
						<div class="form-group">
							<label for="title" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 control-label">Deskripsi</label>

							<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
								<textarea class="form-control" id="deskripsi" name="deskripsi" rows="40" cols="80" required @if($edit == "false") readonly @endif>{{$data->description}}</textarea>
							</div>
						</div>
					</div>
					<!-- /.box-body -->
					<div class="box-footer">
						@if($edit == "true")
						<button type="submit" class="btn btn-info pull-right" id="btn_submit"><i class="fa fa-save"></i> Simpan</button>
						@endif
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