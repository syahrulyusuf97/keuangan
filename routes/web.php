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
	Route::get('/dashboard/riwayat/{parameter}', 'KeuanganController@riwayat');

	// Deposito
	Route::match(['get', 'post'], '/deposito', 'KeuanganController@deposito');
	Route::get('/deposito/delete/{id}', 'KeuanganController@delete_deposito');
	Route::match(['get', 'post'], '/deposito/update', 'KeuanganController@update_deposito');
	Route::get('/deposito/akumulasi/{parameter}', 'KeuanganController@akumulasi_total_deposito');
	Route::get('/deposito/get-current/{id}', 'KeuanganController@get_current_deposito');

	// Credit
	Route::match(['get', 'post'], '/credit', 'KeuanganController@credit');
	Route::get('/credit/delete/{id}', 'KeuanganController@delete_credit');
	Route::match(['get', 'post'], '/credit/update', 'KeuanganController@update_credit');
	Route::get('/credit/akumulasi/{parameter}', 'KeuanganController@akumulasi_total_credit');
	Route::get('/credit/get-current/{id}', 'KeuanganController@get_current_credit');
	
});
