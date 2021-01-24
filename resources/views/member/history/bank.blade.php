@extends('layouts.memberLayout.memberContent')
@section('title', 'Riwayat Bank')
@section('content')

<section class="content-header">
    <h1>
        Riwayat
        <small>Bank</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Riwayat</li>
        <li class="active">Bank</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <section class="col-lg-12 connectedSortable">
            <!-- Calendar -->
            <div class="box box-solid bg-green-gradient">
                <div class="box-header">
                    <i class="fa fa-calendar"></i>
                    <h3 class="box-title">Kalender</h3>
                    <!-- tools box -->
                    <div class="pull-right box-tools">
                        <button type="button" class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                        <!-- /. tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body no-padding">
                    <!--The calendar -->
                    <div id="calendar" style="width: 100%"></div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer text-black">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Progress bars -->
                            <h4 class="text-center" id="ttl_rkm"></h4>
                            <hr>
                            <div class="table-responsive">
                                <table id="table_deposito" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>Akun</td>
                                            <td>Keterangan</td>
                                            <td>Jumlah</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer text-black">
                    <div class="row">
                        <div class="col-sm-12">
                            <h4 class="text-center" id="ttl_rkk"></h4>
                            <hr>
                            <div class="table-responsive">
                                <table id="table_credit" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>Akun</td>
                                            <td>Keperluan</td>
                                            <td>Jumlah</td>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <!-- /.box -->
        </section>
    </div>
</section>
@endsection

@section('extra_script')
<script type="text/javascript">
    $(document).on('turbolinks:load', function(){
        $('#table_deposito').dataTable();
        $('#table_credit').dataTable();
        $('#calendar').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            language: 'id'
        });
        $('#calendar').datepicker('update', new Date());
        var tgl = $('#calendar').datepicker('getFormattedDate');

        $('#calendar').datepicker().on("changeDate", function(evt){
            tgl = $('#calendar').datepicker('getFormattedDate');
            getRiwayat(tgl);
        });

        // get riwayat
        getRiwayat(tgl);

        function getRiwayat(tanggal)
        {
            $('#table_deposito').dataTable().fnClearTable();
            $('#table_credit').dataTable().fnClearTable();
            $.getJSON(baseUrl+'/riwayat/get-bank/'+tanggal, function(resp){
                // console.log(resp);
                var array_data_credit = [];
                var temp_array_credit = [];
                var array_data_debit = [];
                var temp_array_debit = [];
                if (resp.result_credit != "") {
                    $.each(resp.result_credit, function(key, val){
                        // console.log(val.tanggal);
                        temp_array_credit = [];
                        temp_array_credit = [
                                        val.tanggal,
                                        val.akun,
                                        val.keperluan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                    ];
                        array_data_credit[array_data_credit.length] = temp_array_credit;
                    })
                    $('#table_credit').dataTable().fnAddData(array_data_credit);
                    $('#table_credit').dataTable().fnDraw();
                }

                if (resp.result_debit != "") {
                    $.each(resp.result_debit, function(key, val){
                        // console.log(val.tanggal);
                        temp_array_debit = [];
                        temp_array_debit = [
                                        val.tanggal,
                                        val.akun,
                                        val.keterangan,
                                        'Rp'+number_format(val.jumlah, '2', ',', '.')
                                    ];
                        array_data_debit[array_data_debit.length] = temp_array_debit;
                    })
                    $('#table_deposito').dataTable().fnAddData(array_data_debit);
                    $('#table_deposito').dataTable().fnDraw();
                }
                $('#ttl_rkm').text("Riwayat bank masuk tanggal "+dateFormat(tanggal, "d-m-Y"));
                $('#ttl_rkk').text("Riwayat bank keluar tanggal "+dateFormat(tanggal, "d-m-Y"));
            });
        }
    })
</script>
@endsection