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

Route::get('/', 'SignController@login');
Route::post('/login', 'SignController@login');
Route::get('/logout', 'SignController@logout');

Route::group(['middleware'=>['auth']], function(){
	Route::get('/dashboard', 'DashboardController@dashboard');
	Route::get('/admin/settings', 'AdminController@settings');
	Route::get('/admin/check-pwd', 'AdminController@checkPassword');
	Route::match(['get', 'post'], '/admin/update-pwd', 'AdminController@updatePassword');
	Route::get('/admin/profil', 'AdminController@profil');
	Route::get('/dashboard/riwayat/{parameter}', 'DashboardController@riwayat');
	Route::get('/dashboard/grafik', 'KeuanganController@grafik');

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
	
});
