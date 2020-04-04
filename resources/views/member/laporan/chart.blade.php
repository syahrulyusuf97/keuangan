@extends('layouts.memberLayout.memberContent')
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
            <div class="col-md-12 box box-primary">
                <div class="col-md-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">Laporan per bulan</h3>
                    </div>
                    
                    <form class="form-horizontal" method="get" action="">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="akun_bulan" class="col-sm-2 control-label">Akun</label>

                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-tags"></i>
                                        </div>
                                        <select class="form-control" name="akun_bulan" id="akun_bulan">
                                            <option value="Kas">Kas</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="perbulan" class="col-sm-2 control-label">Bulan</label>

                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control pull-right" id="perbulan">
                                    </div>
                                </div>
                            </div>
                            <div class="pull-right">
                                <button type="button" id="btn_per_bln" class="btn btn-primary"><i class="fa fa-filter"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6">
                    <div class="box-header with-border">
                        <h3 class="box-title">Laporan per tahun</h3>
                    </div>
                    
                    <form class="form-horizontal">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="akun_tahun" class="col-sm-2 control-label">Akun</label>

                                <div class="col-sm-10">
                                    <div class="input-group date">
                                        <div class="input-group-addon">
                                            <i class="fa fa-tags"></i>
                                        </div>
                                        <select class="form-control" name="akun_tahun" id="akun_tahun">
                                            <option value="Kas">Kas</option>
                                            <option value="Bank">Bank</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
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
                            <div class="box-footer pull-right">
                                <button type="button" id="btn_per_thn" class="btn btn-primary"><i class="fa fa-filter"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-xs-12" id="per_tahun" style="display: none">
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-c"></h3>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="bar-chart" style="height: 340px;"></div>
                    <div class="col-lg-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Kas Masuk</td>
                                    <td id="total_debit_tahun">0</td>
                                </tr>
                                <tr>
                                    <td>Total Bulan</td>
                                    <td id="total_bulan_debit">0</td>
                                </tr>
                                <tr>
                                    <td>Rata-rata/Bulan</td>
                                    <td id="average_debit_tahun">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Kas Keluar</td>
                                    <td id="total_credit_tahun">0</td>
                                </tr>
                                <tr>
                                    <td>Total Bulan</td>
                                    <td id="total_bulan_credit">0</td>
                                </tr>
                                <tr>
                                    <td>Rata-rata/Bulan</td>
                                    <td id="average_credit_tahun">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-ktg-m-t"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <canvas class="chart" id="dnt-ch-m-t" style="height: 300px;"></canvas>
                    <div class="col-lg-12 table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered" id="tb_ktg_m_t">
                            <tr>
                              <th>Kategori</th>
                              <th>Jumlah</th>
                              <th>Persen(%)</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-ktg-k-t"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <canvas class="chart" id="dnt-ch-k-t" style="height: 300px;"></canvas>
                    <div class="col-lg-12 table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered" id="tb_ktg_k_t">
                            <tr>
                              <th>Kategori</th>
                              <th>Jumlah</th>
                              <th>Persen(%)</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xs-12" id="per_bulan" style="display: none">
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-m"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="line-chart-debit" style="height: 300px;"></div>
                    <div class="col-lg-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Kas Masuk</td>
                                    <td id="total_debit_bulan">0</td>
                                </tr>
                                <tr>
                                    <td>Total Hari</td>
                                    <td id="total_hari_debit">0</td>
                                </tr>
                                <tr>
                                    <td>Rata-rata/Hari</td>
                                    <td id="average_debit_bulan">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                 <div class="box-header with-border">
                  <h3 class="box-title l-k"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <div class="chart" id="line-chart-kredit" style="height: 300px;"></div>
                    <div class="col-lg-6">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td>Total Kas Keluar</td>
                                    <td id="total_credit_bulan">0</td>
                                </tr>
                                <tr>
                                    <td>Total Hari</td>
                                    <td id="total_hari_credit">0</td>
                                </tr>
                                <tr>
                                    <td>Rata-rata/Hari</td>
                                    <td id="average_credit_bulan">0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-ktg-m"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <canvas class="chart" id="dnt-ch-m" style="height: 300px;"></canvas>
                    <div class="col-lg-12 table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered" id="tb_m">
                            <tr>
                              <th>Kategori</th>
                              <th>Jumlah</th>
                              <th>Persen(%)</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title l-ktg-k"></h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                </div>
                <div class="box-body chart-responsive">
                    <canvas class="chart" id="dnt-ch-k" style="height: 300px;"></canvas>
                    <div class="col-lg-12 table-responsive" style="margin-top: 10px;">
                        <table class="table table-bordered" id="tb_k">
                            <tr>
                              <th>Kategori</th>
                              <th>Jumlah</th>
                              <th>Persen(%)</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>

