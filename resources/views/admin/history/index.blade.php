@extends('layouts.adminLayout.adminContent')
@section('title', 'Log Kegiatan')
<?php
\Carbon\Carbon::setLocale('id');
?>
@section('content')

    <section class="content-header">
        <h1>
            Timeline
            <small>Log kegiatan</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Log kegiatan</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- row -->
        <div class="row">
            <div class="col-md-12">
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
                                    @if ($data[$index]->action == "Create")
                                        <li>
                                            <i class="fa fa-money bg-green"></i>

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
                                    @elseif ($data[$index]->action == "Update")
                                        <li>
                                            <i class="fa fa-money bg-yellow"></i>

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
                                    @elseif ($data[$index]->action == "Delete")
                                        <li>
                                            <i class="fa fa-money bg-red"></i>

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

    <script src="{{ asset('public/js/adminLTE/main.js') }}"></script>
    <!-- jQuery 3 -->
    <script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>

    <script type="text/javascript">

    </script>
@endsection