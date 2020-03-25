@extends('layouts.memberLayout.memberContent')
@section('title', 'Cashflow')
@section('content')

<style type="text/css">
    .overlay { 
        height: 100%; 
        width: 0; 
        position: fixed; 
        background-color: rgb(255, 255, 255); 
        background-color: rgba(0, 96, 128, 0.9); 
        overflow-x: hidden; 
        transition: 0.5s; 
        z-index: 9999;
    } 
      
    .overlay-content { 
        position: relative; 
        top: 25%; 
        width: 100%; 
        text-align: left; 
        margin-top: 30px; 
    } 
      
    .overlay a { 
        padding: 8px; 
        text-decoration: none; 
        font-size: 36px; 
        color: #000000; 
        display: block; 
        transition: 0.3s; 
    } 
      
    .overlay a:hover, 
    .overlay a:focus { 
        color: #f1f1f1; 
    } 
      
    .overlay .closebtn { 
        position: absolute; 
        top: 20px; 
        right: 45px; 
        font-size: 60px; 
    } 
    /* Hide the link that should open and close the topnav on small screens */
    .icon {
      display: none;
    }
    @media screen and (max-width: 600px) {
      a.icon {
        float: right;
        display: block;
      }
      .icon i{
        text-align: center;
        font-size: 24px;
        color: #000;
      }
      .btn-print{
        display: none;
      }
    }
</style>

<style type="text/css" media="print">
    @page { size: portrait; }
    body * #non-printable #myNav {
        display: none;
    }
</style>

<div id="myNav" class="overlay"> 
    <a href="javascript:void(0)" 
       class="closebtn"
       onclick="closeNav()">×</a> 
    
    <div class="overlay-content"> 
        <a href="javascript:void(0);" onclick="printDiv('per_bulan')"><i class="fa fa-print"></i> Print</a> 
        <a href="javascript:void(0);" onclick="exportPDF()"><i class="fa fa-file-pdf-o"></i> Unduh PDF</a> 
        <a href="javascript:void(0);" onclick="exportExcel()"><i class="fa fa-file-excel-o"></i> Unduh Excel</a>
    </div> 
</div>

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
            <div class="col-md-12 box box-primary">
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
        <div class="col-xs-12" id="per_bulan" style="display: none">
            <div class="col-md-12 box box-primary">
                <div class="box-header with-border">
                    <div class="box-tools non-printable" id="non-printable">
                        <button type="button" class="btn btn-warning btn-sm btn-print" title="Print" onclick="printDiv('per_bulan')"><i class="fa fa-print" style="color: #000; font-size: 14px;"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-print" title="PDF" onclick="exportPDF()"><i class="fa fa-file-pdf-o" style="color: #000; font-size: 14px;"></i></button>
                        <button type="button" class="btn btn-warning btn-sm btn-print" title="Excel" onclick="exportExcel()"><i class="fa fa-file-excel-o" style="color: #000; font-size: 14px;"></i></button>
                        <a href="javascript:void(0);" onclick="openNav()" class="icon">
                            <i class="fa fa-bars"></i>
                        </a>
                    </div>
                    
                    <h4 class="text-center">Laporan Arus Kas/<i>Cashflow</i></h4>
                    <h4 class="text-center" id="periode"></h4>
                </div>
                <div class="box-body table-responsive" id="reportBulan"></div>
            </div>
        </div>
    </div>
</section>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>
<script src="{{ asset('public/js/bootstrap/bootstrap-datepicker.min.js') }}"></script>

