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
                @if (Session::has('flash_message_error'))
                    <div class="alert alert-error alert-block">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>{!! session('flash_message_error') !!}</strong>
                    </div>
                @endif
                @if (Session::has('flash_message_success'))
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
                                    <label for="perbulan_chart" class="col-sm-2 control-label">Bulan</label>

                                    <div class="col-sm-10">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right" id="perbulan_chart">
                                        </div>
                                    </div>
                                </div>
                                <div class="pull-right">
                                    <button type="button" id="btn_per_bln" class="btn btn-primary"><i
                                            class="fa fa-filter"></i> Submit</button>
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
                                            <input type="text" class="form-control pull-right" id="pertahun_chart">
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer pull-right">
                                    <button type="button" id="btn_per_thn" class="btn btn-primary"><i
                                            class="fa fa-filter"></i> Submit</button>
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
                                        <td id="total_debit_tahun" class="text-right">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-6">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>Total Kas Keluar</td>
                                        <td id="total_credit_tahun" class="text-right">0</td>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="dnt-ch-m-t-none" style="height: 200px; display: none;"></div>
                        <canvas class="chart" id="dnt-ch-m-t" style="height: 150px;"></canvas>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="dnt-ch-k-t-none" style="height: 200px; display: none;"></div>
                        <canvas class="chart" id="dnt-ch-k-t" style="height: 1500px;"></canvas>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
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
                                        <td id="total_debit_bulan" class="text-right">0</td>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
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
                                        <td id="total_credit_bulan" class="text-right">0</td>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="dnt-ch-m-none" style="height: 200px; display: none;"></div>
                        <canvas class="chart" id="dnt-ch-m" style="height: 150px;"></canvas>
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
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body chart-responsive">
                        <div class="chart" id="dnt-ch-k-none" style="height: 200px; display: none;"></div>
                        <canvas class="chart" id="dnt-ch-k" style="height: 150px;"></canvas>
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
@endsection

