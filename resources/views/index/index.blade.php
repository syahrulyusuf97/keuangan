@extends('layouts.indexLayout.indexContent')
@section('title', 'Beranda')

@section('content')
<!-- Customers -->
<!-- <div class="slider-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h5>Trusted By</h5>
                
                <div class="slider-container">
                    <div class="swiper-container image-slider">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-1.png') }}" alt="alternative">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-2.png') }}" alt="alternative">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-3.png') }}" alt="alternative">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-4.png') }}" alt="alternative">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-5.png') }}" alt="alternative">
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="image-container">
                                    <img class="img-responsive" src="{{ asset('public/images/index/customer-logo-6.png') }}" alt="alternative">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> -->


<!-- Services -->
<div id="services" class="cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Layanan</h2>
                <!-- <p class="p-heading p-large">We serve small and medium sized companies in all tech related industries with high quality growth services which are presented below</p> -->
            </div> 
        </div> 
        <div class="row">
            <div class="col-lg-12">

                <!-- <div class="card">
                    <img class="card-image" src="{{ asset('public/images/index/services-icon-1.svg') }}" alt="alternative">
                    <div class="card-body">
                        <h4 class="card-title">Market Analysis</h4>
                        <p>Our team of enthusiastic marketers will analyse and evaluate how your company stacks against the closest competitors</p>
                    </div>
                </div>

                <div class="card">
                    <img class="card-image" src="{{ asset('public/images/index/services-icon-2.svg') }}" alt="alternative">
                    <div class="card-body">
                        <h4 class="card-title">Opportunity Scan</h4>
                        <p>Once the market analysis process is completed our staff will search for opportunities that are in reach</p>
                    </div>
                </div>

                <div class="card">
                    <img class="card-image" src="{{ asset('public/images/index/services-icon-3.svg') }}" alt="alternative">
                    <div class="card-body">
                        <h4 class="card-title">Action Plan</h4>
                        <p>With all the information in place you will be presented with an action plan that your company needs to follow</p>
                    </div>
                </div> -->
                
            </div>
        </div>
    </div>
</div>


<!-- Details 1 -->
<div class="basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>Transaksi KAS & BANK</h2>
                    <p>Kami menyediakan fitur untuk melakukan pencatatan teransaksi Kas/Bank. Anda dapat membuat catatan dalam setiap transaksi yang dilakukan baik pengeluaran maupun pemasukan.</p>
                    <a class="btn-solid-reg popup-with-move-anim" href="{{url('/registrasi')}}">REGISTRASI</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="image-container">
                    <img class="img-fluid" src="{{ asset('public/images/index/details-1-office-worker.svg') }}" alt="alternative">
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Details 2 -->
<div class="basic-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="image-container">
                    <img class="img-fluid" src="{{ asset('public/images/index/details-2-office-team-work.svg') }}" alt="alternative">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>Laporan Keuangan</h2>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-check"></i>
                            <div class="media-body">Laporan Arus Kas</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i>
                            <div class="media-body">Laporan keuangan dalam bentuk grafik</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-check"></i>
                            <div class="media-body">Riwayat transaksi Kas/Bank</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg popup-with-move-anim" href="{{url('/registrasi')}}">REGISTRASI</a>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- About -->
