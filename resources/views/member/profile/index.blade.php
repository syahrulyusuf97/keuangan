@extends('layouts.memberLayout.memberContent')
@section('title', 'Profil')
<style type="text/css">
    .img-profile:hover {
        border-color: #00C0EF;
        cursor: pointer;
    }
</style>
@section('content')

<section class="content-header">
    <h1>
        Dashboard
        <small>Profil</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard </a></li>
        <li class="active">Profil</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            @if(Session::has('flash_message_error'))
                <div class="alert alert-error alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{!! session('flash_message_error') !!}</strong>
                </div>
            @endif
            @if(Session::has('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>{!! session('flash_message_success') !!}</strong>
                </div>
            @endif
            <div class="col-md-12 box box-primary">
                <div class="col-md-4 box-body box-profile">
                    @if(auth()->user()->img == "")
                      <img src="{{ asset('public/images/default.jpg') }}" alt="Foto profil" title="Sunting foto profil" class="profile-user-img img-responsive img-circle img-profile" onclick="editFoto()">
                    @else
                      <img src="{{ asset('public/images/'. auth()->user()->img) }}" alt="Foto profil" title="Sunting foto profil" class="profile-user-img img-responsive img-circle img-profile" onclick="editFoto()">
                    @endif
                    
                    <h3 class="profile-username text-center text-capitalize">{{ auth()->user()->name }}</h3>
                    <p class="text-muted text-center text-capitalize">@if(auth()->user()->level == 1) Admin @elseif(auth()->user()->level == 2) Member @endif KeuanganKu</p>
                    @if(auth()->user()->tempat_lahir != null && auth()->user()->tgl_lahir != null)
                    <p class="text-muted text-center text-capitalize">{!! auth()->user()->tempat_lahir !!}, {!! date('d F Y', strtotime(auth()->user()->tgl_lahir)) !!}</p>
                    @endif
                    @if(auth()->user()->address != null)
                    <p class="text-muted text-center text-capitalize">{!! auth()->user()->address !!}</p>
                    @endif
                </div>
                <div class="col-md-6 box-body box-profile">
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item"><b>Nama</b> <a class="pull-right">{!! auth()->user()->name !!}</a></li>
                        <li class="list-group-item"><b>Email</b> <a class="pull-right">{!! auth()->user()->email !!}</a></li>
                        <li class="list-group-item"><b>Username</b> <a class="pull-right">{!! auth()->user()->username !!}</a></li>
                        <li class="list-group-item"><b>Last Login</b> <a class="pull-right">{!! date('d F Y H:m:s', strtotime(auth()->user()->login)) !!}</a></li>
                        @if(auth()->user()->logout != null)
                        <li class="list-group-item"><b>Last Logout</b> <a class="pull-right">{!! date('d F Y H:m:s', strtotime(auth()->user()->logout)) !!}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="col-md-12 box box-primary">
                <div class="box-body">
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="isn"></i>
                        <a href="javascript:void(0)" onclick="visibleNama()">Sunting Nama Pengguna</a>
                    </strong>
                    <div style="display: none" id="edit_nama">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-nama')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Nama Pengguna</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama pengguna..." value="{!! auth()->user()->name !!}">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                    <hr>
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="ise"></i>
                        <a href="javascript:void(0)" onclick="visibleEmail()">Sunting Email</a>
                    </strong>
                    <div style="display: none"  id="edit_email">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-email')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Email</label>

                                        <div class="col-sm-6">
                                            <input type="email" class="form-control" id="email" name="email" placeholder="Email pengguna..." value="{!! auth()->user()->email !!}">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                    <hr>
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="isu"></i>
                        <a href="javascript:void(0)" onclick="visibleUsername()">Sunting Username</a>
                    </strong>
                    <div style="display: none"  id="edit_username">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-username')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Username</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username pengguna..." value="{!! auth()->user()->username !!}">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                    <hr>
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="isp"></i>
                        <a href="javascript:void(0)" onclick="visiblePassword()">Sunting Kata Sandi</a>
                    </strong>
                    <div style="display: none" id="edit_password">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-password')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Kata Sandi Lama</label>

                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Kata sandi lama...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Kata Sandi Baru</label>

                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Kata sandi baru...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Konfirmasi Kata Sandi</label>

                                        <div class="col-sm-6">
                                            <input type="password" class="form-control" id="vernewPassword" name="vernewPassword" placeholder="Konfirmasi kata sandi baru...">
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                    <hr>
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="ist"></i>
                        <a href="javascript:void(0)" onclick="visibleTtl()">Sunting Tempat, Tanggal Lahir</a>
                    </strong>
                    <div style="display: none" id="edit_ttl">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-ttl')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Tempat Lahir</label>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" id="tempat_lahir" name="tempat" placeholder="Tempat lahir..." value="{!! auth()->user()->tempat_lahir !!}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Tanggal Lahir</label>

                                        <div class="col-sm-6">
                                            <select id="dobday" class="form-control col-sm-2" style="width: 30%;" name="tanggal"></select>
                                            <select id="dobmonth" class="form-control col-sm-4" style="width: 30%; margin-left: 10px" name="bulan"></select>
                                            <select id="dobyear" class="form-control col-sm-3" style="width: 30%; margin-left: 10px" name="tahun"></select>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                    <hr>
                    <strong>
                        <i class="fa fa-pencil-square-o margin-r-5" id="isa"></i>
                        <a href="javascript:void(0)" onclick="visibleAlamat()">Sunting Alamat</a>
                    </strong>
                    <div style="display: none"  id="edit_alamat">
                        <strong>
                            <form class="form-horizontal" method="post" action="{{url('/profil/update-alamat')}}">{{ csrf_field() }}
                                <div class="box-body">
                                    <div class="form-group">
                                        <label for="title" class="col-sm-2 control-label">Alamat</label>

                                        <div class="col-sm-6">
                                            <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control">{!! auth()->user()->address !!}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-info pull-right">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-primary fade" id="foto-profil">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Sunting foto profil</h4>
                </div>
                <form method="post" action="{{url('/profil/update-foto')}}" enctype="multipart/form-data">{{csrf_field()}}
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                @if(auth()->user()->img == "")
                                  <img src="{{ asset('public/images/default.jpg') }}" alt="Foto profil" class="profile-user-img img-responsive img-circle">
                                @else
                                  <img src="{{ asset('public/images/'. auth()->user()->img) }}" alt="Foto profil" class="profile-user-img img-responsive img-circle">
                                @endif
                                <input type="hidden" name="current_img" value="{{auth()->user()->img}}">
                            </div>
                            <div class="col-md-4">
                                <div style="height: 100px; padding-top: 40px;">
                                    <input type="file" name="foto" id="foto" accept="image/*" class="btn btn-outline form-control"  onchange="loadFile(event)">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <img src="{{ asset('public/images/default.jpg') }}" id="newimage" alt="Foto profil pengganti" title="Sunting foto profil" class="profile-user-img img-responsive img-circle">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline pull-left" data-dismiss="modal">Tutup</button>
                        <button type="submit" onclick="return confirm('Apakah Anda yakin akan merubah data ini?')" class="btn btn-outline">Simpan perubahan</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</section>

<!-- jQuery 3 -->
<script src="{{ asset('public/js/jQuery/jquery.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $.dobPicker({
            // Selectopr IDs
            daySelector: '#dobday',
            monthSelector: '#dobmonth',
            yearSelector: '#dobyear',

            // Default option values
            dayDefault: 'Tanggal',
            monthDefault: 'Bulan',
            yearDefault: 'Tahun',

            // Minimum age
            minimumAge: 0,

            // Maximum age
            maximumAge: 80
        });

        $('#dobday').val('{{ $day }}');
        $('#dobmonth').val('{{ $month }}');
        $('#dobyear').val('{{ $year }}');

    })

    var displayNama = false, displayEmail = false, displayUsername = false, displayPassword = false, displayTtl = false, displayAlamat = false;
    function visibleNama() {
        if (displayNama == false){
            $("#edit_nama").show('slow');
            displayNama = true;
            $("#isn").addClass("text-blue");
        } else {
            $("#edit_nama").hide('slow');
            displayNama = false;
            $("#isn").removeClass("text-blue");
        }
    }

    function visibleEmail() {
        if (displayEmail == false){
            $("#edit_email").show('slow');
            displayEmail = true;
            $("#ise").addClass("text-blue");
        } else {
            $("#edit_email").hide('slow');
            displayEmail = false;
            $("#ise").removeClass("text-blue");
        }
    }

    function visibleUsername() {
        if (displayUsername == false){
            $("#edit_username").show('slow');
            displayUsername = true;
            $("#isu").addClass("text-blue");
        } else {
            $("#edit_username").hide('slow');
            displayUsername = false;
            $("#isu").removeClass("text-blue");
        }
    }

    function visiblePassword() {
        if (displayPassword == false){
            $("#edit_password").show('slow');
            displayPassword = true;
            $("#isp").addClass("text-blue");
        } else {
            $("#edit_password").hide('slow');
            displayPassword = false;
            $("#isp").removeClass("text-blue");
        }
    }

    function visibleTtl() {
        if (displayTtl == false){
            $("#edit_ttl").show('slow');
            displayTtl = true;
            $("#ist").addClass("text-blue");
        } else {
            $("#edit_ttl").hide('slow');
            displayTtl = false;
            $("#ist").removeClass("text-blue");
        }
    }

    function visibleAlamat() {
        if (displayAlamat == false){
            $("#edit_alamat").show('slow');
            displayAlamat = true;
            $("#isa").addClass("text-blue");
        } else {
            $("#edit_alamat").hide('slow');
            displayAlamat = false;
            $("#isa").removeClass("text-blue");
        }
    }

    function editFoto() {
        $("#foto-profil").modal('show');
    }

    function loadFile(event)
    {
        $("#newimage").attr("src", URL.createObjectURL(event.target.files[0]));
    }
</script>
@endsection