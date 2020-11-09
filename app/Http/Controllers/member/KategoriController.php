<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kategori;
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

class KategoriController extends Controller
{
    public $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    function checkCategoriesInCash($cat)
    {
        $check = Cash::where('c_kategori', $cat)->count();
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
                return redirect('/master/kategori')->with('flash_message_success', 'Master kategori berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/master/kategori')->with('flash_message_error', 'Master kategori gagal disimpan!');
            }
    	}

        if ($this->agent->isMobile()) {
            return view('member.master.kategori.mobile.index');
        } else {
            return view('member.master.kategori.index');
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
            $data            = $request->all();
            $id_kategori     = $data['id'];
            $jenis_transaksi = isset($data['jenis_transaksi']) ? $data['jenis_transaksi'] : "";
            $nama            = $data['nama'];
            $keterangan      = $data['keterangan'];
            $warna           = $data['warna'];

            if ($id_kategori == "") {
                DB::beginTransaction();
                try{
                    $cat = new Kategori;
                    $cat->jenis_transaksi = $jenis_transaksi;
                    $cat->nama            = $nama;
                    $cat->keterangan      = $keterangan;
                    $cat->warna           = $warna;
                    $cat->iduser          = Auth::user()->id;
                    $cat->created_at      = Carbon::now('Asia/Jakarta');
                    $cat->updated_at      = Carbon::now('Asia/Jakarta');
                    $cat->save();

                    Activity::log(Auth::user()->id, 'Create', 'membuat master kategori', "(".$jenis_transaksi.") ".$nama, null, Carbon::now('Asia/Jakarta'));

                    DB::commit();
                    $message = array(
                        'status' => "success",
                        'message'=> "Master kategori berhasil disimpan"
                    );
                    return response()->json($message);
                }catch (\Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> "Master kategori gagal disimpan"
                    );
                    return response()->json($message);
                }
            } else {
                try {
                    $id = Crypt::decrypt($id_kategori);
                } catch (DecryptException $e) {
                    $message = array(
                        'status' => "failed",
                        'message'=> "Invalid ID"
                    );
                    return response()->json($message);
                }

                DB::beginTransaction();
                try{
                    $cat = Kategori::where(['id'=> $id])->first();
                    Activity::log(Auth::user()->id, 'Update', 'memperbarui master kategori', 'Diperbarui menjadi "' . $nama.'"', 'Nama kategori sebelumnya "' . $cat->nama . '"', Carbon::now('Asia/Jakarta'));
                    Kategori::where(['id'=>$id])->update([
                        'nama'   => $nama,
                        'keterangan'   => $keterangan,
                        'warna'   => $warna,
                        'updated_at'     => Carbon::now('Asia/Jakarta')
                    ]);
                    DB::commit();
                    $message = array(
                        'status' => "success",
                        'message'=> "Master kategori berhasil diperbarui"
                    );
                    return response()->json($message);
                }catch (\Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> "Master kategori gagal diperbarui"
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

    public function getKategori()
    {
        $data = Kategori::where('iduser', Auth::user()->id)->orderBy('nama','asc');

        return DataTables::of($data)

            ->addColumn('jenis_transaksi', function ($data) {

                return $data->jenis_transaksi;

            })

            ->addColumn('nama', function ($data) {

                return $data->nama;

            })

            ->addColumn('keterangan', function ($data) {

                return $data->keterangan;

            })

            ->addColumn('aksi', function ($data) {

                if ($this->checkCategoriesInCash($data->id) == true) {
                    if ($data->enabled == 1) {
                        if ($this->agent->isMobile()) {
                            $url = url("/mobile/master/kategori/disable");
                            return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan menonaktifkan data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-danger"><ion-icon name="close-outline"></ion-icon>Disable</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                        } else {
                            return '<p class="text-center"><a href="'.url('/master/kategori/disable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menonaktifkan data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                        }
                    } else {
                        if ($this->agent->isMobile()) {
                            $url = url("/mobile/master/kategori/enable");
                            return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan mengaktifkan data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-success"><ion-icon name="checkmark-outline"></ion-icon>Enable</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                        } else {
                            return '<p class="text-center"><a href="'.url('/master/kategori/enable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan mengaktifkan data ini?'.'\')" class="text-success" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                        }
                    }
                } else {
                    if ($this->agent->isMobile()) {
                        $url = url("/mobile/master/kategori/delete");
                        return '<p class="text-center"><a href="#" onclick="confirmMessage(\''. "Konfirmasi" . '\', \''. "Apakah anda yakin akan menghapus data ini?" . '\', \''. Crypt::encrypt($data->id) . '\', \''. $url . '\')" class="btn btn-danger"><ion-icon name="trash-outline"></ion-icon>Hapus</a><a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="btn btn-primary"><ion-icon name="create-outline"></ion-icon>Edit</a></p>';
                    } else {
                        return '<p class="text-center"><a href="'.url('/master/kategori/delete/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                    }
                }

            })

            ->rawColumns(['jenis_transaksi', 'nama', 'keterangan', 'aksi'])

            ->make(true);
    }

    public function destroyKategori($param, $id = null)
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
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->delete();
                DB::commit();
                return redirect('/master/kategori')->with('flash_message_success', 'Berhasil menghapus master kategori!');
            } else if ($param == "enable") {
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->update(['enabled'=>1]);
                DB::commit();
                return redirect('/master/kategori')->with('flash_message_success', 'Berhasil mengaktifkan master kategori!');
            } else if ($param == "disable") {
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->update(['enabled'=>0]);
                DB::commit();
                return redirect('/master/kategori')->with('flash_message_success', 'Berhasil menonaktifkan master kategori!');
            }
        }catch (\Exception $e){
            DB::rollback();
            if ($param == "delete") {
                return redirect('/master/kategori')->with('flash_message_error', 'Gagal menghapus master kategori!');
            } else if ($param == "enable") {
                return redirect('/master/kategori')->with('flash_message_error', 'Gagal mengaktifkan master kategori!');
            } else if ($param == "disable") {
                return redirect('/master/kategori')->with('flash_message_error', 'Gagal menonaktifkan master kategori!');
            }
        }
    }

    public function mobileDestroyKategori(Request $request, $param)
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
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->delete();
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil menghapus master kategori"
                );
                return response()->json($message);
            } else if ($param == "enable") {
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'mengaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->update(['enabled'=>1]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil mengaktifkan master kategori"
                );
                return response()->json($message);
            } else if ($param == "disable") {
                $cat = Kategori::where(['id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Update', 'menonaktifkan master kategori', "(".$cat->jenis_transaksi .') '. $cat->nama, null, Carbon::now('Asia/Jakarta'));
                Kategori::where(['id'=> $id])->update(['enabled'=>0]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Berhasil menonaktifkan master kategori"
                );
                return response()->json($message);
            }
        }catch (\Exception $e){
            DB::rollback();
            if ($param == "delete") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal menghapus master kategori"
                );
                return response()->json($message);
            } else if ($param == "enable") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal mengaktifkan master kategori"
                );
                return response()->json($message);
            } else if ($param == "disable") {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal menonaktifkan master kategori"
                );
                return response()->json($message);
            }
        }
    }

    public function getCurrentKategori(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->get('kid'));
        } catch (DecryptException $e) {
            return Response::json(['status'=>"failed"]);
        }

        $data = Kategori::where('id', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->id),
            'jenis_transaksi' => $data->jenis_transaksi,
            'nama' => $data->nama,
            'keterangan' => $data->keterangan,
            'warna' => $data->warna
        );

        return Response::json($results);

    }

    public function updateKategori(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }
        
    	$data       	= $request->all();
        $nama  			= $data['nama'];
        $keterangan  	= $data['keterangan'];
        $warna  		= $data['warna'];
    	$id         	= $data['id'];

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

    	DB::beginTransaction();
    	try{
            $cat = Kategori::where(['id'=> $id])->first();
            Activity::log(Auth::user()->id, 'Update', 'memperbarui master kategori', 'Diperbarui menjadi "' . $nama.'"', 'Nama kategori sebelumnya "' . $cat->nama . '"', Carbon::now('Asia/Jakarta'));
            Kategori::where(['id'=>$id])->update([
                'nama'   => $nama,
                'keterangan'   => $keterangan,
                'warna'   => $warna,
                'updated_at'     => Carbon::now('Asia/Jakarta')
            ]);
            DB::commit();
            return redirect('/master/kategori')->with('flash_message_success', 'Berhasil mengubah master kategori!');
        }catch (\Exception $e){
    	    DB::rollback();
            return redirect('/master/kategori')->with('flash_message_error', 'Gagal mengubah master kategori!');
        }

    }

}
