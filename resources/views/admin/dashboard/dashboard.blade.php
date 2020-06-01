@extends('layouts.adminLayout.adminContent')
@section('title', 'Dashboard')

@section('content')
<section class="content-header">
	<h1>
		Dashboard
		<small>Control panel</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<!-- small box -->
			<div class="small-box bg-green">
				<div class="inner">
					<h3>{{$member_active}}</h3>

					<p>Member Aktif</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<a href="{{url('/admin/users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<!-- small box -->
			<div class="small-box bg-red">
				<div class="inner">
					<h3>{{$member_nonactive}}</h3>

					<p>Member Tidak Aktif</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<a href="{{url('/admin/users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>

		<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
			<!-- small box -->
			<div class="small-box bg-yellow">
				<div class="inner">
					<h3>{{$member_suspend}}</h3>

					<p>Member Ditangguhkan</p>
				</div>
				<div class="icon">
					<i class="fa fa-users"></i>
				</div>
				<a href="{{url('/admin/users')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
			</div>
		</div>
	</div>
</section>

<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
	
<script type="text/javascript">
	
</script>
@endsection