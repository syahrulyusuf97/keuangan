<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kategori;
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

class KategoriController extends Controller
{
    function checkCategoriesInCash($cat)
    {
        $check = Cash::where('c_kategori', $cat)->count();
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function saveKategori(Request $request)
    {
        if ($request->isMethod('post')) {
    		# code...
            $rules_of_validator = [
                'jenis_transaksi' => 'required',
                'nama_kategori'   => 'required',
                'warna_label'     => 'required',
            ];

            $message_of_validator = [
                'jenis_transaksi.required' => 'Jenis transaksi tidak boleh kosong',
                'nama_kategori.required'   => 'Nama tidak boleh kosong',
                'warna_label.required'     => 'Warna label tidak boleh kosong',
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
                $data       	 = $request->all();
                $jenis_transaksi = $data['jenis_transaksi'];
                $nama  			 = $data['nama_kategori'];
                $keterangan  	 = $data['keterangan'];
                $warna  	 	 = $data['warna_label'] ?? null;

                DB::beginTransaction();
                try{
                    $cat = new Kategori;
                    $cat->jenis_transaksi = $jenis_transaksi;
                    $cat->nama  		  = $nama;
                    $cat->keterangan 	  = $keterangan;
                    $cat->warna 	 	  = $warna;
                    $cat->iduser     	  = Auth::user()->id;
                    $cat->created_at 	  = Carbon::now('Asia/Jakarta');
                    $cat->updated_at 	  = Carbon::now('Asia/Jakarta');
                    $cat->save();

                    Activity::log(Auth::user()->id, 'Create', 'membuat master kategori', "(".$jenis_transaksi.") ".$nama, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();
                    
                    $response = [
                        'success'    => true,
                        'message'    => "Master kategori berhasil disimpan",
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

    public function getKategori(Request $request)
    {
        if ($request->isMethod('get')) {
            $limit     = $request->limit ?? 5;
            $offset    = $request->offset ?? 0;
            $sort      = $request->sort ?? 'desc';

            $search_jenis_transaksi = $request->jenis_transaksi;
            $search_nama            = $request->nama_kategori;
            $search_keterangan      = $request->keterangan;

            try{
                $data_kategori_count = Kategori::where('iduser', Auth::user()->id)->count();
                
                $data_kategori = Kategori::where('iduser', Auth::user()->id)
                ->when($search_jenis_transaksi, function($query) use ($search_jenis_transaksi) {
                    $query->where('jenis_transaksi', 'like', '%'.$search_jenis_transaksi.'%');
                })
                ->when($search_nama, function($query) use ($search_nama) {
                    $query->where('nama', 'like', '%'.$search_nama.'%');
                })
                ->when($search_keterangan, function($query) use ($search_keterangan) {
                    $query->where('keterangan', 'like', '%'.$search_keterangan.'%');
                });

                $total_filtered = $data_kategori->count();

                $data_kategori_results = $data_kategori
                    ->limit($limit)
                    ->offset($offset)
                    ->orderBy('nama', 'asc')
                    ->get()
                    ->map(function ($items, $key) {
                        $data['id']         = encrypt($items->id);
                        $data['jenis_transaksi']       = $items->jenis_transaksi;
                        $data['nama']       = $items->nama;
                        $data['keterangan'] = $items->keterangan;
                        $data['warna']      = $items->warna;

                        return $data;
                    });

                $results = [
                    'records_total'    => $data_kategori_count,
                    'records_filtered' => $total_filtered,
                    'records_limit'    => $limit,
                    'records_offset'   => $offset,
                    'records'          => $data_kategori_results
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

    public function getAllKategori($jenis_transaksi)
    {
        $data_kategori = Kategori::where('iduser', Auth::user()->id)
        ->where('jenis_transaksi', $jenis_transaksi)
        ->get()
        ->map(function ($items, $key) {
            $data['id']                 = $items->id;
            $data['jenis_transaksi']    = $items->jenis_transaksi;
            $data['nama']       = $items->nama;
            $data['keterangan'] = $items->keterangan;
            $data['warna']      = $items->warna;

            return $data;
        });

        $response = [
            'success'    => true,
            'message'    => 'data available',
            'error_code' => null,
            'data'       => $data_kategori
        ];

        return response()->json($response);
    }

    public function deleteKategori(Request $request, $id=null)
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

            if ($this->checkCategoriesInCash($id)) {
                $response = [
                    'success'    => false,
                    'message'    => "Gagal menghapus master kategori. Kategori tersebut masih terpakai.",
                    'error_code' => null,
                    'data'       => []
                ];
            } else {
                DB::beginTransaction();
                try{
                    $cat = Kategori::where(['id'=> $id])->first();
                    Activity::log(Auth::user()->id, 'Delete', 'menghapus master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                    Kategori::where(['id'=> $id])->delete();
                    DB::commit();
                    
                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil menghapus master kategori",
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

    public function statusKategori(Request $request)
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
                            $cat = Kategori::where(['id'=> $id])->first();
                            Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                            Kategori::where(['id'=> $id])->update(['enabled'=>1]);
                            DB::commit();
                            
                            $response = [
                                'success'    => true,
                                'message'    => "Berhasil mengaktifkan master kategori",
                                'error_code' => null,
                                'data'       => []
                            ];
                        } else {
                            $cat = Kategori::where(['id'=> $id])->first();
                            Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                            Kategori::where(['id'=> $id])->update(['enabled'=>0]);
                            DB::commit();
                            
                            $response = [
                                'success'    => true,
                                'message'    => "Berhasil menonaktifkan master kategori",
                                'error_code' => null,
                                'data'       => []
                            ];
                        }
                    }catch(\Exception $e){
                        DB::rollback();
                        // if ($request->status) {
                        //     $response = [
                        //         'status'    => 'failed',
                        //         'message'   => 'Gagal mengaktifkan master kategori'
                        //     ];
                        // } else {
                        //     $response = [
                        //         'status'    => 'failed',
                        //         'message'   => 'Gagal menonaktifkan master kategori'
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

    public function getCurrentKategori(Request $request, $id=null)
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

            $data = Kategori::where('id', $id);

            if ($data->count() > 0) {
                $results = array(
                    'id' => Crypt::encrypt($data->first()->id),
                    'jenis_transaksi' => $data->first()->jenis_transaksi,
                    'nama_kategori' => $data->first()->nama,
                    'keterangan' => $data->first()->keterangan,
                    // 'warna' => implode('0xff', explode('#', $data->first()->warna))
                    'warna_label' => $data->first()->warna
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
                    'message'    => "Kategori tidak ditemukan",
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

    public function updateKategori(Request $request)
    {
        if ($request->isMethod('put')) {
            $rules_of_validator = [
                'jenis_transaksi' => 'required',
                'nama_kategori'   => 'required',
                'warna_label'     => 'required',
            ];

            $message_of_validator = [
                'jenis_transaksi.required' => 'Jenis transaksi tidak boleh kosong',
                'nama_kategori.required'   => 'Nama tidak boleh kosong',
                'warna_label.required'     => 'Warna label tidak boleh kosong',
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
                $nama       = $request->nama_kategori;
                $keterangan = $request->keterangan;
                $warna      = $request->warna_label;
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
                    $cat = Kategori::where(['id'=> $id])->first();
                    Activity::log(Auth::user()->id, 'Update', 'memperbarui master kategori', 'Diperbarui menjadi "' . $nama.'"', 'Nama kategori sebelumnya "' . $cat->nama . '"', Carbon::now('Asia/Jakarta'));
                    Kategori::where(['id'=>$id])->update([
                        'nama'         => $nama,
                        'keterangan'   => $keterangan,
                        'warna'        => $warna,
                        'updated_at'   => Carbon::now('Asia/Jakarta')
                    ]);
                    DB::commit();
                    
                    $response = [
                        'success'    => true,
                        'message'    => "Berhasil memperbarui master kategori",
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
