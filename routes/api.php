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
                Route::get('user', 'SignController@user');
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
                Route::get('/get-all-akun/{jenis_akun}', 'AkunController@getAllAkun');
                Route::delete('/delete-akun/{id}', 'AkunController@deleteAkun');
                Route::put('/status-akun', 'AkunController@statusAkun');
                Route::get('/get-current-akun/{id}', 'AkunController@getCurrentAkun');
                Route::put('/update-akun', 'AkunController@updateAkun');

                // Kategori
                Route::post('/save-kategori', 'KategoriController@saveKategori');
                Route::get('/get-kategori', 'KategoriController@getKategori');
                Route::get('/get-all-kategori/{jenis_transaksi}', 'KategoriController@getAllKategori');
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
                Route::get('/get-data-transaction', 'KeuanganController@getDataTransaction');
                Route::get('/akumulasi-total', 'KeuanganController@akumulasiTotal');

                Route::get('/get-current-transaction/{id}', 'KeuanganController@getCurrentTransaction');

                // Bank
                // Debet
                Route::post('/save-bank-debet', 'KeuanganController@saveBankDebet');
                Route::put('/update-bank-debet', 'KeuanganController@updateBankDebet');
                Route::delete('/delete-bank-debet/{id}', 'KeuanganController@deleteBankDebet');
                

                // Kredit
                Route::post('/save-bank-kredit', 'KeuanganController@saveBankKredit');
                Route::put('/update-bank-kredit', 'KeuanganController@updateBankKredit');
                Route::delete('/delete-bank-kredit/{id}', 'KeuanganController@deleteBankKredit');
                // End Bank

                // Kas
                // Debet
                Route::post('/save-kas-debet', 'KeuanganController@saveKasDebet');
                Route::put('/update-kas-debet', 'KeuanganController@updateKasDebet');
                Route::delete('/delete-kas-debet/{id}', 'KeuanganController@deleteKasDebet');

                // Kredit
                Route::post('/save-kas-kredit', 'KeuanganController@saveKasKredit');
                Route::put('/update-kas-kredit', 'KeuanganController@updateKasKredit');
                Route::delete('/delete-kas-kredit/{id}', 'KeuanganController@deleteKasKredit');
                // End Kas

                // Statistik
                Route::get('/statistik', 'LaporanController@statistik');
                Route::get('/cashflow', 'LaporanController@getCashflow');
            });
        });
    });
});

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
