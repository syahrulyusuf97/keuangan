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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/createuser', 'CreateUser@store');

Route::get('/', 'SignController@login');
Route::match(['get', 'post'], '/login', 'SignController@login')->name('login');
Route::get('/logout', 'SignController@logout');

Route::group(['middleware'=>['auth']], function(){
	Route::get('/dashboard', 'DashboardController@dashboard');
	Route::get('/admin/settings', 'AdminController@settings');
	Route::get('/admin/check-pwd', 'AdminController@checkPassword');
	Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');
	Route::get('/admin/profil', 'AdminController@profil');
	Route::get('/dashboard/riwayat/{parameter}', 'DashboardController@riwayat');
	Route::get('/dashboard/grafik', 'DashboardController@grafik');

    //Profile
    Route::get('/profil', 'DashboardController@profil');
    Route::post('/profil/update-nama', 'DashboardController@updateNama');
    Route::post('/profil/update-email', 'DashboardController@updateEmail');
    Route::post('/profil/update-username', 'DashboardController@updateUsername');
    Route::post('/profil/update-password', 'DashboardController@updatePassword');
    Route::post('/profil/update-ttl', 'DashboardController@updateTtl');
    Route::post('/profil/update-alamat', 'DashboardController@updateAlamat');
    Route::post('/profil/update-foto', 'DashboardController@updateFoto');

    //Log Kegiatan
    Route::get('/log-kegiatan', 'DashboardController@history');

	// kas masuk
	Route::match(['get', 'post'], '/kas/masuk', 'KeuanganController@debit');
	Route::get('/kas/masuk/data', 'KeuanganController@getDebit')->name('debit');
	Route::get('/kas/masuk/hapus/{id}', 'KeuanganController@deleteDebit');
	Route::match(['get', 'post'], '/kas/masuk/edit', 'KeuanganController@updateDebit');
	Route::get('/kas/masuk/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalDebit');
	Route::get('/kas/masuk/detail/{id}', 'KeuanganController@getCurrentDebit');
	Route::get('/kas/masuk/grafik', 'KeuanganController@grafikDebit');

	// kas keluar
	Route::match(['get', 'post'], '/kas/keluar', 'KeuanganController@credit');
    Route::get('/kas/keluar/data', 'KeuanganController@getCredit')->name('credit');
	Route::get('/kas/keluar/hapus/{id}', 'KeuanganController@deleteCredit');
	Route::match(['get', 'post'], '/kas/keluar/edit', 'KeuanganController@updateCredit');
	Route::get('/kas/keluar/akumulasi/{parameter}', 'KeuanganController@akumulasiTotalCredit');
	Route::get('/kas/keluar/detail/{id}', 'KeuanganController@getCurrentCredit');
	Route::get('/kas/keluar/grafik', 'KeuanganController@grafikCredit');

	// Laporan
    // Chart
    Route::get('/laporan/chart', 'LaporanController@chart');
    Route::get('/laporan/chart/bulan/debit/{bulan}', 'LaporanController@chartBulanDebit');
    Route::get('/laporan/chart/bulan/kredit/{bulan}', 'LaporanController@chartBulanKredit');
    Route::get('/laporan/chart/tahun/{tahun}', 'LaporanController@chartTahun');

    //Cashflow
    Route::get('/laporan/cashflow', 'LaporanController@cashflow');
    Route::get('/laporan/cashflow/bulan/{bulan}', 'LaporanController@cashflowBulan');
    Route::get('/laporan/cashflow/tahun/{tahun}', 'LaporanController@cashflowTahun');

});
