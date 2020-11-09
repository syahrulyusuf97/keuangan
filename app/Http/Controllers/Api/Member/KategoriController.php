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
    		$data       	 = $request->all();
            $jenis_transaksi = $data['jenis_transaksi'];
            $nama  			 = $data['nama'];
            $keterangan  	 = $data['keterangan'];
            $warna  	 	 = $data['warna'];

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
                    'status'    => "success",
                    'message'   => "Master kategori berhasil disimpan"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Master kategori gagal disimpan"
                ];
            }
            return response()->json($response);
    	} else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function getKategori(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $data = Kategori::where('iduser', Auth::user()->id)->orderBy('nama','asc');
                $kategori = [];

                if ($data->count() > 0) {
                    foreach ($data->get() as $key => $value) {
                        $kategori[] = array(
                            'id'                => Crypt::encrypt($value->id),
                            'jenis_transaksi'   => $value->jenis_transaksi,
                            'nama_kategori'     => $value->nama,
                            'keterangan'        => $value->keterangan,
                        );
                    }
                }

                $response = [
                    'status' => 'success',
                    'data'   => $kategori
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
            }
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function deleteKategori(Request $request, $id=null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => 'error',
                    'message'   => 'A network error occurred. Please try again!'
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->delete();
                DB::commit();
                $response = [
                    'status'    => 'success',
                    'message'   => 'Berhasil menghapus master kategori'
                ];
            }catch(\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => 'failed',
                    'message'   => 'Gagal menghapus master kategori'
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function statusKategori(Request $request)
    {
        if ($request->isMethod('put')) {
            if (is_bool($request->status)) {
                try {
                    $id = Crypt::decrypt($request->id);
                } catch (DecryptException $e) {
                    $response = [
                        'status'    => 'error',
                        'message'   => 'A network error occurred. Please try again!'
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
                            'status'    => 'success',
                            'message'   => 'Berhasil mengaktifkan master kategori'
                        ];
                    } else {
                        $cat = Kategori::where(['id'=> $id])->first();
                        Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                        Kategori::where(['id'=> $id])->update(['enabled'=>0]);
                        DB::commit();
                        $response = [
                            'status'    => 'success',
                            'message'   => 'Berhasil menonaktifkan master kategori'
                        ];
                    }
                }catch(\Exception $e){
                    DB::rollback();
                    if ($request->status) {
                        $response = [
                            'status'    => 'failed',
                            'message'   => 'Gagal mengaktifkan master kategori'
                        ];
                    } else {
                        $response = [
                            'status'    => 'failed',
                            'message'   => 'Gagal menonaktifkan master kategori'
                        ];
                    }
                }
                return response()->json($response);
            } else {
                $response = [
                    'status'    => 'error',
                    'message'   => 'Parameter status harus tipe boolean'
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function getCurrentKategori(Request $request, $id=null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => 'error',
                    'message'   => 'A network error occurred. Please try again!'
                ];
                return response()->json($response);
            }

            $data = Kategori::where('id', $id);

            if ($data->count() > 0) {
                $results = array(
                    'id' => Crypt::encrypt($data->id),
                    'jenis_transaksi' => $data->jenis_transaksi,
                    'nama' => $data->nama,
                    'keterangan' => $data->keterangan,
                    'warna' => implode('0xff', explode('#', $data->warna))
                );

                $response = [
                    'status' => 'success',
                    'data'   => $results
                ];
            } else {
                $response = [
                    'status'    => 'failed',
                    'message'   => 'Akun tidak ditemukan'
                ];
            }

            return Response::json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function updateKategori(Request $request)
    {
        if ($request->isMethod('put')) {
            $nama           = $request->nama;
            $keterangan     = $request->keterangan;
            $warna          = $request->warna;
            $id             = $request->id;

            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => 'error',
                    'message'   => 'A network error occurred. Please try again!'
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
                    'status'    => 'success',
                    'message'   => 'Berhasil memperbarui master kategori'
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => 'failed',
                    'message'   => 'Gagal memperbarui master kategori'
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }

    }

}
