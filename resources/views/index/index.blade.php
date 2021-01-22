@extends('layouts.indexLayout.indexContent')
@section('title', $identitas->deskripsi)

@section('content')
<!-- Services -->
<div id="services" class="cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Layanan</h2>
            </div> 
        </div> 
        <div class="row">
            <div class="col-lg-12">

                
                
            </div>
        </div>
    </div>
</div>

@php($row=1)
@foreach($layanan as $key => $value)
@if($row == 1)
<!-- Details 1 -->
<div class="basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>{{$value->title}}</h2>
                    {!! $value->description !!}
                    <a class="btn-solid-reg popup-with-move-anim" href="{{url('/registrasi')}}">REGISTRASI</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="image-container">
                    <img class="img-fluid" src="{{ asset('images/index/'.$value->image) }}" alt="alternative">
                </div>
            </div>
        </div>
    </div>
</div>
@php($row=2)
@elseif($row == 2)
<!-- Details 2 -->
<div class="basic-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="image-container">
                    <img class="img-fluid" src="{{ asset('images/index/'.$value->image) }}" alt="alternative">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>{{$value->title}}</h2>
                    {!! $value->description !!}
                    <a class="btn-solid-reg popup-with-move-anim" href="{{url('/registrasi')}}">REGISTRASI</a>
                </div>
            </div>
        </div>
    </div>
</div>
@php($row=1)
@endif
@endforeach

<!-- About -->
<div id="syarat" class="">
    <div class="ex-basic-2 container" style="">
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-lg-12 text-center">
                <h2>{{$syarat->title}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="text-container">
                    {!! $syarat->description !!}
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-lg-12 text-center">
                <h2>{{$kebijakan->title}}</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="text-container">
                    {!! $kebijakan->description !!}
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Contact -->
<div id="contact" class="form-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Pesan</h2>
                <ul class="list-unstyled li-space-lg">
                    <li class="address">Kirimkan pesan/pertanyaan, kritik, saran Anda kepada Kami.</li>
                    <!-- <li class="address"><i>Contact Support : support@keuanganku.my.id</i></li> -->
                </ul>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if(Session::has('flash_message_error'))
                <div class="alert alert-warning alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{!! session('flash_message_error') !!}</strong>
                </div>
                @elseif(Session::has('flash_message_success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <strong>{!! session('flash_message_success') !!}</strong>
                </div>
                @endif 

                <form id="contactForm" method="post" data-toggle="validator" data-focus="false" action="{{url('/pesan')}}">{{csrf_field()}}
                    <div class="form-group">
                        <input type="text" class="form-control-input @if(Session::has('cname')) notEmpty @endif" id="cname" name="nama" value="@if(Session::has('cname')){{session('cname')}}@endif" required>
                        <label class="label-control" for="cname">Nama</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control-input @if(Session::has('cemail')) notEmpty @endif" id="cemail" name="email" value="@if(Session::has('cemail')){{session('cemail')}}@endif" required>
                        <label class="label-control" for="cemail">Email</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control-input @if(Session::has('csubject')) notEmpty @endif" id="csubject" name="subyek" value="@if(Session::has('csubject')){{session('csubject')}}@endif" required>
                        <label class="label-control" for="csubject">Subyek</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control-textarea @if(Session::has('cmessage')) notEmpty @endif" id="cmessage" name="pesan" required>@if(Session::has('cmessage')){{session('cmessage')}}@endif</textarea>
                        <label class="label-control" for="cmessage">Pesan Anda...</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <div class="captcha">
                           <span>{!! captcha_img() !!}</span>
                           <button type="button" class="btn btn-success"><i class="fa fa-sync-alt" id="refresh"></i></button>
                        </div>
                        <input type="text" class="form-control" id="ccaptcha" name="ccaptcha" placeholder="Captcha" autocomplete="off" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="form-control-submit-button">KIRIM PESAN</button>
                    </div>
                    <div class="form-message">
                        <div id="cmsgSubmit" class="h3 text-center hidden"></div>
                    </div>
                </form>

            </div>
        </div> 
    </div>
</div>

<!-- <div id="donasi">
    <div class="basic-4 container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Donasi</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 text-center">
                
                <h4><strong>BANK BRI</strong></h4>
                <h4>NO. REK : <span class="norek">0508-01-010795-50-4</span></h4>
                <h6>A.N. AHMAD SYAHRUL YUSUF</h6>

            </div>
        </div>
    </div>
</div> -->
@endsection