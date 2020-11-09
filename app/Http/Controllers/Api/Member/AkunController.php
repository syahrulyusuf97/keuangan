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
                    'status'    => "success",
                    'message'   => "Master akun berhasil disimpan"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Master akun gagal disimpan"
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

    public function getAkun(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $data = Akun::where('iduser', Auth::user()->id)->orderBy('kode_akun','asc');
                $akun = [];

                if ($data->count() > 0) {
                    foreach ($data->get() as $key => $value) {
                        $akun[] = array(
                            'id'         => Crypt::encrypt($value->id),
                            'kode_akun'  => $value->kode_akun,
                            'nama_akun'  => $value->nama_akun,
                            'jenis_akun' => $value->jenis_akun,
                        );
                    }
                }

                $response = [
                    'status' => 'success',
                    'data'   => $akun
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
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

    public function deleteAkun(Request $request, $id=null)
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
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->delete();
                DB::commit();
                $response = [
                    'status'    => 'success',
                    'message'   => 'Berhasil menghapus master akun'
                ];
            }catch(\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => 'failed',
                    'message'   => 'Gagal menghapus master akun'
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

    public function statusAkun(Request $request)
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
                        $akun = Akun::where(['id'=> $id])->first();
                        Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                        Akun::where(['id'=> $id])->update(['enabled'=>1]);
                        DB::commit();
                        $response = [
                            'status'    => 'success',
                            'message'   => 'Berhasil mengaktifkan master akun'
                        ];
                    } else {
                        $akun = Akun::where(['id'=> $id])->first();
                        Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                        Akun::where(['id'=> $id])->update(['enabled'=>0]);
                        DB::commit();
                        $response = [
                            'status'    => 'success',
                            'message'   => 'Berhasil menonaktifkan master akun'
                        ];
                    }
                }catch (\Exception $e){
                    DB::rollback();
                    if ($request->status) {
                        $response = [
                            'status'    => 'failed',
                            'message'   => 'Gagal mengaktifkan master akun'
                        ];
                    } else {
                        $response = [
                            'status'    => 'failed',
                            'message'   => 'Gagal menonaktifkan master akun'
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

    public function getCurrentAkun(Request $request, $id=null)
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

            $data = Akun::where('id', $id);

            if ($data->count() > 0) {
                $results = array(
                    'id' => Crypt::encrypt($data->first()->id),
                    'kode_akun' => $data->first()->kode_akun,
                    'nama_akun' => $data->first()->nama_akun,
                    'jenis_akun' => $data->first()->jenis_akun
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

    public function updateAkun(Request $request)
    {
        if ($request->isMethod('put')) {
            $nama_akun  = $request->nama_akun;
            $id         = $request->id;

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
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'memperbarui master akun', 'Diperbarui menjadi "' . $nama_akun.'"', 'Nama akun sebelumnya "' . $akun->nama_akun . '"', Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=>$id])->update([
                    'nama_akun'   => $nama_akun,
                    'updated_at'     => Carbon::now('Asia/Jakarta')
                ]);
                DB::commit();
                $response = [
                    'status'    => 'success',
                    'message'   => 'Berhasil memperbarui master akun'
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => 'failed',
                    'message'   => 'Gagal memperbarui master akun'
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