<script type="text/javascript">
    function openNav() { 
        document.getElementById( 
          "myNav").style.width = "100%"; 
    } 

    function closeNav() { 
        document.getElementById( 
          "myNav").style.width = "0%"; 
    } 

    $(function () {
        
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

        changeBulan();
        changeTahun();

    });

    function changeBulan() {
        $('#perbulan').on('change', function(e){
            var row = '';
            var totalDebitBank = 0;
            var totalKreditBank = 0;
            var totalDebitKas = 0;
            var totalKreditKas = 0;
            if ($("#tbl_report").length > 0){
                $("#tbl_report").remove();
            }
            $("#pertahun").val('');
            $.ajax({
                url: baseUrl+'/laporan/cashflow/bulan/'+$(this).val(),
                dataType: 'json',
                beforeSend: function() {
                    // setting a timeout
                    $("#per_bulan").css('display', 'none');
                }
            }).done(function (results){
                $("#per_bulan").css('display', 'block');
                if (results.bank_debit.length == 0 && results.bank_kredit.length == 0 && results.kas_debit.length == 0 && results.kas_kredit.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak ada transaksi pada bulan '+$("#perbulan").val()
                    });
                    row += '<table class="table table-bordered" style="font-size: 12px;" id="tbl_report">';

                    row += '<tr>\n' +
                            '<td width="15%" class="text-center"><strong>Tanggal</strong></td>\n' +
                            '<td class="text-center"><strong>Akun</strong></td>\n' +
                            '<td class="text-center"><strong>Keterangan</strong></td>\n' +
                            '<td class="text-center"><strong>Jumlah</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="3"><strong>Saldo Awal</strong></td>\n' +
                            '<td class="text-right"><strong>'+new Intl.NumberFormat('de-DE').format(results.saldo_awal)+'</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Bank Masuk</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="debit_bank[]" class="debitbankvalue" value="0">';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totBankDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Bank Keluar</strong></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                                '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_bank[]" class="kreditbankvalue" value="0">';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totBankKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Kas Masuk</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="debit_kas[]" class="debitkasvalue" value="0">';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totKasDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Kas Keluar</strong></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_kas[]" class="kreditkasvalue" value="0">';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totKasKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></strong></td>\n' +
                        '<td class="text-right" id="totNet"></td>\n' +
                        '</tr></table>'

                    $("#reportBulan").append(row);

                    // Bank
                    var debitbankinput = document.getElementsByClassName( 'debitbankvalue' ),
                        debit_bank  = [].map.call(debitbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_bank.length; i++){
                        totalDebitBank += parseInt(debit_bank[i]);
                    }

                    $("#totBankDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitBank)+'</strong>');

                    var kreditbankinput = document.getElementsByClassName( 'kreditbankvalue' ),
                        kredit_bank  = [].map.call(kreditbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_bank.length; i++){
                        totalKreditBank += parseInt(kredit_bank[i]);
                    }

                    $("#totBankKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditBank)+'</strong>');

                    // Kas
                    var debitkasinput = document.getElementsByClassName( 'debitkasvalue' ),
                        debit_kas  = [].map.call(debitkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_kas.length; i++){
                        totalDebitKas += parseInt(debit_kas[i]);
                    }

                    $("#totKasDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitKas)+'</strong>');

                    var kreditkasinput = document.getElementsByClassName( 'kreditkasvalue' ),
                        kredit_kas  = [].map.call(kreditkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_kas.length; i++){
                        totalKreditKas += parseInt(kredit_kas[i]);
                    }

                    $("#totKasKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditKas)+'</strong>');

                    var totNet = results.saldo_awal + ((parseInt(totalDebitBank) - parseInt(totalKreditBank)) + (parseInt(totalDebitKas) - parseInt(totalKreditKas)));

                    $("#totNet").html('<strong>'+new Intl.NumberFormat('de-DE').format(totNet)+'</strong>');

                    $("#periode").html('Periode '+ results.periode);
                } else {
                    row += '<table class="table table-bordered" style="font-size: 12px;" id="tbl_report">';

                    row += '<tr>\n' +
                            '<td width="15%" class="text-center"><strong>Tanggal</strong></td>\n' +
                            '<td class="text-center"><strong>Akun</strong></td>\n' +
                            '<td class="text-center"><strong>Keterangan</strong></td>\n' +
                            '<td class="text-center"><strong>Jumlah</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="3"><strong>Saldo Awal</strong></td>\n' +
                            '<td class="text-right"><strong>'+new Intl.NumberFormat('de-DE').format(results.saldo_awal)+'</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Bank Masuk</strong></td>\n' +
                            '</tr>';

                    if (results.bank_debit.length != 0) {
                        results.bank_debit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit_bank[]" class="debitbankvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                                    '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit_bank[]" class="debitbankvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totBankDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Bank Keluar</strong></td>\n' +
                        '</tr>';

                    if (results.bank_kredit.length != 0) {
                        results.bank_kredit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit_bank[]" class="kreditbankvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                                '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_bank[]" class="kreditbankvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totBankKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Kas Masuk</strong></td>\n' +
                            '</tr>';

                    if (results.kas_debit.length != 0) {
                        results.kas_debit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit_kas[]" class="debitkasvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';

                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="debit_kas[]" class="debitkasvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totKasDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Kas Keluar</strong></td>\n' +
                        '</tr>';

                    if (results.kas_kredit.length != 0) {
                        results.kas_kredit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit_kas[]" class="kreditkasvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_kas[]" class="kreditkasvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totKasKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></strong></td>\n' +
                        '<td class="text-right" id="totNet"></td>\n' +
                        '</tr></table>'

                    $("#reportBulan").append(row);

                    // Bank
                    var debitbankinput = document.getElementsByClassName( 'debitbankvalue' ),
                        debit_bank  = [].map.call(debitbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_bank.length; i++){
                        totalDebitBank += parseInt(debit_bank[i]);
                    }

                    $("#totBankDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitBank)+'</strong>');

                    var kreditbankinput = document.getElementsByClassName( 'kreditbankvalue' ),
                        kredit_bank  = [].map.call(kreditbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_bank.length; i++){
                        totalKreditBank += parseInt(kredit_bank[i]);
                    }

                    $("#totBankKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditBank)+'</strong>');

                    // Kas
                    var debitkasinput = document.getElementsByClassName( 'debitkasvalue' ),
                        debit_kas  = [].map.call(debitkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_kas.length; i++){
                        totalDebitKas += parseInt(debit_kas[i]);
                    }

                    $("#totKasDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitKas)+'</strong>');

                    var kreditkasinput = document.getElementsByClassName( 'kreditkasvalue' ),
                        kredit_kas  = [].map.call(kreditkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_kas.length; i++){
                        totalKreditKas += parseInt(kredit_kas[i]);
                    }

                    $("#totKasKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditKas)+'</strong>');

                    var totNet = results.saldo_awal + ((parseInt(totalDebitBank) - parseInt(totalKreditBank)) + (parseInt(totalDebitKas) - parseInt(totalKreditKas)));

                    $("#totNet").html('<strong>'+new Intl.NumberFormat('de-DE').format(totNet)+'</strong>');

                    $("#periode").html('Periode '+ results.periode);
                }

            })
        })
    }

    function changeTahun() {
        $('#pertahun').on('change', function(e){
            var row = '';
            var totalDebitBank = 0;
            var totalKreditBank = 0;
            var totalDebitKas = 0;
            var totalKreditKas = 0;
            if ($("#tbl_report").length > 0){
                $("#tbl_report").remove();
            }
            $("#perbulan").val('');
            $.ajax({
                url: baseUrl+'/laporan/cashflow/tahun/'+$(this).val(),
                dataType: 'json',
                beforeSend: function() {
                    // setting a timeout
                    $("#per_bulan").css('display', 'none');
                }
            }).done(function (results){
                $("#per_bulan").css('display', 'block');
                if (results.bank_debit.length == 0 && results.bank_kredit.length == 0 && results.kas_debit.length == 0 && results.kas_kredit.length == 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Tidak ada transaksi pada tahun '+$("#pertahun").val()
                    });
                } else {
                    row += '<table class="table table-bordered" style="font-size: 12px;" id="tbl_report">';

                    row += '<tr>\n' +
                            '<td width="15%" class="text-center"><strong>Tanggal</strong></td>\n' +
                            '<td class="text-center"><strong>Akun</strong></td>\n' +
                            '<td class="text-center"><strong>Keterangan</strong></td>\n' +
                            '<td class="text-center"><strong>Jumlah</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="3"><strong>Saldo Awal</strong></td>\n' +
                            '<td class="text-right"><strong>'+new Intl.NumberFormat('de-DE').format(results.saldo_awal)+'</strong></td>\n' +
                            '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Bank Masuk</strong></td>\n' +
                            '</tr>';

                    if (results.bank_debit.length != 0) {
                        results.bank_debit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit_bank[]" class="debitbankvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';

                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="debit_bank[]" class="debitbankvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totBankDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Bank Keluar</strong></td>\n' +
                        '</tr>';

                    if (results.bank_kredit.length != 0) {
                        results.bank_kredit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit_bank[]" class="kreditbankvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_bank[]" class="kreditbankvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL BANK KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totBankKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                            '<td colspan="4"><strong>Kas Masuk</strong></td>\n' +
                            '</tr>';

                    if (results.kas_debit.length != 0) {
                        results.kas_debit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="debit_kas[]" class="debitkasvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="debit_kas[]" class="debitkasvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS MASUK</i></strong></td>\n' +
                        '<td class="text-right" id="totKasDebit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="4"><strong>Kas Keluar</strong></td>\n' +
                        '</tr>';

                    if (results.kas_kredit.length != 0) {
                        results.kas_kredit.forEach(function(element) {
                            row += '<tr>\n' +
                                    '<td width="15%">'+element.c_tanggal+'</td>\n' +
                                    '<td>'+'('+element.akun.kode_akun+') '+element.akun.nama_akun+'</td>\n' +
                                    '<td>'+element.c_transaksi+'</td>\n' +
                                    '<td class="text-right">'+new Intl.NumberFormat('de-DE').format(element.c_jumlah)+'</td>\n' +
                                    '</tr>'+
                                    '<input type="hidden" name="kredit_kas[]" class="kreditkasvalue" value="'+new Intl.NumberFormat('de-DE').format(element.c_jumlah).toString().replace(".", "").replace(".", "").replace(".", "")+'">';
                        });
                    } else {
                        row += '<tr>\n' +
                            '<td colspan="4" class="text-center">Tidak ada transaksi</td>\n' +
                            '</tr>'+
                            '<input type="hidden" name="kredit_kas[]" class="kreditkasvalue" value="0">';
                    }

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>TOTAL KAS KELUAR</i></strong></td>\n' +
                        '<td class="text-right" id="totKasKredit"></td>\n' +
                        '</tr>';

                    row += '<tr>\n' +
                        '<td colspan="3" class="text-center"><strong><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></strong></td>\n' +
                        '<td class="text-right" id="totNet"></td>\n' +
                        '</tr></table>'

                    $("#reportBulan").append(row);

                    // Bank
                    var debitbankinput = document.getElementsByClassName( 'debitbankvalue' ),
                        debit_bank  = [].map.call(debitbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_bank.length; i++){
                        totalDebitBank += parseInt(debit_bank[i]);
                    }

                    $("#totBankDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitBank)+'</strong>');

                    var kreditbankinput = document.getElementsByClassName( 'kreditbankvalue' ),
                        kredit_bank  = [].map.call(kreditbankinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_bank.length; i++){
                        totalKreditBank += parseInt(kredit_bank[i]);
                    }

                    $("#totBankKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditBank)+'</strong>');

                    // Kas
                    var debitkasinput = document.getElementsByClassName( 'debitkasvalue' ),
                        debit_kas  = [].map.call(debitkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < debit_kas.length; i++){
                        totalDebitKas += parseInt(debit_kas[i]);
                    }

                    $("#totKasDebit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalDebitKas)+'</strong>');

                    var kreditkasinput = document.getElementsByClassName( 'kreditkasvalue' ),
                        kredit_kas  = [].map.call(kreditkasinput, function( input ) {
                            return input.value;
                        });

                    for (var i = 0; i < kredit_kas.length; i++){
                        totalKreditKas += parseInt(kredit_kas[i]);
                    }

                    $("#totKasKredit").html('<strong>'+new Intl.NumberFormat('de-DE').format(totalKreditKas)+'</strong>');

                    var totNet = (parseInt(totalDebitBank) - parseInt(totalKreditBank)) + (parseInt(totalDebitKas) - parseInt(totalKreditKas));

                    $("#totNet").html('<strong>'+new Intl.NumberFormat('de-DE').format(totNet)+'</strong>');

                    $("#periode").html('Periode '+ $("#pertahun").val().toString());
                }

            })
        })
    }

    function exportExcel() {
        var bulan = null, tahun = null
        if ($("#perbulan").val() == "" && $("#pertahun").val() == "") {
            Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukkan bulan atau tahun transaksi'
                    });
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
            Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukkan bulan atau tahun transaksi'
                    });
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
            Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Masukkan bulan atau tahun transaksi'
                    });
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