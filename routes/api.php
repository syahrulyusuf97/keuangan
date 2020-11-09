<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::fallback(function(){
    return response()->json([
        'status'    => "error",
        'message' => 'Not Found'], 404);
});

// Api version 1
Route::group(['prefix' => 'v1'], function(){
    // Prefix for auth
    Route::group(['prefix' => 'auth'], function () {
        Route::group(['namespace' => 'Api'], function(){
            Route::post('login', 'SignController@login');
            Route::post('signup', 'SignController@signup');
          
            Route::group(['middleware' => 'auth:api'], function() {
                Route::get('logout', 'SignController@logout');
                // Route::get('user', 'SignController@user');
                // Route::get('details', 'SignController@details');
            });
        });
    });

    // Prefix for master
    Route::group(['prefix' => 'master'], function(){
        Route::group(['namespace' => 'Api\Member'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                // Akun
                Route::post('/save-akun', 'AkunController@saveAkun');
                Route::get('/get-akun', 'AkunController@getAkun');
                Route::delete('/delete-akun/{id}', 'AkunController@deleteAkun');
                Route::put('/status-akun', 'AkunController@statusAkun');
                Route::get('/get-current-akun/{id}', 'AkunController@getCurrentAkun');
                Route::put('/update-akun', 'AkunController@updateAkun');

                // Kategori
                Route::post('/save-kategori', 'KategoriController@saveKategori');
                Route::get('/get-kategori', 'KategoriController@getKategori');
                Route::delete('/delete-kategori/{id}', 'KategoriController@deleteKategori');
                Route::put('/status-kategori', 'KategoriController@statusKategori');
                Route::get('/get-current-kategori/{id}', 'KategoriController@getCurrentKategori');
                Route::put('/update-kategori', 'KategoriController@updateKategori');
            });
        });
    });

    // Prefix for dashboard
    Route::group(['prefix' => 'dashboard'], function(){
        Route::group(['namespace' => 'Api\Member'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                // Dashboard
                Route::get('/get-saldo', 'DashboardController@getSaldo');
                Route::get('/detail-saldo/{param}', 'DashboardController@detailSaldo');
                Route::get('/get-statistik', 'DashboardController@getStatistik');
            });
        });
    });

    // Prefix for keuangan
    Route::group(['prefix' => 'keuangan'], function(){
        Route::group(['namespace' => 'Api\Member'], function(){      
            Route::group(['middleware' => 'auth:api'], function() {
                Route::get('/get-kategori/{jenis_transaksi}', 'KeuanganController@getKategori');
                Route::get('/get-akun/{jenis_akun}', 'KeuanganController@getAkun');

                // Kas
                // Debet
                Route::post('/save-kas-debet', 'KeuanganController@saveKasDebet');
                Route::get('/get-kas-debet', 'KeuanganController@getKasDebet');
                Route::delete('/delete-kas-debet/{id}', 'KeuanganController@deleteKasDebet');
                Route::get('/get-current-kas-debet/{id}', 'KeuanganController@getCurrentKasDebet');
                Route::put('/update-kas-debet', 'KeuanganController@updateKasDebet');

                // Kredit
                Route::post('/save-kas-kredit', 'KeuanganController@saveKasKredit');
                Route::get('/get-kas-kredit', 'KeuanganController@getKasKredit');
                Route::delete('/delete-kas-kredit/{id}', 'KeuanganController@deleteKasKredit');
                Route::get('/get-current-kas-kredit/{id}', 'KeuanganController@getCurrentKasKredit');
                Route::put('/update-kas-kredit', 'KeuanganController@updateKasKredit');
                // End Kas

                // Bank
                // Debet
                Route::post('/save-bank-debet', 'KeuanganController@saveBankDebet');
                Route::get('/get-bank-debet', 'KeuanganController@getBankDebet');
                Route::delete('/delete-bank-debet/{id}', 'KeuanganController@deleteBankDebet');
                Route::get('/get-current-bank-debet/{id}', 'KeuanganController@getCurrentBankDebet');
                Route::put('/update-bank-debet', 'KeuanganController@updateBankDebet');

                // Kredit
                Route::post('/save-bank-kredit', 'KeuanganController@saveBankKredit');
                Route::get('/get-bank-kredit', 'KeuanganController@getBankKredit');
                Route::delete('/delete-bank-kredit/{id}', 'KeuanganController@deleteBankKredit');
                Route::get('/get-current-bank-kredit/{id}', 'KeuanganController@getCurrentBankKredit');
                Route::put('/update-bank-kredit', 'KeuanganController@updateBankKredit');
                // End Bank

                // Statistik
                Route::get('/statistik-bulan', 'LaporanController@statistikBulan');
                Route::get('/statistik-tahun', 'LaporanController@statistikTahun');
                Route::get('/statistik-kategori-bulan', 'LaporanController@statistikKategoriBulan');
                Route::get('/statistik-kategori-tahun', 'LaporanController@statistikKategoriTahun');
                Route::get('/cashflow', 'LaporanController@getCashflow');
            });
        });
    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
