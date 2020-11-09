<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Akun;
use App\Cash;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Jenssegers\Agent\Agent;
use DataTables;
use DB;
use Response;
use Auth;
use Helper;
use Session;

class AkunController extends Controller
{
    public $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    function checkAccountInCash($akun)
    {
        $check = Cash::where('c_akun', $akun)->count();
        if ($check > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function add(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

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
                return redirect('/master/akun')->with('flash_message_success', 'Master akun berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/master/akun')->with('flash_message_error', 'Master akun gagal disimpan!');
            }
    	}

        if ($this->agent->isMobile()) {
            return view('member.master.akun.mobile.index');
        } else {
            return view('member.master.akun.index');
        }
    }

    public function mobileAdd(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session"
            );
            return response()->json($message);
        }

        if ($request->isMethod('post')) {
            # code...
            $data       = $request->all();
            $id_akun    = $data['id'];
            $jenis_akun = isset($data['jenis_akun'])? $data['jenis_akun'] : "";
            $nama_akun  = $data['nama_akun'];
            $kode       = $jenis_akun == "Kas" ? "101" : "201";
            $kode_akun  = Helper::accountCode('ms_akun', 'kode_akun', $jenis_akun, $kode, 1, 3, 2);

            if ($id_akun == "") {
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
                    $message = array(
                        'status' => "success",
                        'message'=> "Master akun berhasil disimpan"
                    );
                    return response()->json($message);
                }catch (\Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> "Master akun gagal disimpan"
                    );
                    return response()->json($message);
                }
            } else {
                try {
                    $id = Crypt::decrypt($id_akun);
                } catch (DecryptException $e) {
                    $message = array(
                        'status' => "failed",
                        'message'=> "Invalid ID"
                    );
                    return response()->json($message);
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
                    $message = array(
                        'status' => "success",
                        'message'=> "Master akun berhasil diperbarui"
                    );
                    return response()->json($message);
                }catch (\Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> "Master akun gagal diperbarui"
                    );
                    return response()->json($message);
                }
            }
        } else {
            $message = array(
                'status' => "failed",
                'message'=> "Not Allowed Method"
            );
            return response()->json($message);
        }
    }

    public function getAkun()
    {
        $data = Akun::where('iduser', Auth::user()->id)->orderBy('kode_akun','asc');

        return DataTables::of($data)

            ->addColumn('kode', function ($data) {

                return $data->kode_akun;

            })

            ->addColumn('akun', function ($data) {

                return $data->nama_akun;

            })

            ->addColumn('jenis_akun', function ($data) {

                return $data->jenis_akun;

            })

            ->addColumn('aksi', function ($data) {

                if ($this->checkAccountInCash($data->id) == true) {
                    if ($data->enabled == 1) {
                        if ($this->agent->isMobile()) {
                            $url = url("/mobile/master/akun/disable");
                            return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan menonaktifkan data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-danger"><ion-icon name="close-outline"></ion-icon>Disable</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\')" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                        } else {
                            return '<p class="text-center"><a href="'.url('/master/akun/disable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menonaktifkan data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i></a>&nbsp;||&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                        }
                    } else {
                        if ($this->agent->isMobile()) {
                            $url = url("/mobile/master/akun/enable");
                            return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan mengaktifkan data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-success"><ion-icon name="checkmark-outline"></ion-icon>Enable</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                        } else {
                            return '<p class="text-center"><a href="'.url('/master/akun/enable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan mengaktifkan data ini?'.'\')" class="text-success" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i></a>&nbsp;||&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                        }
                    }
                } else {
                    if ($this->agent->isMobile()) {
                        $url = url("/mobile/master/akun/delete");
                        return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan menghapus data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon>Hapus</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                    } else {
                        return '<p class="text-center"><a href="'.url('/master/akun/delete/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;||&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                    }
                }

            })

            ->rawColumns(['kode', 'akun', 'jenis_akun', 'aksi'])

            ->make(true);
    }

    public function destroyAkun($param, $id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            if ($param == "delete") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->delete();
                DB::commit();
                return redirect('/master/akun')->with('flash_message_success', 'Berhasil menghapus master akun!');
            } else if ($param == "enable") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->update(['enabled'=>1]);
                DB::commit();
                return redirect('/master/akun')->with('flash_message_success', 'Berhasil mengaktifkan master akun!');
            } else if ($param == "disable") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->update(['enabled'=>0]);
                DB::commit();
                return redirect('/master/akun')->with('flash_message_success', 'Berhasil menonaktifkan master akun!');
            }
        }catch (\Exception $e){
            DB::rollback();
            if ($param == "delete") {
                return redirect('/master/akun')->with('flash_message_error', 'Gagal menghapus master akun!');
            } else if ($param == "enable") {
                return redirect('/master/akun')->with('flash_message_error', 'Gagal mengaktifkan master akun!');
            } else if ($param == "disable") {
                return redirect('/master/akun')->with('flash_message_error', 'Gagal menonaktifkan master akun!');
            }
        }
    }

    public function mobileDestroyAkun(Request $request, $param)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session"
            );
            return response()->json($message);
        }

        try {
            $id = Crypt::decrypt($request->confirm_id);
        } catch (DecryptException $e) {
            $message = array(
                'status' => "failed",
                'message'=> "ID tidak diketahui"
            );
            return response()->json($message);
        }

        DB::beginTransaction();
        try{
            if ($param == "delete") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->delete();
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil menghapus master akun"
                );
                return response()->json($message);
            } else if ($param == "enable") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->update(['enabled'=>1]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil mengaktifkan master akun"
                );
                return response()->json($message);
            } else if ($param == "disable") {
                $akun = Akun::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master akun', "(".$akun->kode_akun .') '. $akun->nama_akun, null, Carbon::now('Asia/Jakarta'));
                Akun::where(['id'=> $id])->update(['enabled'=>0]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil menonaktifkan master akun"
                );
                return response()->json($message);
            }
        }catch (\Exception $e){
            DB::rollback();
            if ($param == "delete") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal menghapus master akun"
                );
                return response()->json($message);
            } else if ($param == "enable") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal mengaktifkan master akun"
                );
                return response()->json($message);
            } else if ($param == "disable") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal menonaktifkan master akun"
                );
                return response()->json($message);
            }
        }
    }

    public function getCurrentAkun(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->get('aid'));
        } catch (DecryptException $e) {
            return Response::json(['status'=>"failed"]);
        }

        $data = Akun::where('id', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->id),
            'kode_akun' => $data->kode_akun,
            'nama_akun' => $data->nama_akun,
            'jenis_akun' => $data->jenis_akun
        );

        return Response::json($results);

    }

    public function updateAkun(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }
        
    	$data       = $request->all();
        $nama_akun  = $data['nama_akun'];
    	$id         = $data['id'];

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
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
            return redirect('/master/akun')->with('flash_message_success', 'Berhasil memperbarui master akun!');
        }catch (\Exception $e){
    	    DB::rollback();
            return redirect('/master/akun')->with('flash_message_error', 'Gagal memperbarui master akun!');
        }

    }

}
