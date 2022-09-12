<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Akun;
use App\Cash;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;
use Auth;
use Helper;
use Validator;

class AkunController extends Controller
{

    function checkAccountInCash($akun)
    {
        $check = Cash::where('c_akun', $akun)->count();
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveAkun(Request $request)
    {
    	if ($request->isMethod('post')) {
    		# code...
            $rules_of_validator = [
                'jenis_akun'=> 'required',
                'nama_akun' => 'required',
            ];

            $message_of_validator = [
                'jenis_akun.required' => 'Jenis akun tidak boleh kosong',
                'nama_akun.required'  => 'Nama akun tidak boleh kosong',
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
                $data       = $request->all();
                $jenis_akun = $data['jenis_akun'];
                $nama_akun  = $data['nama_akun'];
                $kode       = $jenis_akun == "Kas" ? "101" : "201";
                $kode_akun  = Helper::accountCode('ms_akun', 'kode_akun', $jenis_akun, $kode, 1, 3, 2);

                DB::beginTransaction();
                try{
                    $akun = new Akun;
                    $akun->kode_akun  = $kode_akun;
                    $akun->nama_akun  = $nama_akun;
                    $akun->jenis_akun = $jenis_akun;
                    $akun->iduser     = Auth::user()->id;
                    $akun->created_at = Carbon::now('Asia/Jakarta');
                    $akun->updated_at = Carbon::now('Asia/Jakarta');
                    $akun->save();

                    Activity::log(Auth::user()->id, 'Create', 'membuat master akun', "(".$kode_akun.") ".$nama_akun, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();

                    $response = [
                        'success'    => true,
                        'message'    => "Master akun berhasil disimpan",
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

    public function getAkun(Request $request)
    {
        if ($request->isMethod('get')) {
            $limit     = $request->limit ?? 5;
            $offset    = $request->offset ?? 0;
            $sort      = $request->sort ?? 'desc';

            $search_kode_akun  = $request->kode_akun;
            $search_nama_akun  = $request->nama_akun;
            $search_jenis_akun = $request->jenis_akun;

            try{
                $data_akun_count = Akun::where('iduser', Auth::user()->id)->count();

                $data_akun = Akun::where('iduser', Auth::user()->id)
                ->when($search_kode_akun, function($query) use ($search_kode_akun) {
                    $query->where('kode_akun', 'like', '%'.$search_kode_akun.'%');
                })
                ->when($search_nama_akun, function($query) use ($search_nama_akun) {
                    $query->where('nama_akun', 'like', '%'.$search_nama_akun.'%');
                })
                ->when($search_jenis_akun, function($query) use ($search_jenis_akun) {
                    $query->where('jenis_akun', 'like', '%'.$search_jenis_akun.'%');
                });

                $total_filtered = $data_akun->count();

                $data_akun_results = $data_akun
                    ->limit($limit)
                    ->offset($offset)
                    ->orderBy('kode_akun', 'asc')
                    ->get()
                    ->map(function ($items, $key) {
                        $data['id']        = encrypt($items->id);
                        $data['kode_akun'] = $items->kode_akun;
                        $data['nama_akun'] = $items->nama_akun;
                        $data['jenis_akun']= $items->jenis_akun;

                        return $data;
                    });

                $results = [
                    'records_total'    => $data_akun_count,
                    'records_filtered' => $total_filtered,
                    'records_limit'    => $limit,
                    'records_offset'   => $offset,
                    'records'          => $data_akun_results
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

    public function deleteAkun(Request $request, $id=null)
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
            
            if ($this->checkAccountInCash($id)) {
                $response = [
                    'success'    => false,
                    'message'    => "Gagal menghapus master akun. Akun tersebut masih terpakai.",
                    'error_code' => null,
                    'data'       => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $akun = Akun::where(['id'=> $id])->first();
                    Activity::log(Auth::user()->id, 'Delete', 'menghapus master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                    Akun::where(['id'=> $id])->delete();
                    DB::commit();
                    
                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil menghapus master akun",
                        'error_code' => null,
                        'data'       => []
                    ];
                }catch(\Exception $e){
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

    public function statusAkun(Request $request)
    {
        if ($request->isMethod('put')) {
            $rules_of_validator = [
                'status'=> 'required',
            ];

            $message_of_validator = [
                'status.required' => 'Status tidak boleh kosong',
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
                if (is_bool($request->status)) {
                    try {
                        $id = Crypt::decrypt($request->id);
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
                        if ($request->status) {
                            $akun = Akun::where(['id'=> $id])->first();
                            Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                            Akun::where(['id'=> $id])->update(['enabled'=>1]);
                            DB::commit();
                            
                            $response = [
                                'success'    => true,
                                'message'    => "Berhasil mengaktifkan master akun",
                                'error_code' => null,
                                'data'       => []
                            ];
                        } else {
                            $akun = Akun::where(['id'=> $id])->first();
                            Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                            Akun::where(['id'=> $id])->update(['enabled'=>0]);
                            DB::commit();
                            
                            $response = [
                                'success'    => true,
                                'message'    => "Berhasil menonaktifkan master akun",
                                'error_code' => null,
                                'data'       => []
                            ];
                        }
                    }catch (\Exception $e){
                        DB::rollback();
                        // if ($request->status) {
                        //     $response = [
                        //         'status'    => 'failed',
                        //         'message'   => 'Gagal mengaktifkan master akun'
                        //     ];
                        // } else {
                        //     $response = [
                        //         'status'    => 'failed',
                        //         'message'   => 'Gagal menonaktifkan master akun'
                        //     ];
                        // }
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
                        'message'    => 'Parameter status harus tipe boolean',
                        'error_code' => null,
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

    public function getCurrentAkun(Request $request, $id=null)
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

            $data = Akun::where('id', $id);

            if ($data->count() > 0) {
                $results = array(
                    'id' => Crypt::encrypt($data->first()->id),
                    'kode_akun' => $data->first()->kode_akun,
                    'nama_akun' => $data->first()->nama_akun,
                    'jenis_akun' => $data->first()->jenis_akun
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
                    'message'    => "Akun tidak ditemukan",
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

        return Response::json($response);
    }

    public function getAllAkun($jenis_akun)
    {
        $data_akun = Akun::where('iduser', Auth::user()->id)
        ->where('jenis_akun', $jenis_akun)
        ->get()
        ->map(function ($items, $key) {
            $data['id']        = $items->id;
            $data['kode_akun'] = $items->kode_akun;
            $data['nama_akun'] = $items->nama_akun;
            $data['akun'] = "(".$items->kode_akun.") ".$items->nama_akun;
            $data['jenis_akun']= $items->jenis_akun;

            return $data;
        });

        $response = [
            'success'    => true,
            'message'    => 'data available',
            'error_code' => null,
            'data'       => $data_akun
        ];

        return response()->json($response);
    }

    public function updateAkun(Request $request)
    {
        if ($request->isMethod('put')) {
            $rules_of_validator = [
                'nama_akun'=> 'required',
            ];

            $message_of_validator = [
                'nama_akun.required' => 'Nama akun tidak boleh kosong',
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
                $nama_akun  = $request->nama_akun;
                $id         = $request->id;

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
                    $akun = Akun::where(['id'=> $id])->first();
                    Activity::log(Auth::user()->id, 'Update', 'memperbarui master akun', 'Diperbarui menjadi "' . $nama_akun.'"', 'Nama akun sebelumnya "' . $akun->nama_akun . '"', Carbon::now('Asia/Jakarta'));
                    Akun::where(['id'=>$id])->update([
                        'nama_akun'   => $nama_akun,
                        'updated_at'     => Carbon::now('Asia/Jakarta')
                    ]);
                    DB::commit();
                    
                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui master akun",
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

}
