@extends('layouts.adminLayout.adminContent')
@section('title', 'Cashflow')
@section('content')

    <style type="text/css" media="print">
        @page { size: portrait; }
        body * #non-printable {
            display: none;
        }
    </style>

    <section class="content-header">
        <h1>
            Dashboard
            <small>Laporan Arus Kas/<i>Cashflow</i></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Laporan </a></li>
            <li class="active">Arus Kas/<i>Cashflow</i></li>
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
                            <form class="form-horizontal">
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
            <div class="col-xs-12" id="per_bulan" style="display: block">
                <div class="col-md-12 box box-danger">
                    <div class="box-header with-border">
                        <div class="pull-right box-tools non-printable" id="non-printable">
                            <button type="button" class="btn btn-warning btn-sm" title="Print" id="print" onclick="printDiv('per_bulan')"><i class="fa fa-print" style="color: #000; font-size: 14px;"></i></button>
                            <button type="button" class="btn btn-warning btn-sm" title="PDF" onclick="exportPDF()"><i class="fa fa-file-pdf-o" style="color: #000; font-size: 14px;"></i></button>
                            <button type="button" class="btn btn-warning btn-sm" title="Excel" onclick="exportExcel()"><i class="fa fa-file-excel-o" style="color: #000; font-size: 14px;"></i></button>
                        </div>
                        <h4 class="text-center">Laporan Arus Kas/<i>Cashflow</i></h4>
                        <h4 class="text-center" id="periode"></h4>
                    </div>
                    <div class="box-body table-responsive" id="reportBulan">



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

            changeBulan();
            changeTahun();

        });

        function toRupiah(angka) {
            parseInt(angka);
            var rupiah = '';
            var angkarev = angka.toString().split('').reverse().join('');
            for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
            var hasil = rupiah.split('',rupiah.length-1).reverse().join('');
            return hasil;

        }

        function changeBulan() {
            $('#perbulan').on('change', function(e){
                var row = '';
                var totalDebit = 0;
                var totalKredit = 0;
                if ($("#tbl_report").length > 0){
                    $("#tbl_report").remove();
                }
                $("#pertahun").val('');
                $.ajax({
                    url: baseUrl+'/laporan/cashflow/bulan/'+$(this).val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#periode").html('');
                        alert("Tidak ada transaksi pada bulan "+$("#perbulan").val());
                    } else {
                        row += '<table class="table table-bordered" id="tbl_report"><tr>\n' +
                            '<td colspan="3"><strong>Arus Kas Masuk</strong></td>\n' +
                            '</tr>';
                        results.data.forEach(function(element) {
                            // console.log(element.c_id);
                            if (element.c_jenis == "D") {
                                row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit[]" class="debitvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                            }

                        });


                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>TOTAL KAS MASUK</i></strong></td>\n' +
                            '<td class="text-right" id="totDebit"></td>\n' +
                            '</tr>';

                        row += '<tr>\n' +
                            '<td colspan="3"><strong>Arus Kas Keluar</strong></td>\n' +
                            '</tr>';
                        results.data.forEach(function(element) {
                            // console.log(element.c_id);
                            if (element.c_jenis == "K") {
                                row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit[]" class="kreditvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                            }
                        });



                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>TOTAL KAS KELUAR</i></strong></td>\n' +
                            '<td class="text-right" id="totKredit"></td>\n' +
                            '</tr>';

                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></strong></td>\n' +
                            '<td class="text-right" id="totNet"></td>\n' +
                            '</tr></table>'

                        $("#reportBulan").append(row);

                        var debitinput = document.getElementsByClassName( 'debitvalue' ),
                            debit  = [].map.call(debitinput, function( input ) {
                                return input.value;
                            });

                        for (var i = 0; i < debit.length; i++){
                            totalDebit += parseInt(debit[i]);
                        }

                        $("#totDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebit)+'</strong>');

                        var kreditinput = document.getElementsByClassName( 'kreditvalue' ),
                            kredit  = [].map.call(kreditinput, function( input ) {
                                return input.value;
                            });

                        for (var i = 0; i < kredit.length; i++){
                            totalKredit += parseInt(kredit[i]);
                        }

                        $("#totKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKredit)+'</strong>');
                        var totNet = parseInt(totalDebit) - parseInt(totalKredit);
                        $("#totNet").html('<strong>'+new Intl.NumberFormat('de-DE').format(totNet)+'</strong>');

                        $("#periode").html('Periode '+ results.periode);
                    }

                })
            })
        }

        function changeTahun() {
            $('#pertahun').on('change', function(e){
                var row = '';
                var totalDebit = 0;
                var totalKredit = 0;
                if ($("#tbl_report").length > 0){
                    $("#tbl_report").remove();
                }
                $("#perbulan").val('');
                $.ajax({
                    url: baseUrl+'/laporan/cashflow/tahun/'+$(this).val(),
                    dataType: 'json',
                }).done(function (results){
                    if (results.length == 0) {
                        $("#periode").html('');
                        alert("Tidak ada transaksi pada tahun "+$("#pertahun").val());
                    } else {
                        row += '<table class="table table-bordered" id="tbl_report"><tr>\n' +
                            '<td colspan="3"><strong>Arus Kas Masuk</strong></td>\n' +
                            '</tr>';
                        results.forEach(function(element) {
                            // console.log(element.c_id);
                            if (element.c_jenis == "D") {
                                row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit[]" class="debitvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                            }

                        });


                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>TOTAL KAS MASUK</i></strong></td>\n' +
                            '<td class="text-right" id="totDebit"></td>\n' +
                            '</tr>';

                        row += '<tr>\n' +
                            '<td colspan="3"><strong>Arus Kas Keluar</strong></td>\n' +
                            '</tr>';
                        results.forEach(function(element) {
                            // console.log(element.c_id);
                            if (element.c_jenis == "K") {
                                row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit[]" class="kreditvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                            }
                        });



                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>TOTAL KAS KELUAR</i></strong></td>\n' +
                            '<td class="text-right" id="totKredit"></td>\n' +
                            '</tr>';

                        row += '<tr>\n' +
                            '<td colspan="2" class="text-center"><strong><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></strong></td>\n' +
                            '<td class="text-right" id="totNet"></td>\n' +
                            '</tr></table>'

                        $("#reportBulan").append(row);

                        var debitinput = document.getElementsByClassName( 'debitvalue' ),
                            debit  = [].map.call(debitinput, function( input ) {
                                return input.value;
                            });

                        for (var i = 0; i < debit.length; i++){
                            totalDebit += parseInt(debit[i]);
                        }

                        $("#totDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebit)+'</strong>');

                        var kreditinput = document.getElementsByClassName( 'kreditvalue' ),
                            kredit  = [].map.call(kreditinput, function( input ) {
                                return input.value;
                            });

                        for (var i = 0; i < kredit.length; i++){
                            totalKredit += parseInt(kredit[i]);
                        }

                        $("#totKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKredit)+'</strong>');
                        var totNet = parseInt(totalDebit) - parseInt(totalKredit);
                        $("#totNet").html('<strong>'+new Intl.NumberFormat('de-DE').format(totNet)+'</strong>');

                        $("#periode").html('Periode '+ $("#pertahun").val().toString());
                    }

                })
            })
        }

        function exportExcel() {
            var bulan = null, tahun = null
            if ($("#perbulan").val() == "" && $("#pertahun").val() == "") {
                return alert("Masukkan bulan atau tahun transaksi");
            }

            if ($("#perbulan").val() != "") {
                bulan = $("#perbulan").val();
            } else if ($("#pertahun").val() != "") {
                tahun = $("#pertahun").val();
            }

            window.open(baseUrl+'/laporan/cashflow/excel/'+bulan+'/'+tahun);
        }

        function exportPDF() {
            var bulan = null, tahun = null
            if ($("#perbulan").val() == "" && $("#pertahun").val() == "") {
                return alert("Masukkan bulan atau tahun transaksi");
            }

            if ($("#perbulan").val() != "") {
                bulan = $("#perbulan").val();
            } else if ($("#pertahun").val() != "") {
                tahun = $("#pertahun").val();
            }

            window.open(baseUrl+'/laporan/cashflow/pdf/'+bulan+'/'+tahun);
        }

        function printDiv(divName) {
            var report = document.getElementById('tbl_report');
            if (report == null) {
                return alert("Masukkan bulan atau tahun transaksi");
            }
            $("#non-printable").hide();
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;

        }

        var beforePrint = function() {
            console.log('Functionality to run before printing.');
        };

        var afterPrint = function() {
            console.log('Functionality to run after printing');
            $("#non-printable").show();

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

            changeBulan();
            changeTahun();
        };

        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function(mql) {
                if (mql.matches) {
                    beforePrint();
                } else {
                    afterPrint();
                }
            });
        }

        window.onbeforeprint = beforePrint;
        window.onafterprint = afterPrint;
    </script>
@endsection