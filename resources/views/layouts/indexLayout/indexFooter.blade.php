<!-- Footer -->
<div class="footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="footer-col">
                    <h4>Tentang {{$identitas->title}}</h4>
                    <p>{{$identitas->deskripsi}}</p>
                </div>
            </div> 
            <div class="col-md-4">
                <div class="footer-col middle">
                    <h4>Important Links</h4>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body"><a class="turquoise" href="{{url('/')}}/#syarat">Syarat & Ketentuan</a></div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i>
                            <div class="media-body"><a class="turquoise" href="{{url('/')}}/#syarat">Kebijakan Privasi</a></div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="footer-col last">
                    <h4>Social Media</h4>
                    <span class="fa-stack">
                        <a href="https://www.facebook.com/syahrul.yusuf.99" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-facebook-f fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="https://www.instagram.com/syahrulyusuf97/" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-instagram fa-stack-1x"></i>
                        </a>
                    </span>
                    <span class="fa-stack">
                        <a href="https://www.linkedin.com/in/syahrul-yusuf-19379a16b/" target="_blank">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fab fa-linkedin-in fa-stack-1x"></i>
                        </a>
                    </span>
                </div> 
            </div>
        </div>
    </div>
</div>


<!-- Copyright -->
<div class="copyright">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <p class="p-small">Copyright © <a href="{{url('/')}}">KeuanganKu</a> - {{\Carbon\Carbon::now()->format('Y')}} All rights reserved. </p>
            </div>
        </div>
    </div>
</div>