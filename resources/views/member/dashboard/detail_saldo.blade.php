@extends('layouts.memberLayout.memberContent')
@section('title', 'Detail Saldo')

@section('content')

<section class="content-header">
	<h1>
		Dashboard
		<small>Detail Saldo {{ucwords($param)}}</small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Detail Saldo {{ucwords($param)}}</li>
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Detail Saldo {{ucwords($param)}}</h3>
				</div>
				<div class="box-body table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Akun</th>
								<th>Saldo</th>
							</tr>
						</thead>
						<tbody>
						@php($total=0)
						@if(count($data) > 0)
						@foreach($data as $key => $value)
						<tr>
							<td>({{$value->kode_akun}}) {{$value->nama_akun}}</td>
							<td>{{Helper::displayRupiah($value->saldo)}}</td>
						</tr>
						@php($total+=$value->saldo)
						@endforeach
						@endif
						</tbody>
						<tfoot>
							<tr>
								<th>Total</th>
								<th>{{Helper::displayRupiah($total)}}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</div>
</section>

@endsection