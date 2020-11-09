<!-- App Header -->
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="#" class="headerButton" data-toggle="modal" data-target="#sidebarPanel">
            <ion-icon name="menu-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        <!-- <img src="{{ asset('public/images/icon/logo.png') }}" alt="logo" class="logo"> -->
        <span class="logo">KeuanganKu</span>
    </div>
    <div class="right">
        <!-- <a href="app-notifications.html" class="headerButton">
            <ion-icon class="icon" name="notifications-outline"></ion-icon>
            <span class="badge badge-danger">4</span>
        </a> -->
        <a href="{{url('/profil')}}" class="headerButton">
            @if(auth()->user()->img == "")
            <img src="{{ asset('public/images/default.jpg') }}" alt="image" class="imaged w32">
            @else
            <img src="{{ asset('public/images/'. auth()->user()->img) }}" alt="image" class="imaged w32">
            @endif
            <!-- <span class="badge badge-danger">6</span> -->
        </a>
    </div>
</div>
<!-- * App Header -->