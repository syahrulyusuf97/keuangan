<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cash;
use App\Akun;
use App\Kategori;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;
use Auth;
use Helper;
use Validator;

class KeuanganController extends Controller
{    
    public function getKategori(Request $request, $jenis_transaksi)
    {
        if ($request->isMethod('get')) {
            try{
                $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', $jenis_transaksi)->get()->map(function($items, $key) {
                    // $data['id'] = Crypt::encrypt($items->id);
                    $data['id'] = $items->id;
                    $data['category_name'] = $items->nama;
                    return $data;
                });

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $kategori
                ];
            }catch(\Exception $e){
                $response = [
                    'success'    => false,
                    'message'   => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data' => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'   => Helper::errorCode(1106),
                'error_code' => 1106,
                'data' => []
            ];
        }
        return response()->json($response);
    }

    public function getAkun(Request $request, $jenis_akun)
    {
        if ($request->isMethod('get')) {
            try{
                $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', $jenis_akun)->get()->map(function($items, $key) {
                    // $data['id'] = Crypt::encrypt($items->id);
                    $data['id'] = $items->id;
                    $data['account_type'] = $items->jenis_akun;
                    $data['account_code'] = $items->kode_akun;
                    $data['account_name'] = $items->nama_akun;
                    return $data;
                });

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $akun
                ];
            }catch(\Exception $e){
                $response = [
                    'success'    => false,
                    'message'   => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data' => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'   => Helper::errorCode(1106),
                'error_code' => 1106,
                'data' => []
            ];
        }

        return response()->json($response);
    }

    public function akumulasiTotal(Request $request)
    {
        $params          = $request->params == "" ? "BulanLalu" : $request->params;
        $flag_akun       = $request->flag_akun;
        $jenis           = $request->jenis;
        $tanggal         = $request->tanggal;
        $tanggal_awal    = $request->tanggal_awal;
        $tanggal_akhir   = $request->tanggal_akhir;
        $month           = $request->bulan;
        $tahun           = $request->tahun;
        $result          = array();
        $row             = array();
        $last_month      = date('Y-m', strtotime('-1 months'));
        $last_month_year = explode("-", $last_month)[0];
        $last_month_month = explode("-", $last_month)[1];
        $last_year       = date('Y', strtotime('-1 year'));

        if ($params == "BulanLalu") {
            try{
                $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->sum('c_jumlah');

                $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->get();

                foreach ($data as $value) {
                    $row = array(
                        'tanggal'       => $value->c_tanggal,
                        'keterangan'    => $value->c_transaksi,
                        'jumlah'        => $value->c_jumlah
                    );

                    $result[] = $row;
                }

                $result_array = array('option'=>$params, 'periode'=>$last_month, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $result_array
                ];
            }catch(Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error_code' => null,
                    'data'   => []
                ];
            }

            return Response::json($response);
        } elseif ($params == "TahunLalu") {
            try{
                $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->sum('c_jumlah');

                $data        = Cash::where('c_iduser', Auth::user()->id)
                                ->where('c_flagakun', $flag_akun)
                                ->where('c_jenis', $jenis)
                                ->whereYear('c_tanggal', '=', $last_year)
                                ->get();

                foreach ($data as $value) {
                    $row = array(
                        'tanggal' => $value->c_tanggal,
                        'keterangan' => $value->c_transaksi,
                        'jumlah' => $value->c_jumlah
                    );

                    $result[] = $row;
                }

                $result_array = array('option'=>$params, 'periode'=>$last_year, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $result_array
                ];
            }catch(Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error_code' => null,
                    'data'   => []
                ];
            }

            return Response::json($response);
        } elseif ($params == "Pertanggal") {
            try{
                $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->where('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

                $data        = Cash::where('c_iduser', Auth::user()->id)
                                ->where('c_flagakun', $flag_akun)
                                ->where('c_jenis', $jenis)
                                ->where('c_tanggal', '=', $tanggal)
                                ->get();

                foreach ($data as $value) {
                    $row = array(
                        'tanggal' => $value->c_tanggal,
                        'keterangan' => $value->c_transaksi,
                        'jumlah' => $value->c_jumlah
                    );

                    $result[] = $row;
                }

                $result_array = array('option'=>$params, 'periode'=>$tanggal, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $result_array
                ];
            }catch(Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error_code' => null,
                    'data'   => []
                ];
            }

            return Response::json($response);
        } elseif ($params == "Perbulan") {
            $ex_params = explode("-", $month);
            $tahun = $ex_params[0];
            $bulan = $ex_params[1];

            try{
                $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->sum('c_jumlah');

                $data        = Cash::where('c_iduser', Auth::user()->id)
                                ->where('c_flagakun', $flag_akun)
                                ->where('c_jenis', $jenis)
                                ->whereYear('c_tanggal', '=', $tahun)
                                ->whereMonth('c_tanggal', '=', $bulan)
                                ->get();

                foreach ($data as $value) {
                    $row = array(
                        'tanggal' => $value->c_tanggal,
                        'keterangan' => $value->c_transaksi,
                        'jumlah' => $value->c_jumlah
                    );

                    $result[] = $row;
                }

                $result_array = array('option'=>$params, 'periode'=>$month, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $result_array
                ];
            }catch(Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error_code' => null,
                    'data'   => []
                ];
            }

            return Response::json($response);
        } elseif ($params == "Pertahun") {
            try{
                $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->sum('c_jumlah');

                $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', $flag_akun)
                            ->where('c_jenis', $jenis)
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->get();

                foreach ($data as $value) {
                    $row = array(
                        'tanggal' => $value->c_tanggal,
                        'keterangan' => $value->c_transaksi,
                        'jumlah' => $value->c_jumlah
                    );

                    $result[] = $row;
                }

                $result_array = array('option'=>$params, 'periode'=>$tahun, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);

                $response = [
                    'success' => true,
                    'message' => 'data available',
                    'error_code' => null,
                    'data'   => $result_array
                ];
            }catch(Exception $e){
                $response = [
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem',
                    'error_code' => null,
                    'data'   => []
                ];
            }

            return Response::json($response);
        } elseif ($params == "RangeTanggal") {

            $rules_of_validator = [
                'tanggal_awal'  => 'required',
                'tanggal_akhir' => 'required'
            ];

            $message_of_validator = [
                'tanggal_awal.required'  => 'Tanggal awal tidak boleh kosong',
                'tanggal_akhir.required' => 'Tanggal akhir tidak boleh kosong'
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                try{
                    $total_debit = Cash::where('c_iduser', Auth::user()->id)
                                ->where('c_flagakun', $flag_akun)
                                ->where('c_jenis', $jenis)
                                ->where('c_tanggal', '>=', $tanggal_awal)
                                ->where('c_tanggal', '<=', $tanggal_akhir)
                                ->sum('c_jumlah');
    
                    $data        = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', $flag_akun)
                                    ->where('c_jenis', $jenis)
                                    ->where('c_tanggal', '>=', $tanggal_awal)
                                    ->where('c_tanggal', '<=', $tanggal_akhir)
                                    ->get();
    
                    foreach ($data as $value) {
                        $row = array(
                            'tanggal' => $value->c_tanggal,
                            'keterangan' => $value->c_transaksi,
                            'jumlah' => $value->c_jumlah
                        );
    
                        $result[] = $row;
                    }
    
                    $result_array = array('option'=>$params, 'periode'=>$tanggal_awal.' s/d '.$tanggal_akhir, 'flag_akun'=>$flag_akun, 'type'=>$jenis, 'total'=>$total_debit, 'detail'=>$result);
    
                    $response = [
                        'success' => true,
                        'message' => 'data available',
                        'error_code' => null,
                        'data'   => $result_array
                    ];
                }catch(Exception $e){
                    $response = [
                        'success' => false,
                        'message' => 'Terjadi kesalahan sistem',
                        'error_code' => null,
                        'data'   => []
                    ];
                }
            }

            return Response::json($response);
        } else {
            $response = [
                'success' => false,
                'message' => 'Data tidak tersidia',
                'error_code' => null,
                'data'   => []
            ];
        }
    }

    public function getDataTransaction(Request $request)
    {
        if ($request->isMethod('get')) {
            $limit     = $request->limit ?? 5;
            $offset    = $request->offset ?? 0;
            $sort      = $request->sort ?? 'desc';
            $flag_akun = $request->flag_akun;
            $jenis     = $request->jenis;

            // SEARCH
            $search_description_of_transaction = $request->description_of_transaction;
            $search_amount                     = $request->amount;
            $search_date_of_transaction        = $request->date_of_transaction ? Carbon::parse($request->date_of_transaction)->format('Y-m-d') : null;
            $search_category_id                = Helper::tryDecrypt($request->category_id);
            $search_account_bank_id            = (Helper::tryDecrypt($request->account_bank_id)) ? Helper::tryDecrypt($request->account_bank_id) : $request->account_bank_id;
            $search_first_date                 = $request->first_date ? Carbon::parse($request->first_date)->format('Y-m-d') : null;
            $search_last_date                  = $request->last_date ? Carbon::parse($request->last_date)->format('Y-m-d') : null;
            $is_date_range                     = ($search_first_date && $search_last_date);
            $is_first_date                     = ($search_first_date && !$search_last_date);
            $is_last_date                      = (!$search_first_date && $search_last_date);

            $rules_of_validator = [
                'flag_akun'  => 'required',
                'jenis' => 'required'
            ];

            $message_of_validator = [
                'flag_akun.required'  => 'Flag akun tidak boleh kosong',
                'jenis.required' => 'Jenis tidak boleh kosong'
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                try{
                    $data_transaction_count = Cash::where('c_iduser', Auth::user()->id)
                    ->where('c_flagakun', $flag_akun)
                    ->where('c_jenis', $jenis)
                    ->selectRaw("count(distinct(c_id)) as total")
                    ->first();
                    
                    $data_transaction = Cash::with(['akun'])
                    ->where('c_iduser', Auth::user()->id)
                    ->where('c_flagakun', $flag_akun)
                    ->where('c_jenis', $jenis)
                    ->when($search_description_of_transaction, function($query) use ($search_description_of_transaction) {
                        $query->where('c_transaksi', 'like', '%'.$search_description_of_transaction.'%');
                    })
                    ->when($search_amount, function($query) use ($search_amount) {
                        $query->where('c_jumlah', 'like', '%'.$search_amount.'%');
                    })
                    ->when($search_date_of_transaction, function($query) use ($search_date_of_transaction) {
                        $query->where('c_tanggal', $search_date_of_transaction);
                    })
                    ->when($is_date_range, function($query) use ($search_first_date, $search_last_date) {
                        $query->where('c_tanggal', '>=', $search_first_date)->where('c_tanggal', '<=', $search_last_date);
                    })
                    ->when($is_first_date, function($query) use ($search_first_date) {
                        $query->where('c_tanggal', $search_first_date);
                    })
                    ->when($is_last_date, function($query) use ($search_last_date) {
                        $query->where('c_tanggal', $search_last_date);
                    })
                    ->when($search_category_id, function($query) use ($search_category_id) {
                        $query->where('c_kategori', $search_category_id);
                    })
                    ->when($search_account_bank_id, function($query) use ($search_account_bank_id) {
                        $query->where('c_akun', $search_account_bank_id);
                    });

                    $total_filtered = $data_transaction->count();
                    
                    $data_transaction_results = $data_transaction
                    ->limit($limit)
                    ->offset($offset)
                    ->orderBy('c_tanggal', 'desc')
                    ->get()
                    ->map(function ($items, $key) {
                        $data['id']                         = encrypt($items->c_id);
                        $data['description_of_transaction'] = $items->c_transaksi;
                        $data['amount']                     = $items->c_jumlah;
                        $data['type_of_transaction']        = $items->c_jenis;
                        $data['date_of_transaction']        = $items->c_tanggal;
                        // $data['category_id']                = encrypt($items->c_kategori);
                        $data['category_id']                = $items->c_kategori;
                        // $data['account_bank_id']         = encrypt($items->c_akun);
                        $data['account_bank_id']            = $items->c_akun;
                        $data['flag_of_transaction']        = $items->c_flag;
                        $data['flag_of_account']            = $items->c_flagakun;
                        $data['account_code']               = $items->akun->kode_akun;
                        $data['account_name']               = $items->akun->nama_akun;
                        $data['account_code_name']          = "(".$items->akun->kode_akun.") ".$items->akun->nama_akun;
                        $data['account_type']               = $items->akun->jenis_akun;
                        $data['user_id']                    = encrypt($items->c_iduser);
                        $data['created_at']                 = Carbon::parse($items->created_at)->format("Y-m-d H:i:s");
                        $data['updated_at']                 = Carbon::parse($items->updated_at)->format("Y-m-d H:i:s");
                        return $data;
                    });

                    $results = [
                        'records_total'    => $data_transaction_count->total,
                        'records_filtered' => $total_filtered,
                        'records_limit'    => $limit,
                        'records_offset'   => $offset,
                        'records'          => $data_transaction_results
                    ];

                    $response = [
                        'success'    => true,
                        'message'    => 'data available',
                        'error_code' => null,
                        'data'       => $results
                    ];
                }catch(\Exception $e){
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function getCurrentTransaction(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];

                return response()->json($response);
            }

            $data = Cash::with(['akun', 'kategori'])
            ->where('c_iduser', Auth::user()->id)
            ->where('c_id', $id);

            if ($data->count() > 0) {
                $results = array(
                    'id' => Crypt::encrypt($data->first()->c_id),
                    'kategori_id' => $data->first()->c_kategori,
                    'kategori' => $data->first()->kategori->nama,
                    'keterangan' => $data->first()->c_transaksi,
                    'jumlah' => $data->first()->c_jumlah,
                    'tanggal' => $data->first()->c_tanggal,
                    'akun_id' => $data->first()->c_akun,
                    'akun' => "(".$data->first()->akun->kode_akun.") ".$data->first()->akun->nama_akun,
                );

                $response = [
                    'success'    => true,
                    'message'    => "Data available",
                    'error_code' => null,
                    'data'       => $results
                ];
            } else {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak ditemukan",
                    'error_code' => null,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    // Start Bank
    public function saveBankDebet(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules_of_validator = [
                'kategori'   => 'required',
                'keterangan' => 'required',
                'jumlah'     => 'required',
                'tanggal'    => 'required',
                'ke_akun'    => 'required',
            ];

            $message_of_validator = [
                'kategori.required'     => 'Kategori tidak boleh kosong',
                'keterangan.required'   => 'Keterangan tidak boleh kosong',
                'jumlah.required'       => 'Jumlah tidak boleh kosong',
                'tanggal.required'      => 'Tanggal tidak boleh kosong',
                'ke_akun.required'      => 'Ke akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                $kategori   = $request->kategori;
                $keterangan = $request->keterangan;
                $jumlah     = $request->jumlah;
                $tanggal    = date('Y-m-d', strtotime($request->tanggal));
                $ke_akun    = $request->ke_akun;
                
                // try {
                //     $ke_akun     = Crypt::decrypt($ke_akun);
                //     $kategori   = Crypt::decrypt($kategori);
                // } catch (DecryptException $e) {
                //     $response = [
                //         'success'    => false,
                //         'message'    => Helper::errorCode(1402),
                //         'error_code' => 1402,
                //         'data'       => []
                //     ];

                //     return response()->json($response);
                // }

                DB::beginTransaction();
                try{
                    $debit = new Cash;
                    $debit->c_transaksi     = $keterangan;
                    $debit->c_jumlah        = $jumlah;
                    $debit->c_jenis         = "D";
                    $debit->c_tanggal       = $tanggal;
                    $debit->c_kategori      = $kategori;
                    $debit->c_akun          = $ke_akun;
                    $debit->c_flag          = "Pemasukan";
                    $debit->c_flagakun      = "Bank";
                    $debit->c_iduser        = Auth::user()->id;
                    $debit->created_at      = Carbon::now('Asia/Jakarta');
                    $debit->updated_at      = Carbon::now('Asia/Jakarta');
                    $debit->save();

                    $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                    Activity::log(Auth::user()->id, 'Create', 'membuat bank masuk', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keterangan . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . '('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Bank masuk berhasil disimpan",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function updateBankDebet(Request $request)
    {
        if ($request->isMethod('put')) {
            $kategori   = $request->kategori;
            $keterangan = $request->keterangan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $ke_akun    = $request->ke_akun;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                // $ke_akun    = Crypt::decrypt($ke_akun);
                // $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];
                return response()->json($response);
            }

            $rules_of_validator = [
                'kategori'   => 'required',
                'keterangan' => 'required',
                'jumlah'     => 'required',
                'tanggal'    => 'required',
                'ke_akun'    => 'required',
            ];

            $message_of_validator = [
                'kategori.required'     => 'Kategori tidak boleh kosong',
                'keterangan.required'   => 'Keterangan tidak boleh kosong',
                'jumlah.required'       => 'Jumlah tidak boleh kosong',
                'tanggal.required'      => 'Tanggal tidak boleh kosong',
                'ke_akun.required'      => 'Ke akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                    $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                    Activity::log(Auth::user()->id, 'Update', 'memperbarui bank masuk', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keterangan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                    Cash::where(['c_id'=>$id])->update([
                        'c_transaksi'   => $keterangan,
                        'c_jumlah'      => $jumlah,
                        'c_tanggal'     => $tanggal,
                        'c_kategori'    => $kategori,
                        'c_akun'        => $ke_akun,
                        'updated_at'    => Carbon::now('Asia/Jakarta')
                    ]);

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui bank masuk",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function deleteBankDebet(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];

                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Delete', 'menghapus bank masuk', date('d-m-Y', strtotime($cash->c_tanggal)) .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=> $id])->delete();

                DB::commit();

                $response = [
                    'success'    => true,
                    'message'    => "Berhasil menghapus bank masuk",
                    'error_code' => null,
                    'data'       => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success'    => false,
                    'message'    => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function saveBankKredit(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules_of_validator = [
                'kategori'  => 'required',
                'keperluan' => 'required',
                'jumlah'    => 'required',
                'tanggal'   => 'required',
                'dari_akun' => 'required',
            ];

            $message_of_validator = [
                'kategori.required'  => 'Kategori tidak boleh kosong',
                'keperluan.required' => 'Keperluan tidak boleh kosong',
                'jumlah.required'    => 'Jumlah tidak boleh kosong',
                'tanggal.required'   => 'Tanggal tidak boleh kosong',
                'dari_akun.required' => 'Dari akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                $kategori   = $request->kategori;
                $keperluan  = $request->keperluan;
                $jumlah     = $request->jumlah;
                $tanggal    = date('Y-m-d', strtotime($request->tanggal));
                $dari_akun  = $request->dari_akun;

                // try {
                //     $dari_akun = Crypt::decrypt($dari_akun);
                //     $kategori = Crypt::decrypt($kategori);
                // } catch (DecryptException $e) {
                //     $response = [
                //         'status'    => "failed",
                //         'message'   => "A network error occurred. Please try again!"
                //     ];
                //     return response()->json($response);
                // }

                DB::beginTransaction();
                try{
                    $credit = new Cash;
                    $credit->c_transaksi = $keperluan;
                    $credit->c_jumlah    = $jumlah;
                    $credit->c_jenis     = "K";
                    $credit->c_tanggal   = $tanggal;
                    $credit->c_kategori  = $kategori;
                    $credit->c_akun      = $dari_akun;
                    $credit->c_flag      = "Pengeluaran";
                    $credit->c_flagakun  = 'Bank';
                    $credit->c_iduser    = Auth::user()->id;
                    $credit->created_at  = Carbon::now('Asia/Jakarta');
                    $credit->updated_at  = Carbon::now('Asia/Jakarta');
                    $credit->save();

                    $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                    Activity::log(Auth::user()->id, 'Create', 'membuat bank keluar', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keperluan . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . '('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Bank keluar berhasil disimpan",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function updateBankKredit(Request $request)
    {
        if ($request->isMethod('put')) {
            $kategori   = $request->kategori;
            $keperluan  = $request->keperluan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                // $dari_akun  = Crypt::decrypt($dari_akun);
                // $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];
                return response()->json($response);
            }

            $rules_of_validator = [
                'kategori'  => 'required',
                'keperluan' => 'required',
                'jumlah'    => 'required',
                'tanggal'   => 'required',
                'dari_akun' => 'required',
            ];

            $message_of_validator = [
                'kategori.required'  => 'Kategori tidak boleh kosong',
                'keperluan.required' => 'Keperluan tidak boleh kosong',
                'jumlah.required'    => 'Jumlah tidak boleh kosong',
                'tanggal.required'   => 'Tanggal tidak boleh kosong',
                'dari_akun.required' => 'Dari akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                    $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                    Activity::log(Auth::user()->id, 'Update', 'memperbarui bank keluar', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keperluan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                    Cash::where(['c_id'=>$id])->update([
                        'c_transaksi'   => $keperluan,
                        'c_jumlah'      => $jumlah,
                        'c_tanggal'     => $tanggal,
                        'c_kategori'    => $kategori,
                        'c_akun'        => $dari_akun,
                        'updated_at'    => Carbon::now('Asia/Jakarta')
                    ]);

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui bank keluar",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function deleteBankKredit(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];

                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Delete', 'menghapus bank keluar', date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=> $id])->delete();

                DB::commit();

                $response = [
                    'success'    => true,
                    'message'    => "Berhasil menghapus bank keluar",
                    'error_code' => null,
                    'data'       => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success'    => false,
                    'message'    => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }
    // End Bank

    // Start Kas
    public function saveKasDebet(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules_of_validator = [
                'kategori'   => 'required',
                'keterangan' => 'required',
                'jumlah'     => 'required',
                'tanggal'    => 'required',
                'ke_akun'    => 'required',
            ];

            $message_of_validator = [
                'kategori.required'     => 'Kategori tidak boleh kosong',
                'keterangan.required'   => 'Keterangan tidak boleh kosong',
                'jumlah.required'       => 'Jumlah tidak boleh kosong',
                'tanggal.required'      => 'Tanggal tidak boleh kosong',
                'ke_akun.required'      => 'Ke akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                $kategori   = $request->kategori;
                $keterangan = $request->keterangan;
                $jumlah     = $request->jumlah;
                $tanggal    = date('Y-m-d', strtotime($request->tanggal));
                $ke_akun    = $request->ke_akun;
                
                // try {
                //     $ke_akun     = Crypt::decrypt($ke_akun);
                //     $kategori   = Crypt::decrypt($kategori);
                // } catch (DecryptException $e) {
                //     $response = [
                //         'success'    => false,
                //         'message'    => Helper::errorCode(1402),
                //         'error_code' => 1402,
                //         'data'       => []
                //     ];

                //     return response()->json($response);
                // }

                DB::beginTransaction();
                try{
                    $debit = new Cash;
                    $debit->c_transaksi     = $keterangan;
                    $debit->c_jumlah        = $jumlah;
                    $debit->c_jenis         = "D";
                    $debit->c_tanggal       = $tanggal;
                    $debit->c_kategori      = $kategori;
                    $debit->c_akun          = $ke_akun;
                    $debit->c_flag          = "Pemasukan";
                    $debit->c_flagakun      = "Kas";
                    $debit->c_iduser        = Auth::user()->id;
                    $debit->created_at      = Carbon::now('Asia/Jakarta');
                    $debit->updated_at      = Carbon::now('Asia/Jakarta');
                    $debit->save();

                    $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                    Activity::log(Auth::user()->id, 'Create', 'membuat kas masuk', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keterangan . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . '('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Kas masuk berhasil disimpan",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function updateKasDebet(Request $request)
    {
        if ($request->isMethod('put')) {
            $kategori   = $request->kategori;
            $keterangan = $request->keterangan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $ke_akun    = $request->ke_akun;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                // $ke_akun    = Crypt::decrypt($ke_akun);
                // $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];
                return response()->json($response);
            }

            $rules_of_validator = [
                'kategori'   => 'required',
                'keterangan' => 'required',
                'jumlah'     => 'required',
                'tanggal'    => 'required',
                'ke_akun'    => 'required',
            ];

            $message_of_validator = [
                'kategori.required'     => 'Kategori tidak boleh kosong',
                'keterangan.required'   => 'Keterangan tidak boleh kosong',
                'jumlah.required'       => 'Jumlah tidak boleh kosong',
                'tanggal.required'      => 'Tanggal tidak boleh kosong',
                'ke_akun.required'      => 'Ke akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                    $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                    Activity::log(Auth::user()->id, 'Update', 'memperbarui kas masuk', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keterangan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                    Cash::where(['c_id'=>$id])->update([
                        'c_transaksi'   => $keterangan,
                        'c_jumlah'      => $jumlah,
                        'c_tanggal'     => $tanggal,
                        'c_kategori'    => $kategori,
                        'c_akun'        => $ke_akun,
                        'updated_at'    => Carbon::now('Asia/Jakarta')
                    ]);

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui kas masuk",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function deleteKasDebet(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];

                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Delete', 'menghapus kas masuk', date('d-m-Y', strtotime($cash->c_tanggal)) .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=> $id])->delete();

                DB::commit();

                $response = [
                    'success'    => true,
                    'message'    => "Berhasil menghapus kas masuk",
                    'error_code' => null,
                    'data'       => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success'    => false,
                    'message'    => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function saveKasKredit(Request $request)
    {
        if ($request->isMethod('post')) {
            $rules_of_validator = [
                'kategori'  => 'required',
                'keperluan' => 'required',
                'jumlah'    => 'required',
                'tanggal'   => 'required',
                'dari_akun' => 'required',
            ];

            $message_of_validator = [
                'kategori.required'  => 'Kategori tidak boleh kosong',
                'keperluan.required' => 'Keperluan tidak boleh kosong',
                'jumlah.required'    => 'Jumlah tidak boleh kosong',
                'tanggal.required'   => 'Tanggal tidak boleh kosong',
                'dari_akun.required' => 'Dari akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                $kategori   = $request->kategori;
                $keperluan  = $request->keperluan;
                $jumlah     = $request->jumlah;
                $tanggal    = date('Y-m-d', strtotime($request->tanggal));
                $dari_akun  = $request->dari_akun;

                // try {
                //     $dari_akun = Crypt::decrypt($dari_akun);
                //     $kategori = Crypt::decrypt($kategori);
                // } catch (DecryptException $e) {
                //     $response = [
                //         'status'    => "failed",
                //         'message'   => "A network error occurred. Please try again!"
                //     ];
                //     return response()->json($response);
                // }

                DB::beginTransaction();
                try{
                    $credit = new Cash;
                    $credit->c_transaksi = $keperluan;
                    $credit->c_jumlah    = $jumlah;
                    $credit->c_jenis     = "K";
                    $credit->c_tanggal   = $tanggal;
                    $credit->c_kategori  = $kategori;
                    $credit->c_akun      = $dari_akun;
                    $credit->c_flag      = "Pengeluaran";
                    $credit->c_flagakun  = 'Kas';
                    $credit->c_iduser    = Auth::user()->id;
                    $credit->created_at  = Carbon::now('Asia/Jakarta');
                    $credit->updated_at  = Carbon::now('Asia/Jakarta');
                    $credit->save();

                    $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                    Activity::log(Auth::user()->id, 'Create', 'membuat kas keluar', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keperluan . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . '('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Kas keluar berhasil disimpan",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function updateKasKredit(Request $request)
    {
        if ($request->isMethod('put')) {
            $kategori   = $request->kategori;
            $keperluan  = $request->keperluan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                // $dari_akun  = Crypt::decrypt($dari_akun);
                // $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];
                return response()->json($response);
            }

            $rules_of_validator = [
                'kategori'  => 'required',
                'keperluan' => 'required',
                'jumlah'    => 'required',
                'tanggal'   => 'required',
                'dari_akun' => 'required',
            ];

            $message_of_validator = [
                'kategori.required'  => 'Kategori tidak boleh kosong',
                'keperluan.required' => 'Keperluan tidak boleh kosong',
                'jumlah.required'    => 'Jumlah tidak boleh kosong',
                'tanggal.required'   => 'Tanggal tidak boleh kosong',
                'dari_akun.required' => 'Dari akun tidak boleh kosong',
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                    $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                    Activity::log(Auth::user()->id, 'Update', 'memperbarui kas keluar', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keperluan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                    Cash::where(['c_id'=>$id])->update([
                        'c_transaksi'   => $keperluan,
                        'c_jumlah'      => $jumlah,
                        'c_tanggal'     => $tanggal,
                        'c_kategori'    => $kategori,
                        'c_akun'        => $dari_akun,
                        'updated_at'    => Carbon::now('Asia/Jakarta')
                    ]);

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui kas keluar",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'success'    => false,
                        'message'    => Helper::errorCode(1401),
                        'error_code' => 1401,
                        'data'       => []
                    ];
                }
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function deleteKasKredit(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'success'    => false,
                    'message'    => "Data tidak valid",
                    'error_code' => null,
                    'data'       => []
                ];

                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Delete', 'menghapus kas keluar', date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=> $id])->delete();

                DB::commit();

                $response = [
                    'success'    => true,
                    'message'    => "Berhasil menghapus kas keluar",
                    'error_code' => null,
                    'data'       => []
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'success'    => false,
                    'message'    => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }
    // End Kas
}
