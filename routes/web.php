<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('SignController@login');
// });

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');
// Route::get('/createuser', 'CreateUser@store');

//Route::get('/', 'SignController@login');
Route::match(['get', 'post'], '/login', 'SignController@login')->name('login');
Route::match(['get', 'post'], '/registrasi', 'SignController@registrasi')->name('registrasi');
Route::get('/resend_email', 'SignController@resendEmail');
Route::get('/new_email_code', 'SignController@newEmailCode');
Route::get('/konfirmasi/{code}/{id}', 'SignController@konfirmasi');
Route::match(['get', 'post'], '/reset_password', 'SignController@resetPassword');
Route::post('/password_reset', 'SignController@passwordReset');
Route::get('/logout', 'SignController@logout');

// mobile
Route::post('/mobile/login', 'SignController@mobileLogin');
Route::post('/mobile/reset_password', 'SignController@mobileResetPassword');
Route::post('/mobile/password_reset', 'SignController@mobilePasswordReset');
Route::post('/mobile/registrasi', 'SignController@mobileRegistrasi');
// End mobile

Route::group(['namespace' => 'index'], function(){
    Route::get('/', 'IndexController@index');
    Route::get('/article', 'IndexController@article');
    Route::get('/article/{slug}', 'IndexController@articleSlug');
    Route::match(['get', 'post'], '/pesan', 'IndexController@message');
    Route::get('/refreshcaptcha', 'IndexController@refreshCaptcha');
});

