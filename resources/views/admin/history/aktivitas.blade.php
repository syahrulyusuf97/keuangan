@extends('layouts.adminLayout.adminContent')
@section('title', 'Riwayat Aktivitas')
<?php
\Carbon\Carbon::setLocale('id');
?>
@section('content')

<section class="content-header">
    <h1>
        Timeline
        <small>Riwayat Aktivitas</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li>Riwayat</li>
        <li class="active">Aktivitas</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <!-- row -->
    <div class="row">
        <div class="col-md-12">
            <form class="form-horizontal text-center">
                <div class="box-body">
                    <div class="form-group">
                        <label for="title" class="col-sm-2 control-label">Filter</label>

                        <div class="col-sm-8">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right input-datepicker" id="tanggal" placeholder="Filter berdasarkan tanggal" autocomplete="off">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-12" id="result">
            <!-- The time line -->
            @if (count($date) == 0)
                <p class="text-center">Tidak ada log kegiatan</p>
            @else
                <ul class="timeline">
                    <!-- timeline time label -->
                    @foreach($date as $key => $tgl)
                        <li class="time-label">
                           <span class="bg-aqua">
                            {{$date[$key]->date}}
                          </span>
                        </li>
                        @foreach($data as $index => $dt)

                            @if ($date[$key]->date == $data[$index]->date)
                                @if ($data[$index]->action == "Activated Member")
                                    <li>
                                        <i class="fa fa-check bg-green"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{Carbon\Carbon::parse($data[$index]->created_at)->diffForHumans()}}</span>

                                            <h3 class="timeline-header"><a href="#">{{$data[$index]->user}}</a> {{$data[$index]->title}}</h3>

                                            <div class="timeline-body">
                                                {{$data[$index]->note}}
                                            </div>
                                            <div class="timeline-footer">
                                                <span class="time text-light-blue"><i class="fa fa-clock-o"></i> {{$data[$index]->tgl}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @elseif ($data[$index]->action == "Nonactivated Member")
                                    <li>
                                        <i class="fa fa-times bg-red"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{Carbon\Carbon::parse($data[$index]->created_at)->diffForHumans()}}</span>

                                            <h3 class="timeline-header"><a href="#">{{$data[$index]->user}}</a> {{$data[$index]->title}}</h3>

                                            <div class="timeline-body">
                                                <span class="label label-info">{{$data[$index]->oldnote}}</span>
                                                {{$data[$index]->note}}
                                            </div>
                                            <div class="timeline-footer">
                                                <span class="time text-light-blue"><i class="fa fa-clock-o"></i> {{$data[$index]->tgl}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @elseif ($data[$index]->action == "Login")
                                    <li>
                                        <i class="fa fa-sign-in bg-green"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{Carbon\Carbon::parse($data[$index]->created_at)->diffForHumans()}}</span>

                                            <h3 class="timeline-header"><a href="#">{{$data[$index]->user}}</a> {{$data[$index]->title}}</h3>

                                            <div class="timeline-body">
                                                {{$data[$index]->note}}
                                            </div>
                                            <div class="timeline-footer">
                                                <span class="time text-light-blue"><i class="fa fa-clock-o"></i> {{$data[$index]->tgl}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @elseif ($data[$index]->action == "Logout")
                                    <li>
                                        <i class="fa fa-sign-out bg-red"></i>

                                        <div class="timeline-item">
                                            <span class="time"><i class="fa fa-clock-o"></i> {{Carbon\Carbon::parse($data[$index]->created_at)->diffForHumans()}}</span>

                                            <h3 class="timeline-header"><a href="#">{{$data[$index]->user}}</a> {{$data[$index]->title}}</h3>

                                            <div class="timeline-body">
                                                {{$data[$index]->note}}
                                            </div>
                                            <div class="timeline-footer">
                                                <span class="time text-light-blue"><i class="fa fa-clock-o"></i> {{$data[$index]->tgl}}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endif
                        @endforeach
                    @endforeach

                    <li>
                        <i class="fa fa-clock-o bg-gray"></i>
                    </li>
                </ul>
            @endif
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- /.row -->

</section>

<script type="text/javascript">
    $(function () {
        $('#tanggal').on('change', function(e){
            $("#result").find("p").remove();
            $("#result").find("ul").remove();
            var row = '';
            var date = $(this).val();

            if (date == "") {
                date = "null"
            }

            $.ajax({
                url: baseUrl+'/log-aktivitas/filter/'+date,
                dataType: 'json',
            }).done(function (results){
                if (results.tanggal.length == 0) {
                    row = '<p class="text-center">Tidak ada log kegiatan</p>';
                } else {
                    row = '<ul class="timeline">';
                    results.tanggal.forEach(function(element) {
                        row += '<li class="time-label">\n' +
                                '<span class="bg-aqua">\n' +
                                ''+element.date+'\n' +
                                '</span>\n' +
                                '</li>';
                        
                        results.data.forEach(function(dt){
                            if (element.date == dt.date) {
                                if (dt.action == "Create") {
                                    if(dt.title == "membuat kas masuk") {
                                        row += '<li>\n' +
                                            '<i class="fa fa-money bg-green"></i>\n' +
                                            '\n' +
                                            '<div class="timeline-item">\n' +
                                            '<span class="time"><i class="fa fa-clock-o"></i>'+dt.times+'</span>\n' +
                                            '\n' +
                                            '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                            '\n' +
                                            '<div class="timeline-body">\n' +
                                            ''+dt.note+'\n' +
                                            '</div>\n' +
                                            '<div class="timeline-footer">\n' +
                                            '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                            '</div>\n' +
                                            '</div>\n' +
                                            '</li>';
                                    } else if (dt.title == "membuat kas keluar") {
                                        row += '<li>\n' +
                                            '<i class="fa fa-money bg-red"></i>\n' +
                                            '\n' +
                                            '<div class="timeline-item">\n' +
                                            '<span class="time"><i class="fa fa-clock-o"></i>'+dt.times+'</span>\n' +
                                            '\n' +
                                            '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                            '\n' +
                                            '<div class="timeline-body">\n' +
                                            ''+dt.note+'\n' +
                                            '</div>\n' +
                                            '<div class="timeline-footer">\n' +
                                            '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                            '</div>\n' +
                                            '</div>\n' +
                                            '</li>';
                                    }
                                } else if (dt.action == "Update") {
                                    row += '<li>\n' +
                                        '<i class="fa fa-money bg-yellow"></i>\n' +
                                        '\n' +
                                        '<div class="timeline-item">\n' +
                                        '<span class="time"><i class="fa fa-clock-o"></i>'+dt.times+'</span>\n' +
                                        '\n' +
                                        '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                        '\n' +
                                        '<div class="timeline-body">\n' +
                                        '<span class="label label-info">'+dt.oldnote+'</span>\n' +
                                        ''+dt.note+'\n' +
                                        '</div>\n' +
                                        '<div class="timeline-footer">\n' +
                                        '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                        '</div>\n' +
                                        '</div>\n' +
                                        '</li>';
                                } else if (dt.action == "Delete") {
                                    row += '<li>\n' +
                                        '<i class="fa fa-money bg-red"></i>\n' +
                                        '\n' +
                                        '<div class="timeline-item">\n' +
                                        '<span class="time"><i class="fa fa-clock-o"></i> '+dt.times+'</span>\n' +
                                        '\n' +
                                        '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                        '\n' +
                                        '<div class="timeline-body">\n' +
                                        ''+dt.note+'\n' +
                                        '</div>\n' +
                                        '<div class="timeline-footer">\n' +
                                        '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                        '</div>\n' +
                                        '</div>\n' +
                                        '</li>';
                                } else if (dt.action == "Login") {
                                    row += '<li>\n' +
                                        '<i class="fa fa-sign-in bg-green"></i>\n' +
                                        '\n' +
                                        '<div class="timeline-item">\n' +
                                        '<span class="time"><i class="fa fa-clock-o"></i> '+dt.times+'</span>\n' +
                                        '\n' +
                                        '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                        '\n' +
                                        '<div class="timeline-body">\n' +
                                        ''+dt.note+'\n' +
                                        '</div>\n' +
                                        '<div class="timeline-footer">\n' +
                                        '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                        '</div>\n' +
                                        '</div>\n' +
                                        '</li>';
                                } else if (dt.action == "Logout") {
                                    row += '<li>\n' +
                                        '<i class="fa fa-sign-out bg-red"></i>\n' +
                                        '\n' +
                                        '<div class="timeline-item">\n' +
                                        '<span class="time"><i class="fa fa-clock-o"></i> '+dt.times+'</span>\n' +
                                        '\n' +
                                        '<h3 class="timeline-header"><a href="#">'+dt.user+'</a> '+dt.title+'</h3>\n' +
                                        '\n' +
                                        '<div class="timeline-body">\n' +
                                        ''+dt.note+'\n' +
                                        '</div>\n' +
                                        '<div class="timeline-footer">\n' +
                                        '<span class="time text-light-blue"><i class="fa fa-clock-o"></i> '+dt.tgl+'</span>\n' +
                                        '</div>\n' +
                                        '</div>\n' +
                                        '</li>';
                                }
                            }
                        })

                    });
                    row += '<li>\n' +
                        '<i class="fa fa-clock-o bg-gray"></i>\n' +
                        '</li>\n' +
                        '</ul>';
                }

                $("#result").append(row);

            })
        })
    })
</script>
@endsection