<div id="about" class="">
    <div class="basic-4 container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Tentang Kami</h2>
                <p class="p-heading p-large">Tim pengembang kami, Business Developer, Online Marketer, Software Engineer & Product Manager.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                
                <div class="team-member">
                    <div class="image-wrapper">
                        <img class="img-fluid" src="{{ asset('public/images/index/business-developer.jpg') }}" alt="alternative">
                    </div>
                    <p class="p-large"><strong>Ahmad</strong></p>
                    <p class="job-title">Business Developer</p>
                    <span class="social-icons">
                        <span class="fa-stack">
                            <a href="https://www.facebook.com/syahrul.yusuf.99" target="_blank">
                                <i class="fas fa-circle fa-stack-2x facebook"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://twitter.com/syahrulyusuf14" target="_blank">
                                <i class="fas fa-circle fa-stack-2x twitter"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.instagram.com/syahrulyusuf97/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x instagram"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.linkedin.com/in/syahrul-yusuf-19379a16b/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x linkedin"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                    </span>
                </div>

                <div class="team-member">
                    <div class="image-wrapper">
                        <img class="img-fluid" src="{{ asset('public/images/index/oline-marketer.jpg') }}" alt="alternative">
                    </div> 
                    <p class="p-large"><strong>Syahrul</strong></p>
                    <p class="job-title">Online Marketer</p>
                    <span class="social-icons">
                        <span class="fa-stack">
                            <a href="https://www.facebook.com/syahrul.yusuf.99" target="_blank">
                                <i class="fas fa-circle fa-stack-2x facebook"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://twitter.com/syahrulyusuf14" target="_blank">
                                <i class="fas fa-circle fa-stack-2x twitter"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.instagram.com/syahrulyusuf97/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x instagram"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.linkedin.com/in/syahrul-yusuf-19379a16b/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x linkedin"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                    </span>
                </div>

                <div class="team-member">
                    <div class="image-wrapper">
                        <img class="img-fluid" src="{{ asset('public/images/index/software-engineer.jpg') }}" alt="alternative">
                    </div> 
                    <p class="p-large"><strong>Yusuf</strong></p>
                    <p class="job-title">Software Engineer</p>
                    <span class="social-icons">
                        <span class="fa-stack">
                            <a href="https://www.facebook.com/syahrul.yusuf.99" target="_blank">
                                <i class="fas fa-circle fa-stack-2x facebook"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://twitter.com/syahrulyusuf14" target="_blank">
                                <i class="fas fa-circle fa-stack-2x twitter"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.instagram.com/syahrulyusuf97/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x instagram"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.linkedin.com/in/syahrul-yusuf-19379a16b/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x linkedin"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                    </span>
                </div> 

                <!-- Team Member -->
                <div class="team-member">
                    <div class="image-wrapper">
                        <img class="img-fluid" src="{{ asset('public/images/index/product-manager.jpg') }}" alt="alternative">
                    </div> 
                    <p class="p-large"><strong>Ahmad Syahrul Yusuf</strong></p>
                    <p class="job-title">Product Manager</p>
                    <span class="social-icons">
                        <span class="fa-stack">
                            <a href="https://www.facebook.com/syahrul.yusuf.99" target="_blank">
                                <i class="fas fa-circle fa-stack-2x facebook"></i>
                                <i class="fab fa-facebook-f fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://twitter.com/syahrulyusuf14" target="_blank">
                                <i class="fas fa-circle fa-stack-2x twitter"></i>
                                <i class="fab fa-twitter fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.instagram.com/syahrulyusuf97/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x instagram"></i>
                                <i class="fab fa-instagram fa-stack-1x"></i>
                            </a>
                        </span>
                        <span class="fa-stack">
                            <a href="https://www.linkedin.com/in/syahrul-yusuf-19379a16b/" target="_blank">
                                <i class="fas fa-circle fa-stack-2x linkedin"></i>
                                <i class="fab fa-linkedin-in fa-stack-1x"></i>
                            </a>
                        </span>
                    </span> 
                </div>

            </div>
        </div>
    </div>
    <div class="ex-basic-2 container" style="padding-top: 10px;">
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-lg-12 text-center">
                <h2>Syarat & Ketentuan</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="text-container">
                    <h3>SYARAT DAN KETENTUAN PENGGUNA APLIKASI</h3>
                    <p>Syarat & ketentuan yang ditetapkan di bawah ini mengatur pemakaian aplikasi yang ditawarkan oleh Kami terkait penggunaan aplikasi KeuanganKu. Pengguna disarankan membaca dengan seksama karena dapat berdampak kepada hak dan kewajiban Pengguna.</p>
                    <p>Dengan mendaftar dan/atau menggunakan aplikasi KeuanganKu, maka pengguna dianggap telah membaca, mengerti, memahami dan menyetujui semua isi dalam Syarat & ketentuan. Jika pengguna tidak menyetujui salah satu, sebagian, atau seluruh isi Syarat & ketentuan, maka pengguna tidak diperkenankan menggunakan layanan aplikasi KeuanganKu.</p>
                    <ul class="list-unstyled li-space-lg indent">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">“Akun” mengacu pada Akun yang dibuat oleh Pengguna segera setelah Pengguna menyelesaikan proses pendaftaran di aplikasi KeuanganKu, yang berisi informasi data pribadi milik Pengguna.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">“Syarat dan Ketentuan” mengacu pada Syarat dan Ketentuan untuk penggunaan layanan yang tersedia di KeuanganKu serta produk dan layanan lainnya yang mungkin akan tersedia di masa depan.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">“Layanan” berarti setiap layanan yang ditawarkan oleh ami kepada Pengguna melalui aplikasi KauanganKu, yang mana penggunaannya diatur oleh Syarat & Ketentuan Pengguna ini.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Kami berhak untuk segera membekukan Akun Anda jika kami memiliki alasan untuk mempercayai bahwa Akun Anda telah digunakan oleh orang yang tidak bertanggung jawab.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Kami berhak untuk mulai mengenakan biaya kepada para Pengguna Layanan Kami di masa yang akan datang. Syarat dan Ketentuan terkait pengenaan biaya terhadap Layanan Kami di masa mendatang akan dicantumkan pada perubahan dari Syarat & Ketentuan Pengguna ini.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Aplikasi ini merupakan aplikasi perangkat lunak yang berfungsi sebagai sarana untuk mengelola keuangan pribadi.</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-lg-12 text-center">
                <h2>Kebijakan Privasi</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-10 offset-lg-1">
                <div class="text-container">
                    <h3>TINJAUAN</h3>
                    <p>Kami menyadari bahwa privasi di World Wide Web merupakan hal penting bagi Anda. Kami menggunakan informasi untuk memahami kebutuhan Anda dengan lebih baik dan meningkatkan pengalaman Anda pada website kami.</p>
                    <p>Dalam kebijakan privasi ini, kami menyatakan beberapa praktek yang kami pakai untuk memanfaatkan dan menjelaskan informasi yang diperoleh dari website kami.</p>
                    <p>Informasi yang kami minta, seperti nama, alamat email akan dipakai untuk memenuhi permintaan Anda atas informasi dan bantuan dalam layanan yang kami berikan. Jika Anda ingin menerima update, kami akan mengirim informasi kepada Anda secara berkala mengenai produk dan layanan lainnya yang kami rasa menarik bagi Anda.</p>
                    <p>Kebijakan Privasi ini hanya mencakup informasi yang diperoleh melalui website ini dan tidak mencakup informasi yang kemungkinan diperoleh melalui peranti lunak yang diunduh dari situs atau yang diperoleh dari situs-situs lainnya yang ditautkan ke pada situs ini.</p>
                    <h4>Informasi yang Kami Kumpulkan</h4>
                    <ul class="list-unstyled li-space-lg indent">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Kami menyimpan informasi dari pendaftaran, log files dan/atau pihak ketiga untuk menciptakan profil pengguna kami.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Data Pribadi bisa termasuk nama, alamat, nomor telepon, atau alamat e-mail Anda. Kami juga mengumpulkan informasi yang tak dapat diidentifikasi secara pribadi, yang mungkin tertaut pada Data Pribadi Anda, termasuk alamat IP Anda, serta preferensi pencarian terkait pada pencarian spesifik.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Kami menggunakan informasi ini untuk menghubungi Anda mengenai layanan di situs kami untuk mengetahui ketertarikan Anda. Umumnya, pengguna memiliki opsi untuk memberikan informasi, identitas unik (seperti, nama pengguna dan kata sandi), tapi untuk mendukungnya kami dapat memberikan pengalaman yang lebih pribadi pada situs kami.</div>
                        </li>
                    </ul>
                    <h4>Layanan Pelanggan</h4>
                    <ul class="list-unstyled li-space-lg indent">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">Kami berhubungan dengan pengguna secara teratur untuk menyajikan layanan yang diperlukan dan mengenai persoalan yang berkaitan dengan akun mereka dan kami membalasnya via email, sesuai dengan keinginan pengguna.</div>
                        </li>
                    </ul>
                    <h4>Berbagi Data Pribadi Anda</h4>
                    <p>Ini adalah contoh dimana kami akan membagi informasi pribadi pengguna:</p>
                    <ul class="list-unstyled li-space-lg indent">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body">KeuanganKu dapat membagikan Data Pribadi Anda dengan pihak ketiga yang kami pekerjakan untuk melakukan layanan atas nama kami, seperti layanan hosting web. Pihak ketiga tersebut juga memiliki kewajiban untuk menjaga keamanan dan kerahasiaan Data Pribadi dan untuk memproses Data Pribadi hanya sesuai dengan instruksi kami.</div>
                        </li>
                    </ul>
                    <h4>Persetujuan Anda</h4>
                    <p>Dengan menggunakan website kami, dengan ini Anda setuju pada pengumpulan dan pemakaian informasi ini sebagaimana ditetapkan dalam kebijakan privasi ini.</p>
                    <h4>Informasi Kontak</h4>
                    <p>Jika pengguna memiliki pertanyaan, kritik atau saran yang berkaitan dengan kebijakan privasi, silahkan kirimkan pesan melalui menu pesan yang ada di aplikasi.</p>
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
                </ul>
            </div> 
        </div>
        <div class="row">
            <div class="col-lg-12">
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
                <form id="contactForm" method="post" data-toggle="validator" data-focus="false" action="{{url('/pesan')}}">{{csrf_field()}}
                    <div class="form-group">
                        <input type="text" class="form-control-input" id="cname" name="nama" required>
                        <label class="label-control" for="cname">Nama</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control-input" id="cemail" name="email" required>
                        <label class="label-control" for="cemail">Email</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control-input" id="csubject" name="subyek" required>
                        <label class="label-control" for="csubject">Subyek</label>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control-textarea" id="cmessage" name="pesan" required></textarea>
                        <label class="label-control" for="cmessage">Pesan Anda...</label>
                        <div class="help-block with-errors"></div>
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

<div id="donasi">
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
</div>
@endsection