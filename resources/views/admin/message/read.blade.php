@extends('layouts.adminLayout.adminContent')
@section('title', 'Pesan Masuk')
<style type="text/css">
    .img-profile:hover {
        border-color: #00C0EF;
        cursor: pointer;
    }
</style>
@section('content')

<section class="content-header">
    <h1>
        Dashboard
        <small>Pesan Masuk</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard </a></li>
        <li class="active">Pesan Masuk</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary">
	            <div class="box-header with-border">
	              <h3 class="box-title">Baca Pesan</h3>
	            </div>
	            <!-- /.box-header -->
	            <div class="box-body no-padding">
	              <div class="mailbox-read-info">
	                <h3>{{$message->subject}}</h3>
	                <h5>Pesan Dari: {{$message->name}} ({{$message->email}})
	                  <span class="mailbox-read-time pull-right">{{Carbon\Carbon::parse($message->created_at)->format('d-m-Y H:i:s')}} ({{Carbon\Carbon::parse($message->created_at)->diffForHumans()}})</span></h5>
	              </div>
	              <div class="mailbox-read-message">
	                {{$message->message}}
	              </div>
	              <!-- /.mailbox-read-message -->
	            </div>
	            <!-- /.box-body -->
	            <div class="box-footer">
	              
	            </div>
	            <!-- /.box-footer -->
	            <div class="box-footer">
	              <!-- <div class="pull-right">
	                <button type="button" class="btn btn-default"><i class="fa fa-reply"></i> Reply</button>
	                <button type="button" class="btn btn-default"><i class="fa fa-share"></i> Forward</button>
	              </div>
	              <button type="button" class="btn btn-default"><i class="fa fa-trash-o"></i> Delete</button>
	              <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
	            </div> -->
	            <!-- /.box-footer -->
	          </div>
        </div>
    </div>
</section>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
@endsection