Route::group(['middleware'=>['auth']], function(){
    // Member
    Route::group(['namespace' => 'member'], function(){
        // Dashboard
        Route::get('/dashboard', 'DashboardController@dashboard');
        Route::get('/dashboard/detail-saldo/{param}', 'DashboardController@detailSaldo');
        Route::get('/dashboard/riwayat/{parameter}', 'DashboardController@riwayat');
        Route::get('/dashboard/statistik', 'DashboardController@getStatistik');
        // Route::get('/dashboard/grafik-kas', 'DashboardController@grafikKas');
        // Route::get('/dashboard/grafik-bank', 'DashboardController@grafikBank');
        // Route::get('/dashboard/laporan-bulan-lalu-debit-kas', 'DashboardController@chartBulanDebitKas');
        // Route::get('/dashboard/laporan-bulan-lalu-debit-bank', 'DashboardController@chartBulanDebitBank');
        // Route::get('/dashboard/laporan-bulan-lalu-kredit-kas', 'DashboardController@chartBulanKreditKas');
        // Route::get('/dashboard/laporan-bulan-lalu-kredit-bank', 'DashboardController@chartBulanKreditBank');
        // Route::get('/dashboard/grafik-kategori-kas-debit-bulan-lalu', 'DashboardController@chartKKDBL');
        // Route::get('/dashboard/grafik-kategori-kas-kredit-bulan-lalu', 'DashboardController@chartKKKBL');
        // Route::get('/dashboard/grafik-kategori-bank-debit-bulan-lalu', 'DashboardController@chartKBDBL');
        // Route::get('/dashboard/grafik-kategori-bank-kredit-bulan-lalu', 'DashboardController@chartKBKBL');
        // Route::get('/dashboard/grafik-kategori-kas-debit-tahun-lalu', 'DashboardController@chartKKDTL');
        // Route::get('/dashboard/grafik-kategori-kas-kredit-tahun-lalu', 'DashboardController@chartKKKTL');
        // Route::get('/dashboard/grafik-kategori-bank-debit-tahun-lalu', 'DashboardController@chartKBDTL');
        // Route::get('/dashboard/grafik-kategori-bank-kredit-tahun-lalu', 'DashboardController@chartKBKTL');

        // Master Akun
        Route::match(['get', 'post'], '/master/akun', 'AkunController@add');
        Route::get('/master/akun/data', 'AkunController@getAkun')->name('akun');
        Route::get('/master/akun/{param}/{id}', 'AkunController@destroyAkun');
        Route::match(['get', 'post'], '/master/akun/edit', 'AkunController@updateAkun');
        Route::get('/master/akun/detail', 'AkunController@getCurrentAkun');

        // Mobile Akun
        Route::post('/mobile/master/akun/add', 'AkunController@mobileAdd');
        Route::post('/mobile/master/akun/{param}', 'AkunController@mobileDestroyAkun');

        // Master Kategori
        Route::match(['get', 'post'], '/master/kategori', 'KategoriController@add');
        Route::get('/master/kategori/data', 'KategoriController@getKategori')->name('kategori');
        Route::get('/master/kategori/{param}/{id}', 'KategoriController@destroyKategori');
        Route::match(['get', 'post'], '/master/kategori/edit', 'KategoriController@updateKategori');
        Route::get('/master/kategori/detail', 'KategoriController@getCurrentKategori');

        // Mobile Kategori
        Route::post('/mobile/master/kategori/add', 'KategoriController@mobileAdd');
        Route::post('/mobile/master/kategori/{param}', 'KategoriController@mobileDestroyKategori');

        //Profile
        Route::get('/profil', 'DashboardController@profil');
        Route::post('/profil/update-nama', 'DashboardController@updateNama');
        Route::post('/profil/update-email', 'DashboardController@updateEmail');
        Route::post('/profil/update-username', 'DashboardController@updateUsername');
        Route::post('/profil/update-password', 'DashboardController@updatePassword');
        Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
        Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
        Route::post('/profil/update-foto', 'DashboardController@updateFoto');

        // Mobile profile
        Route::post('/mobile/profil/update-nama', 'DashboardController@mobileUpdateNama');
        Route::post('/mobile/profil/update-email', 'DashboardController@mobileUpdateEmail');
        Route::post('/mobile/profil/update-username', 'DashboardController@mobileUpdateUsername');
        Route::post('/mobile/profil/update-ttl', 'DashboardController@mobileUpdateTtl');
        Route::post('/mobile/profil/update-alamat', 'DashboardController@mobileUpdateAlamat');
        Route::post('/mobile/profil/update-password', 'DashboardController@mobileUpdatePassword');
        Route::post('/mobile/profil/update-foto', 'DashboardController@mobileUpdateFoto');

        //Log Kegiatan
        // Route::get('/log-aktivitas', 'DashboardController@history');
        // Route::get('/log-aktivitas/filter/{tanggal}', 'DashboardController@filterHistory');

        // Start Kas
        // kas masuk
        Route::match(['get', 'post'], '/kas/masuk', 'KeuanganController@debit');
        Route::post('/kas/debet/store', 'KeuanganController@storeDebetKas');
        Route::get('/kas/masuk/data', 'KeuanganController@getDebit')->name('debit');
        Route::get('/kas/masuk/hapus/{id}', 'KeuanganController@deleteDebit');
        Route::delete('/kas/debet/hapus/{id}', 'KeuanganController@deleteDebetKas');
        Route::match(['get', 'post'], '/kas/masuk/edit', 'KeuanganController@updateDebit');
        Route::put('/kas/debet/edit', 'KeuanganController@updateDebetKas');
        Route::get('/kas/masuk/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalDebit');
        Route::get('/kas/masuk/detail/{id}', 'KeuanganController@getCurrentDebit');
        Route::get('/kas/masuk/grafik', 'KeuanganController@grafikDebit');

        // Mobile kas masuk
        Route::get('/mobile/get-kategori/{jenis_kategori}', 'KeuanganController@getKategori');
        Route::get('/mobile/get-akun/{jenis_akun}', 'KeuanganController@getAkun');
        Route::post('/mobile/kas/masuk/add', 'KeuanganController@mobileAddDebitKas');
        Route::post('/mobile/kas/masuk/hapus', 'KeuanganController@mobileDeleteDebitKas');

        // kas keluar
        Route::match(['get', 'post'], '/kas/keluar', 'KeuanganController@credit');
        Route::post('/kas/kredit/store', 'KeuanganController@storeKreditKas');
        Route::get('/kas/keluar/data', 'KeuanganController@getCredit')->name('credit');
        Route::get('/kas/keluar/hapus/{id}', 'KeuanganController@deleteCredit');
        Route::delete('/kas/kredit/hapus/{id}', 'KeuanganController@deleteKreditKas');
        Route::match(['get', 'post'], '/kas/keluar/edit', 'KeuanganController@updateCredit');
        Route::put('/kas/kredit/edit', 'KeuanganController@updateKreditKas');
        Route::get('/kas/keluar/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalCredit');
        Route::get('/kas/keluar/detail/{id}', 'KeuanganController@getCurrentCredit');
        Route::get('/kas/keluar/grafik', 'KeuanganController@grafikCredit');

        // Mobile kas keluar
        Route::post('/mobile/kas/keluar/add', 'KeuanganController@mobileAddCreditKas');
        Route::post('/mobile/kas/keluar/hapus', 'KeuanganController@mobileDeleteCreditKas');
        // End Kas

        // Start Bank
        // bank masuk
        Route::match(['get', 'post'], '/bank/bank-masuk', 'KeuanganController@debitBank');
        Route::post('/bank/debet/store', 'KeuanganController@storeDebetBank');
        Route::get('/bank/masuk/data', 'KeuanganController@getDebitBank')->name('debitBank');
        Route::get('/bank/masuk/hapus/{id}', 'KeuanganController@deleteDebitBank');
        Route::delete('/bank/debet/hapus/{id}', 'KeuanganController@deleteDebetBank');
        Route::match(['get', 'post'], '/bank/masuk/edit', 'KeuanganController@updateDebitBank');
        Route::put('/bank/debet/edit', 'KeuanganController@updateDebetBank');
        Route::get('/bank/masuk/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalDebitBank');
        Route::get('/bank/masuk/detail/{id}', 'KeuanganController@getCurrentDebitBank');
        Route::get('/bank/masuk/grafik', 'KeuanganController@grafikDebitBank');

        // Mobile bank masuk
        Route::post('/mobile/bank/masuk/add', 'KeuanganController@mobileAddDebitBank');
        Route::post('/mobile/bank/masuk/hapus', 'KeuanganController@mobileDeleteDebitBank');

        // bank keluar
        Route::match(['get', 'post'], '/bank/bank-keluar', 'KeuanganController@creditBank');
        Route::post('/bank/kredit/store', 'KeuanganController@storeKreditBank');
        Route::get('/bank/keluar/data', 'KeuanganController@getCreditBank')->name('creditBank');
        Route::get('/bank/keluar/hapus/{id}', 'KeuanganController@deleteCreditBank');
        Route::delete('/bank/kredit/hapus/{id}', 'KeuanganController@deleteKreditBank');
        Route::match(['get', 'post'], '/bank/keluar/edit', 'KeuanganController@updateCreditBank');
        Route::put('/bank/kredit/edit', 'KeuanganController@updateKreditBank');
        Route::get('/bank/keluar/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalCreditBank');
        Route::get('/bank/keluar/detail/{id}', 'KeuanganController@getCurrentCreditBank');
        Route::get('/bank/keluar/grafik', 'KeuanganController@grafikCreditBank');

        // Mobile bank keluar
        Route::post('/mobile/bank/keluar/add', 'KeuanganController@mobileAddCreditBank');
        Route::post('/mobile/bank/keluar/hapus', 'KeuanganController@mobileDeleteCreditBank');
        // End Bank

        // Laporan
        // Chart
        Route::get('/laporan/chart', 'LaporanController@chart');
        Route::get('/laporan/chart/bulan/debit/kas/{bulan}', 'LaporanController@chartBulanDebitKas');
        Route::get('/laporan/chart/bulan/debit/bank/{bulan}', 'LaporanController@chartBulanDebitBank');
        Route::get('/laporan/chart/bulan/kredit/kas/{bulan}', 'LaporanController@chartBulanKreditKas');
        Route::get('/laporan/chart/bulan/kredit/bank/{bulan}', 'LaporanController@chartBulanKreditBank');
        Route::get('/laporan/chart/tahun/kas/{tahun}', 'LaporanController@chartTahunKas');
        Route::get('/laporan/chart/tahun/bank/{tahun}', 'LaporanController@chartTahunBank');
        Route::get('/laporan/chart/kategori-kas-debit-bulan/{bulan}', 'LaporanController@chartKKDB');
        Route::get('/laporan/chart/kategori-kas-kredit-bulan/{bulan}', 'LaporanController@chartKKKB');
        Route::get('/laporan/chart/kategori-bank-debit-bulan/{bulan}', 'LaporanController@chartKBDB');
        Route::get('/laporan/chart/kategori-bank-kredit-bulan/{bulan}', 'LaporanController@chartKBKB');
        Route::get('/laporan/chart/kategori-kas-debit-tahun/{tahun}', 'LaporanController@chartKKDT');
        Route::get('/laporan/chart/kategori-kas-kredit-tahun/{tahun}', 'LaporanController@chartKKKT');
        Route::get('/laporan/chart/kategori-bank-debit-tahun/{tahun}', 'LaporanController@chartKBDT');
        Route::get('/laporan/chart/kategori-bank-kredit-tahun/{tahun}', 'LaporanController@chartKBKT');

        //Cashflow
        Route::get('/laporan/cashflow', 'LaporanController@cashflow');
        Route::get('/laporan/cashflow/bulan/{bulan}', 'LaporanController@cashflowBulan');
        Route::get('/laporan/cashflow/tahun/{tahun}', 'LaporanController@cashflowTahun');
        Route::get('/laporan/cashflow/excel/{bulan}/{tahun}', 'LaporanController@excel');
        Route::get('/laporan/cashflow/pdf/{bulan}/{tahun}', 'LaporanController@pdf');
    });

    // Admin
    Route::group(['namespace' => 'admin'], function(){
        // dashboard
        Route::get('/admin/dashboard', 'DashboardController@dashboard');

        // Users
        Route::get('/admin/users', 'UsersController@index');
        Route::get('/admin/users/get-member-active', 'UsersController@getMemberActive')->name('member_active');
        Route::get('/admin/users/get-member-nonactive', 'UsersController@getMemberNonActive')->name('member_nonactive');
        Route::get('/admin/users/get-member-suspend', 'UsersController@getMemberSuspend')->name('member_suspend');
        Route::get('/admin/users/active/{id}', 'UsersController@active');
        Route::get('/admin/users/nonactive/{id}', 'UsersController@nonActive');
        Route::get('/admin/users/suspend/{id}', 'UsersController@suspend');
        Route::get('/admin/users/detail/{id}', 'UsersController@detail');
        Route::post('/admin/users/update-nama', 'UsersController@updateNama');
        Route::post('/admin/users/update-email', 'UsersController@updateEmail');
        Route::post('/admin/users/update-username', 'UsersController@updateUsername');
        Route::post('/admin/users/update-password', 'UsersController@updatePassword');
        Route::post('/admin/users/update-ttl', 'UsersController@updateTtl');
        Route::post('/admin/users/update-alamat', 'UsersController@updateAlamat');
        Route::post('/admin/users/update-foto', 'UsersController@updateFoto');

        // Profile
        Route::get('/admin/profile', 'ProfileController@index');
        Route::post('/admin/profile/update-nama', 'ProfileController@updateNama');
        Route::post('/admin/profile/update-email', 'ProfileController@updateEmail');
        Route::post('/admin/profile/update-username', 'ProfileController@updateUsername');
        Route::match(['get', 'post'], '/admin/profile/update-password', 'ProfileController@updatePassword');
        Route::post('/admin/profile/update-ttl', 'ProfileController@updateTtl');
        Route::post('/admin/profile/update-alamat', 'ProfileController@updateAlamat');
        Route::post('/admin/profile/update-foto', 'ProfileController@updateFoto');

        // Message
        Route::get('/admin/pesan', 'MessageController@index');
        Route::get('/admin/pesan/get-unread', 'MessageController@messageUnread')->name('message.unread');
        Route::get('/admin/pesan/get-read', 'MessageController@messageRead')->name('message.read');
        Route::get('/admin/pesan/get-bookmark', 'MessageController@messageBookmark')->name('message.bookmark');
        Route::get('/admin/pesan/read/{id}', 'MessageController@read');
        Route::get('/admin/pesan/bookmark/{id}', 'MessageController@bookmark');
        Route::get('/admin/pesan/unbookmark/{id}', 'MessageController@unBookmark');

        // Index
        Route::match(['get', 'post'], '/admin/index/identitas-app', 'IndexController@identitasApp');
        Route::match(['get', 'post'], '/admin/index/layanan', 'IndexController@layanan');
        Route::get('/admin/index/layanan/get-data', 'IndexController@getLayanan')->name('getLayanan');
        Route::match(['get', 'post'], '/admin/index/layanan/create', 'IndexController@layananCreate');
        Route::get('/admin/index/layanan/delete/{id}', 'IndexController@layananDelete');
        Route::match(['get', 'post'], '/admin/index/syarat', 'IndexController@syarat');
        Route::match(['get', 'post'], '/admin/index/kebijakan', 'IndexController@kebijakan');

        // Article
        Route::match(['get', 'post'], '/admin/article', 'ArticleController@index');
        Route::get('/admin/article/get-data', 'ArticleController@getArticle')->name('getArticle');
        Route::match(['get', 'post'], '/admin/article/create', 'ArticleController@articleCreate');
        Route::get('/admin/article/status/{id}/{status}', 'ArticleController@status');
        Route::get('/admin/article/delete/{id}', 'ArticleController@articleDelete');
    });

    //Riwayat
    // Member
    Route::get('/riwayat/aktivitas', 'ActivityController@historyAktivitas');
    Route::get('/riwayat/aktivitas/filter/{tanggal}', 'ActivityController@filterHistoryAktivitas');
    Route::get('/riwayat/kas', 'ActivityController@historyKas');
    Route::get('/riwayat/get-kas/{tanggal}', 'ActivityController@getHistoryKas');
    Route::get('/riwayat/bank', 'ActivityController@historyBank');
    Route::get('/riwayat/get-bank/{tanggal}', 'ActivityController@getHistoryBank');

    // Admin
    Route::get('/admin/riwayat/aktivitas', 'ActivityController@historyAktivitasAdmin');
    Route::get('/admin/riwayat/aktivitas/filter/{tanggal}', 'ActivityController@filterHistoryAktivitas');
});
