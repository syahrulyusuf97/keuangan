@extends('layouts.indexLayout.indexContent')
@section('title', $data->title)

@section('content')
<!-- Breadcrumbs -->
<div class="ex-basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumbs">
                    <a href="{{url('/')}}">Beranda</a><i class="fa fa-angle-double-right"></i><span>Artikel</span>
                </div> <!-- end of breadcrumbs -->
            </div> <!-- end of col -->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of ex-basic-1 -->
<!-- end of breadcrumbs -->

<!-- Privacy Content -->
<div class="ex-basic-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-container">
                    <div class="row">
                        <div class="col-md-8 article-content">
                        	<h1>{{$data->title}}</h1>
                            {!! $data->description !!}
                        </div> <!-- end of col -->

                        <div class="col-md-4 other-article">
                        	<h1>Artikel Lainnya</h1>
                            <ul class="list-unstyled li-space-lg indent">
                                @if(count($article_other) > 0)
                            		@foreach($article_other as $key => $value)
                            			<li class="media">
		                                	<div class="box-border">
				                        		<a href="{{url('/article/'.$value->slug)}}">
				                        			<h3>{{$value->title}}</h3>
				                        		    <p>{!! str_limit(implode("", explode("</p>", implode("", explode("<p>", strip_tags($value->description, '<p>'))))), 100, '...') !!}</p>
				                        		    <span>Dibuat tanggal : {{$value->created_at}}</span>
					                        	</a>
				                        	</div>
		                                </li>
                            		@endforeach
                            	@else
			                        <div class="alert alert-success alert-block text-center">
					                    <strong>Belum ada artikel untuk Anda.</strong>
					                </div>
                            	@endif
                            </ul>
                        </div> <!-- end of col -->
                    </div> <!-- end of row -->
                </div> <!-- end of text-container-->
                <a class="btn-outline-reg back" href="{{url('/article')}}">KEMBALI</a>
            </div> <!-- end of col-->
        </div> <!-- end of row -->
    </div> <!-- end of container -->
</div> <!-- end of ex-basic-2 -->
<!-- end of privacy content -->
@endsection