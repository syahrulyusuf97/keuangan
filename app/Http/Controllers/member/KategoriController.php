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
use DataTables;
use DB;
use Response;
use Auth;
use Helper;
use Session;

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

    public function add(Request $request)
    {
        if (Session::has('adminSession')) {
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

    	return view('member.master.kategori.index');
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
                        return '<p class="text-center"><a href="'.url('/master/kategori/disable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menonaktifkan data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-times"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                    } else {
                        return '<p class="text-center"><a href="'.url('/master/kategori/enable/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan mengaktifkan data ini?'.'\')" class="text-success" style="padding: 4px; font-size: 14px;"><i class="fa fa-check"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                    }
                } else {
                    return '<p class="text-center"><a href="'.url('/master/kategori/delete/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';
                }

            })

            ->rawColumns(['jenis_transaksi', 'nama', 'keterangan', 'aksi'])

            ->make(true);
    }

    public function destroyKategori($param, $id = null)
    {
        if (Session::has('adminSession')) {
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
        if (Session::has('adminSession')) {
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