@section('extra_script')
    <script type="text/javascript">
        var data_kd = [],
            data_kk = [],
            data_bd = [],
            data_bk = [],
            data_t_kd = [],
            data_t_kk = [],
            data_t_bd = [],
            data_t_bk = [],
            label_kd = [],
            label_kk = [],
            label_bd = [],
            label_bk = [],
            label_t_kd = [],
            label_t_kk = [],
            label_t_bd = [],
            label_t_bk = [],
            warna_kd = [],
            warna_kk = [],
            warna_bd = [],
            warna_bk = [],
            warna_t_kd = [],
            warna_t_kk = [],
            warna_t_bd = [],
            warna_t_bk = [];

        $(document).on('turbolinks:load', function() {
            $('#perbulan_chart').datepicker({
                autoclose: true,
                format: 'MM yyyy',
                viewMode: 'months',
                minViewMode: 'months',
                language: 'id'
            })

            $('#pertahun_chart').datepicker({
                autoclose: true,
                format: 'yyyy',
                viewMode: 'years',
                minViewMode: 'years'
            })

            $('#btn_per_bln').click(function(e) {
                e.preventDefault();
                if ($("#perbulan_chart").val() == "") {
                    $("#perbulan_chart").focus();
                } else {
                    perBulan();
                }
            })

            $('#btn_per_thn').click(function(e) {
                e.preventDefault();
                if ($("#pertahun_chart").val() == "") {
                    $("#pertahun_chart").focus();
                } else {
                    perTahun();
                }
            })

            function perBulan() {
                $("#pertahun_chart").val('');
                $("#per_bulan").show();
                $("#per_tahun").hide();

                $("#line-chart-debit").empty();
                $("#line-chart-kredit").empty();

                $("#dnt-ch-m").empty();
                $("#dnt-ch-k").empty();

                if ($("#akun_bulan").val() == "Kas") {

                    $.ajax({
                        url: baseUrl + '/laporan/chart/bulan/debit/kas/' + $("#perbulan_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-m").text("Laporan Kas Masuk Bulan " + $("#perbulan_chart").val());
                            $("#line-chart-debit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //      icon: 'error',
                            //      title: 'Oops...',
                            //      text: 'Tidak ada transaksi debit pada bulan '+$("#perbulan").val()
                            //  });
                        } else {
                            $(".l-m").text("Laporan Kas Masuk Bulan " + $("#perbulan_chart").val());
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

                            var total_debit_bulan = 0;
                            results.forEach(function(element) {
                                total_debit_bulan += parseFloat(element.debit);
                            })
                            $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(
                                total_debit_bulan));
                        }
                    })

                    $.ajax({
                        url: baseUrl + '/laporan/chart/bulan/kredit/kas/' + $("#perbulan_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-k").text("Laporan Kas Keluar Bulan " + $("#perbulan_chart").val());
                            $("#line-chart-kredit").html(
                            '<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Oops...',
                            //     text: 'Tidak ada transaksi kredit pada bulan '+$("#perbulan_chart").val()
                            // });
                        } else {
                            $(".l-k").text("Laporan Kas Keluar Bulan " + $("#perbulan_chart").val());
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

                            var total_credit_bulan = 0;
                            results.forEach(function(element) {
                                total_credit_bulan += parseFloat(element.kredit);
                            })
                            $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(
                                total_credit_bulan));
                        }
                    })

                    // pie
                    $.ajax({
                        url: baseUrl + '/laporan/chart/kategori-kas-debit-bulan/' + $("#perbulan_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_m").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-m").text("Lap. Kas Masuk (Kategori) Bulan " + $("#perbulan_chart")
                                .val());
                            $("#dnt-ch-m").attr("style", "display:none");
                            $("#dnt-ch-m-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                            // var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                            // ctx.font = "15px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                        } else {
                            $("#dnt-ch-m").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-m-none").attr("style", "display:none");
                            $(".l-ktg-m").text("Lap. Kas Masuk (Kategori) Bulan " + $("#perbulan_chart")
                                .val());

                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_kd = [];
                            label_kd = [];
                            warna_kd = [];
                            results.forEach(function(element) {
                                data_kd.push(element.persen);
                                label_kd.push(element.kategori);
                                warna_kd.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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
                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/kategori-kas-kredit-bulan/' + $("#perbulan_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_k").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-k").text("Lap. Kas Keluar (Kategori) Bulan " + $("#perbulan_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-k").attr("style", "display:none");
                            $("#dnt-ch-k-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-k").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-k-none").attr("style", "display:none");
                            $(".l-ktg-k").text("Lap. Kas Keluar (Kategori) Bulan " + $("#perbulan_chart")
                                .val());

                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_kk = [];
                            label_kk = [];
                            warna_kk = [];
                            results.forEach(function(element) {
                                data_kk.push(element.persen);
                                label_kk.push(element.kategori);
                                warna_kk.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/bulan/debit/bank/' + $("#perbulan_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-m").text("Laporan Bank Masuk Bulan " + $("#perbulan_chart").val());
                            $("#line-chart-debit").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //      icon: 'error',
                            //      title: 'Oops...',
                            //      text: 'Tidak ada transaksi debit pada bulan '+$("#perbulan").val()
                            //  });
                        } else {
                            $(".l-m").text("Laporan Bank Masuk Bulan " + $("#perbulan_chart").val());
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

                            var total_debit_bulan = 0;
                            results.forEach(function(element) {
                                total_debit_bulan += parseFloat(element.debit);
                            })
                            $("#total_debit_bulan").text(new Intl.NumberFormat('de-DE').format(
                                total_debit_bulan));
                        }
                    })

                    $.ajax({
                        url: baseUrl + '/laporan/chart/bulan/kredit/bank/' + $("#perbulan_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-k").text("Laporan Bank Masuk Bulan " + $("#perbulan_chart").val());
                            $("#line-chart-kredit").html(
                            '<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Oops...',
                            //     text: 'Tidak ada transaksi kredit pada bulan '+$("#perbulan_chart").val()
                            // });
                        } else {
                            $(".l-k").text("Laporan Bank Keluar Bulan " + $("#perbulan_chart").val());
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

                            var total_credit_bulan = 0;
                            results.forEach(function(element) {
                                total_credit_bulan += parseFloat(element.kredit);
                            })
                            $("#total_credit_bulan").text(new Intl.NumberFormat('de-DE').format(
                                total_credit_bulan));
                        }
                    })

                    // pie
                    $.ajax({
                        url: baseUrl + '/laporan/chart/kategori-bank-debit-bulan/' + $("#perbulan_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_m").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-m").text("Lap. Bank Masuk (Kategori) Bulan " + $("#perbulan_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-m').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-m").attr("style", "display:none");
                            $("#dnt-ch-m-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-m").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-m-none").attr("style", "display:none");
                            $(".l-ktg-m").text("Lap. Bank Masuk (Kategori) Bulan " + $("#perbulan_chart")
                                .val());

                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_bd = [];
                            label_bd = [];
                            warna_bd = [];
                            results.forEach(function(element) {
                                data_bd.push(element.persen);
                                label_bd.push(element.kategori);
                                warna_bd.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/kategori-bank-kredit-bulan/' + $("#perbulan_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_k").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-k").text("Lap. Bank Keluar (Kategori) Bulan " + $("#perbulan_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-k').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-k").attr("style", "display:none");
                            $("#dnt-ch-k-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-k").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-k-none").attr("style", "display:none");
                            $(".l-ktg-k").text("Lap. Bank Keluar (Kategori) Bulan " + $("#perbulan_chart")
                                .val());

                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_bk = [];
                            label_bk = [];
                            warna_bk = [];
                            results.forEach(function(element) {
                                data_bk.push(element.persen);
                                label_bk.push(element.kategori);
                                warna_bk.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                $("#perbulan_chart").val('');
                $("#per_tahun").show();
                $("#per_bulan").hide();

                $("#bar-chart").empty();

                $("#dnt-ch-m-t").empty();
                $("#dnt-ch-k-t").empty();

                if ($("#akun_tahun").val() == "Kas") {
                    $.ajax({
                        url: baseUrl + '/laporan/chart/tahun/kas/' + $("#pertahun_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-c").text("Laporan Keuangan Dari Akun Kas Tahun " + $("#pertahun_chart")
                                .val());
                            $("#bar-chart").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                            $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Oops...',
                            //     text: 'Tidak ada transaksi pada tahun '+$("#pertahun").val()
                            // });
                        } else {
                            $(".l-c").text("Laporan Keuangan Dari Akun Kas Tahun " + $("#pertahun_chart")
                                .val());
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

                            var total_debit_tahun = 0;
                            var total_credit_tahun = 0;
                            results.forEach(function(element) {
                                total_debit_tahun += parseFloat(element.debit);
                                total_credit_tahun += parseFloat(element.kredit);
                            })

                            $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(
                                total_debit_tahun));
                            $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(
                                total_credit_tahun));
                        }
                    })

                    // pie
                    $.ajax({
                        url: baseUrl + '/laporan/chart/kategori-kas-debit-tahun/' + $("#pertahun_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_ktg_m_t").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-m-t").text("Lap. Kas Masuk (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-m-t").attr("style", "display:none");
                            $("#dnt-ch-m-t-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-m-t").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-m-t-none").attr("style", "display:none");
                            $(".l-ktg-m-t").text("Lap. Kas Masuk (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_t_kd = [];
                            label_t_kd = [];
                            warna_t_kd = [];
                            results.forEach(function(element) {
                                data_t_kd.push(element.persen);
                                label_t_kd.push(element.kategori);
                                warna_t_kd.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/kategori-kas-kredit-tahun/' + $("#pertahun_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_ktg_k_t").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-k-t").text("Lap. Kas Keluar (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-k-t").attr("style", "display:none");
                            $("#dnt-ch-k-t-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-k-t").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-k-t-none").attr("style", "display:none");
                            $(".l-ktg-k-t").text("Lap. Kas Keluar (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_t_kk = [];
                            label_t_kk = [];
                            warna_t_kk = [];
                            results.forEach(function(element) {
                                data_t_kk.push(element.persen);
                                label_t_kk.push(element.kategori);
                                warna_t_kk.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/tahun/bank/' + $("#pertahun_chart").val(),
                        dataType: 'json',
                    }).done(function(results) {
                        if (results.length == 0) {
                            $(".l-c").text("Laporan Keuangan Dari Akun Bank Tahun " + $("#pertahun_chart")
                                .val());
                            $("#bar-chart").html('<h1 class="text-center">Tidak ada transaksi</h1>');
                            $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                            $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(0));
                            // Swal.fire({
                            //     icon: 'error',
                            //     title: 'Oops...',
                            //     text: 'Tidak ada transaksi pada tahun '+$("#pertahun").val()
                            // });
                        } else {
                            $(".l-c").text("Laporan Keuangan Dari Akun Bank Tahun " + $("#pertahun_chart")
                                .val());
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

                            var total_debit_tahun = 0;
                            var total_credit_tahun = 0;
                            results.forEach(function(element) {
                                total_debit_tahun += parseFloat(element.debit);
                                total_credit_tahun += parseFloat(element.kredit);
                            })

                            $("#total_debit_tahun").text(new Intl.NumberFormat('de-DE').format(
                                total_debit_tahun));
                            $("#total_credit_tahun").text(new Intl.NumberFormat('de-DE').format(
                                total_credit_tahun));
                        }
                    })

                    // pie
                    $.ajax({
                        url: baseUrl + '/laporan/chart/kategori-bank-debit-tahun/' + $("#pertahun_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_ktg_m_t").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-m-t").text("Lap. Bank Masuk (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-m-t').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-m-t").attr("style", "display:none");
                            $("#dnt-ch-m-t-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-m-t").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-m-t-none").attr("style", "display:none");
                            $(".l-ktg-m-t").text("Lap. Bank Masuk (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_t_bd = [];
                            label_t_bd = [];
                            warna_t_bd = [];
                            results.forEach(function(element) {
                                data_t_bd.push(element.persen);
                                label_t_bd.push(element.kategori);
                                warna_t_bd.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
                        url: baseUrl + '/laporan/chart/kategori-bank-kredit-tahun/' + $("#pertahun_chart")
                            .val(),
                        dataType: 'json',
                    }).done(function(results) {
                        $("#tb_ktg_k_t").find('tr').remove();
                        if (results.length == 0) {
                            $(".l-ktg-k-t").text("Lap. Bank Keluar (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            // var ctx = document.getElementById('dnt-ch-k-t').getContext('2d');
                            // ctx.font = "30px Arial";
                            // ctx.fillText("Tidak ada transaksi", 10, 50);
                            $("#dnt-ch-k-t").attr("style", "display:none");
                            $("#dnt-ch-k-t-none").attr("style", "display:block").html(
                                '<h1 class="text-center">Tidak ada transaksi</h1>');
                        } else {
                            $("#dnt-ch-k-t").attr("style", "display:block; height: 200px;");
                            $("#dnt-ch-k-t-none").attr("style", "display:none");
                            $(".l-ktg-k-t").text("Lap. Bank Keluar (Kategori) Tahun " + $("#pertahun_chart")
                                .val());
                            var row = "";
                            var total = 0;
                            var tot_percent = 0;
                            data_t_bk = [];
                            label_t_bk = [];
                            warna_t_bk = [];
                            results.forEach(function(element) {
                                data_t_bk.push(element.persen);
                                label_t_bk.push(element.kategori);
                                warna_t_bk.push(element.warna);
                                row += '<tr><td>' + element.kategori +
                                    '</td><td class="text-right">' + new Intl.NumberFormat('de-DE')
                                    .format(element.jumlah) +
                                    '</td><td><div class="lbl-ktg" style="background-color: ' +
                                    element.warna + ';"></div>' + element.persen + '%</td></tr>';
                                total += parseFloat(element.jumlah);
                                tot_percent += parseFloat(element.persen);
                            })

                            row +=
                                '<tr><td class="text-right text-bold">Total</td><td class="text-right text-bold">' +
                                new Intl.NumberFormat('de-DE').format(total) +
                                '</td><td class="text-center text-bold">' + Math.round(tot_percent) +
                                '%</td></tr>';

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

                                                var label = data.labels[tooltipItem.index] + ' : ' +
                                                    data.datasets[tooltipItem.datasetIndex].data[
                                                        tooltipItem.index] || '';

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
