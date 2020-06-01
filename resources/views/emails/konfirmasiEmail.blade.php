<!DOCTYPE html>

<html>

<head>

	<title>Konfirmasi Email</title>

</head>

<body>
	<center><strong><h1>KeuanganKu.info</h1></strong></center>

	<h1>Konfirmasi Email</h1>

	<p>Kami telah menerima permintaan pendaftaran pada aplikasi <a href="{{url('/')}}">KeuanganKu.info</a> dengan menggunakan email ini.</p>

	<p>Klik link dibawah ini untuk mengkonfirmasi email & mengaktifkan akun Anda. Link aktif sampai dengan {{date('d-m-Y H:i:s', strtotime($code_expired))}}</p>

	{{$link}}

	<strong><p>Abaikan pesan ini jika Anda merasa tidak meminta pendaftaran pada aplikasi <a href="{{url('/')}}">KeuanganKu.info</a></p></strong>

</body>

</html>