<script type="text/javascript">
    var data_kd = [], data_kk = [], data_bd = [], data_bk = [],
        data_t_kd = [], data_t_kk = [], data_t_bd = [], data_t_bk = [], 
        label_kd = [], label_kk = [], label_bd = [], label_bk = [],
        label_t_kd = [], label_t_kk = [], label_t_bd = [], label_t_bk = [],
        warna_kd = [], warna_kk = [], warna_bd = [], warna_bk = [],
        warna_t_kd = [], warna_t_kk = [], warna_t_bd = [], warna_t_bk = [];

    $(function() {
        $('#perbulan').datepicker({
            autoclose: true,
            format: 'MM yyyy',
            viewMode: 'months',
            minViewMode: 'months',
            language: 'id'
        })

        $('#pertahun').datepicker({
            autoclose: true,
            format: 'yyyy',
            viewMode: 'years',
            minViewMode: 'years'
        })

        $('#btn_per_bln').click(function(e){
            e.preventDefault();
            if ($("#perbulan").val() == "") {
                $("#perbulan").focus();
            } else {
                perBulan();
            }
        })

        $('#btn_per_thn').click(function(e){
            e.preventDefault();
            if ($("#pertahun").val() == "") {
                $("#pertahun").focus();
            } else {
                perTahun();
            }
        })

        $('#akun_bulan').on('change', function(e){
            e.preventDefault();
            if ($("#perbulan").val() == "") {
                $("#perbulan").focus();
                Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukkan periode bulan transaksi'
                    });
            } else {
                $("#perbulan").trigger('change');
            }
        })

        $('#akun_tahun').on('change', function(e){
            e.preventDefault();
            if ($("#pertahun").val() == "") {
                $("#pertahun").focus();
                Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukkan periode tahun transaksi'
                    });
            } else {
                $("#pertahun").trigger('change');
            }
        })

        function perBulan() {
            $("#pertahun").val('');
            $("#per_bulan").show();
            $("#per_tahun").hide();

            if ($("#akun_bulan").val() == "Kas") {
                $("#line-chart-debit").empty();
                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/debit/kas/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-m").text("Laporan Kas Masuk Bulan "+$("#perbulan").val());
                        $("#line-chart-debit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                       Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi debit pada bulan '+$("#perbulan").val()
                        });
                    } else {
                        $(".l-m").text("Laporan Kas Masuk Bulan "+$("#perbulan").val());
                        var linedebit = new Morris.Line({
                            element: 'line-chart-debit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['debit'],
                            labels: ['Debit'],
                            lineColors: ['#3c8dbc'],
                            hideHover: 'auto',
                            parseTime: false
                        });
                    }
                })

                $("#line-chart-kredit").empty();
                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/kredit/kas/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-k").text("Laporan Kas Keluar Bulan "+$("#perbulan").val());
                        $("#line-chart-kredit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi kredit pada bulan '+$("#perbulan").val()
                        });
                    } else {
                        $(".l-k").text("Laporan Kas Keluar Bulan "+$("#perbulan").val());
                        var linekredit = new Morris.Line({
                            element: 'line-chart-kredit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['kredit'],
                            labels: ['Kredit'],
                            lineColors: ['#d9534f'],
                            hideHover: 'auto',
                            parseTime: false
                        });
                    }
                })

                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/kas/average/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_hari_debit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_hari_credit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                    } else {
                        $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(results.total_debit));
                        $("#total_hari_debit").text(results.count_debit);
                        $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(results.total_credit));
                        $("#total_hari_credit").text(results.count_credit);
                        $("#average_debit_bulan").text(new Intl.NumberFormat('de-DE').format(results.average_debit));
                        $("#average_credit_bulan").text(new Intl.NumberFormat('de-DE').format(results.average_credit));
                    }
                })

                // pie
                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-kas-debit-bulan/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_m").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-m").text("Lap. Kas Masuk (Kategori) Bulan "+$("#perbulan").val());
                        var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-m").text("Lap. Kas Masuk (Kategori) Bulan "+$("#perbulan").val());

                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_kd = [];
                        label_kd = [];
                        warna_kd = [];
                        results.forEach(function(element){
                            data_kd.push(element.jumlah);
                            label_kd.push(element.kategori);
                            warna_kd.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_m").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_kd,
                                    backgroundColor: warna_kd,
                                    label: 'Dataset 1'
                                }],
                                labels: label_kd
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });

                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-kas-kredit-bulan/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_k").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-k").text("Lap. Kas Keluar (Kategori) Bulan "+$("#perbulan").val());
                        var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-k").text("Lap. Kas Keluar (Kategori) Bulan "+$("#perbulan").val());

                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_kk = [];
                        label_kk = [];
                        warna_kk = [];
                        results.forEach(function(element){
                            data_kk.push(element.jumlah);
                            label_kk.push(element.kategori);
                            warna_kk.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_k").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_kk,
                                    backgroundColor: warna_kk,
                                    label: 'Dataset 1'
                                }],
                                labels: label_kk
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });
            } else {
                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/debit/bank/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-m").text("Laporan Bank Masuk Bulan "+$("#perbulan").val());
                        $("#line-chart-debit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                       Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi debit pada bulan '+$("#perbulan").val()
                        });
                    } else {
                        $(".l-m").text("Laporan Bank Masuk Bulan "+$("#perbulan").val());
                        var linedebit = new Morris.Line({
                            element: 'line-chart-debit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['debit'],
                            labels: ['Debit'],
                            lineColors: ['#3c8dbc'],
                            hideHover: 'auto',
                            parseTime: false
                        });
                    }
                })

                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/kredit/bank/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-k").text("Laporan Bank Masuk Bulan "+$("#perbulan").val());
                        $("#line-chart-kredit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi kredit pada bulan '+$("#perbulan").val()
                        });
                    } else {
                        $(".l-k").text("Laporan Bank Keluar Bulan "+$("#perbulan").val());
                        var linekredit = new Morris.Line({
                            element: 'line-chart-kredit',
                            resize: true,
                            data: results,
                            xkey: 'date',
                            ykeys: ['kredit'],
                            labels: ['Kredit'],
                            lineColors: ['#d9534f'],
                            hideHover: 'auto',
                            parseTime: false
                        });
                    }
                })

                $.ajax({
                    url: baseUrl+'/laporan/chart/bulan/bank/average/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_hari_debit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_hari_credit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                    } else {
                        $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(results.total_debit));
                        $("#total_hari_debit").text(results.count_debit);
                        $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(results.total_credit));
                        $("#total_hari_credit").text(results.count_credit);
                        $("#average_debit_bulan").text(new Intl.NumberFormat('de-DE').format(results.average_debit));
                        $("#average_credit_bulan").text(new Intl.NumberFormat('de-DE').format(results.average_credit));
                    }
                })

                // pie
                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-bank-debit-bulan/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_m").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-m").text("Lap. Bank Masuk (Kategori) Bulan "+$("#perbulan").val());
                        var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-m").text("Lap. Bank Masuk (Kategori) Bulan "+$("#perbulan").val());

                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_bd = [];
                        label_bd = [];
                        warna_bd = [];
                        results.forEach(function(element){
                            data_bd.push(element.jumlah);
                            label_bd.push(element.kategori);
                            warna_bd.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_m").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_bd,
                                    backgroundColor: warna_bd,
                                    label: 'Dataset 1'
                                }],
                                labels: label_bd
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });

                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-bank-kredit-bulan/'+$("#perbulan").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_k").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-k").text("Lap. Bank Keluar (Kategori) Bulan "+$("#perbulan").val());
                        var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-k").text("Lap. Bank Keluar (Kategori) Bulan "+$("#perbulan").val());

                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_bk= [];
                        label_bk= [];
                        warna_bk= [];
                        results.forEach(function(element){
                            data_bk.push(element.jumlah);
                            label_bk.push(element.kategori);
                            warna_bk.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_k").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_bk,
                                    backgroundColor: warna_bk,
                                    label: 'Dataset 1'
                                }],
                                labels: label_bk
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });
            }
        }

        function perTahun() {
            $("#perbulan").val('');
            $("#per_tahun").show();
            $("#per_bulan").hide();

            if ($("#akun_tahun").val() == "Kas") {
                $.ajax({
                    url: baseUrl+'/laporan/chart/tahun/kas/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-c").text("Laporan Keuangan Dari Akun Kas Tahun "+$("#pertahun").val());
                        $("#bar-chart").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi pada tahun '+$("#pertahun").val()
                        });
                    } else {
                        $(".l-c").text("Laporan Keuangan Dari Akun Kas Tahun "+$("#pertahun").val());
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

                $.ajax({
                    url: baseUrl+'/laporan/chart/tahun/kas/average/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_bulan_debit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_bulan_credit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                    } else {
                        $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(results.total_debit));
                        $("#total_bulan_debit").text(results.count_debit);
                        $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(results.total_credit));
                        $("#total_bulan_credit").text(results.count_credit);
                        $("#average_debit_tahun").text(new Intl.NumberFormat('de-DE').format(results.average_debit));
                        $("#average_credit_tahun").text(new Intl.NumberFormat('de-DE').format(results.average_credit));
                    }
                })

                // pie
                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-kas-debit-tahun/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_ktg_m_t").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-m-t").text("Lap. Kas Masuk (Kategori) Tahun "+$("#pertahun").val());
                        var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-m-t").text("Lap. Kas Masuk (Kategori) Tahun "+$("#pertahun").val());
                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_t_kd= [];
                        label_t_kd= [];
                        warna_t_kd= [];
                        results.forEach(function(element){
                            data_t_kd.push(element.jumlah);
                            label_t_kd.push(element.kategori);
                            warna_t_kd.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_ktg_m_t").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_t_kd,
                                    backgroundColor: warna_t_kd,
                                    label: 'Dataset 1'
                                }],
                                labels: label_t_kd
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });

                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-kas-kredit-tahun/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_ktg_k_t").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-k-t").text("Lap. Kas Keluar (Kategori) Tahun "+$("#pertahun").val());
                        var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-k-t").text("Lap. Kas Keluar (Kategori) Tahun "+$("#pertahun").val());
                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_t_kk = [];
                        label_t_kk = [];
                        warna_t_kk = [];
                        results.forEach(function(element){
                            data_t_kk.push(element.jumlah);
                            label_t_kk.push(element.kategori);
                            warna_t_kk.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_ktg_k_t").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_t_kk,
                                    backgroundColor: warna_t_kk,
                                    label: 'Dataset 1'
                                }],
                                labels: label_t_kk
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                }); 
            } else {
                $.ajax({
                    url: baseUrl+'/laporan/chart/tahun/bank/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $(".l-c").text("Laporan Keuangan Dari Akun Bank Tahun "+$("#pertahun").val());
                        $("#bar-chart").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Tidak ada transaksi pada tahun '+$("#pertahun").val()
                        });
                    } else {
                        $(".l-c").text("Laporan Keuangan Dari Akun Bank Tahun "+$("#pertahun").val());
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

                $.ajax({
                    url: baseUrl+'/laporan/chart/tahun/bank/average/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_bulan_debit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#total_bulan_credit").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                        $("#average_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                    } else {
                        $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(results.total_debit));
                        $("#total_bulan_debit").text(results.count_debit);
                        $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(results.total_credit));
                        $("#total_bulan_credit").text(results.count_credit);
                        $("#average_debit_tahun").text(new Intl.NumberFormat('de-DE').format(results.average_debit));
                        $("#average_credit_tahun").text(new Intl.NumberFormat('de-DE').format(results.average_credit));
                    }
                })

                // pie
                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-bank-debit-tahun/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_ktg_m_t").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-m-t").text("Lap. Bank Masuk (Kategori) Tahun "+$("#pertahun").val());
                        var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-m-t").text("Lap. Bank Masuk (Kategori) Tahun "+$("#pertahun").val());
                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_t_bd= [];
                        label_t_bd= [];
                        warna_t_bd= [];
                        results.forEach(function(element){
                            data_t_bd.push(element.jumlah);
                            label_t_bd.push(element.kategori);
                            warna_t_bd.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_ktg_m_t").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_t_bd,
                                    backgroundColor: warna_t_bd,
                                    label: 'Dataset 1'
                                }],
                                labels: label_t_bd
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                });

                $.ajax({
                    url: baseUrl+'/laporan/chart/kategori-bank-kredit-tahun/'+$("#pertahun").val(),
                    dataType: 'json',
                }).done(function (results){
                    $("#tb_ktg_k_t").find('tr').remove();
                    if (results.length == 0) {
                        $(".l-ktg-k-t").text("Lap. Bank Keluar (Kategori) Tahun "+$("#pertahun").val());
                        var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                        ctx.font = "30px Arial";
                        ctx.fillText("Tidak ada transaksi", 10, 50);
                    } else {
                        $(".l-ktg-k-t").text("Lap. Bank Keluar (Kategori) Tahun "+$("#pertahun").val());
                        var row = "";
                        var total = 0;
                        var tot_percent = 0;
                        data_t_bk = [];
                        label_t_bk = [];
                        warna_t_bk = [];
                        results.forEach(function(element){
                            data_t_bk.push(element.jumlah);
                            label_t_bk.push(element.kategori);
                            warna_t_bk.push(element.warna);
                            row += '<tr><td>'+element.kategori+'</td><td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.jumlah)+'</td><td><div class="lbl-ktg" style="background-color: '+element.warna+';"></div>'+element.persen+'%</td></tr>';
                            total += parseFloat(element.jumlah);
                            tot_percent += parseFloat(element.persen);
                        })

                        row += '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">'+new Intl.NumberFormat('de-DE').format(total)+'</td><td class="text-center text-bold">'+tot_percent+'%</td></tr>';

                        $("#tb_ktg_k_t").append(row);

                        var config = {
                            type: 'pie',
                            data: {
                                datasets: [{
                                    data: data_t_bk,
                                    backgroundColor: warna_t_bk,
                                    label: 'Dataset 1'
                                }],
                                labels: label_t_bk
                            },
                            options: {
                                responsive: true,
                                legend: {
                                            display: false,
                                            position: 'right',
                                            align: 'start'
                                        },
                                tooltips: {
                                    callbacks: {
                                      label: function(tooltipItem, data) {

                                        var label = data.labels[tooltipItem.index] + ' : ' + data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index] || '';

                                        if (label) {
                                          label += '%';
                                        }

                                        return label;
                                      }
                                    }
                                  }
                            }
                        };

                        var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                        window.myPie = new Chart(ctx, config);
                    }
                }); 
            }
        }
    });
</script>
@endsection