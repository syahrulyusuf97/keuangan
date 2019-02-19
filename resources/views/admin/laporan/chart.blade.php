@extends('layouts.adminLayout.adminContent')
@section('title', 'Chart')
@section('content')

    <section class="content-header">
        <h1>
            Dashboard
            <small>Laporan Chart</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Laporan </a></li>
            <li class="active">Chart</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
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
                <div class="col-md-12 box box-danger">
                    <div class="col-md-6" style="margin-top: 30px;">
                        <div class="box">

                            <div class="box-header with-border">
                                <h3 class="box-title">Laporan per bulan</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal" method="post" action="{{ url('/kas/masuk') }}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Bulan</label>

                                        <div class="col-sm-10">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="perbulan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <!-- /.box-footer -->
                            </form>
                        </div>
                    </div>
                    <div class="col-md-6" style="margin-top: 30px;">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Laporan per tahun</h3>
                            </div>
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form class="form-horizontal">
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Tahun</label>

                                        <div class="col-sm-10">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="pertahun">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <!-- /.box-footer -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12" id="per_tahun" style="display: none">
                <div class="col-md-12 box box-danger">
                    <div class="box-body chart-responsive">
                        <div class="chart" id="bar-chart" style="height: 340px;"></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12" id="per_bulan" style="display: none">
                <div class="col-md-12 box box-danger">
                    <div class="box-body chart-responsive">
                        <div class="chart" id="line-chart-debit" style="height: 300px;"></div>
                        <hr>
                        <div class="chart" id="line-chart-kredit" style="height: 300px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('public/js/adminLTE/main.js') }}"></script>
    <!-- jQuery 3 -->
    <script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
    <script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>

    <script>
        var baseUrl = '{{ url('/') }}';

        $(function () {
            $('#perbulan').datepicker({
                autoclose: true,
                format: 'MM yyyy',
                viewMode: 'months',
                minViewMode: 'months'
            })

            $('#pertahun').datepicker({
                autoclose: true,
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years'
            })

            $('#perbulan').on('change', function(e){
                $("#pertahun").val('');
                $("#per_bulan").show();
                $("#per_tahun").hide();
                var grafikdebit = $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/debit/'+$(this).val(),
                    dataType: 'json',
                }).done(function (results){
                    console.log(results.length);
                    if (results.length == "0") {
                       alert("Tidak ada transaksi debit pada bulan "+$("#perbulan").val());
                    } else {

                        //BAR CHART
                        var linedebit = new Morris.Line({
                            element: 'line-chart-debit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['debit'],
                            labels: ['Debit'],
                            lineColors: ['#3c8dbc'],
                            hideHover: 'auto'
                        });

                    }

                })

                var grafikkredit = $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/kredit/'+$(this).val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        alert("Tidak ada transaksi kredit pada bulan "+$("#perbulan").val());
                    } else {

                        //BAR CHART
                        var linekredit = new Morris.Line({
                            element: 'line-chart-kredit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['kredit'],
                            labels: ['Kredit'],
                            lineColors: ['#d9534f'],
                            hideHover: 'auto'
                        });
                    }

                })
            })

            $('#pertahun').on('change', function(e){
                $("#perbulan").val('');
                $("#per_tahun").show();
                $("#per_bulan").hide();
                var grafik = $.ajax({
                    url: baseUrl+'/laporan/chart/tahun/'+$(this).val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        alert("Tidak ada transaksi pada tahun "+$("#pertahun").val());
                    } else {

                        //BAR CHART

                        var bar = new Morris.Bar({
                            element: 'bar-chart',
                            resize: true,
                            data: results,
                            barColors: ['#00a65a', '#f56954'],
                            xkey: 'month',
                            ykeys: ['debit', 'kredit'],
                            labels: ['DEBIT', 'KREDIT'],
                            hideHover: 'auto'
                        });

                    }

                })
            })
        });
    </script>
@endsection