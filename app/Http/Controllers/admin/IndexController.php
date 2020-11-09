<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Identitas;
use App\Layanan;
use App\Syarat;
use App\Kebijakan;
use DB;
use Auth;
use Helper;
use DataTables;
use Session;
use File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class IndexController extends Controller
{
    public function identitasApp(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
        	try {
                $id = Crypt::decrypt($request->get('id'));
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
    		try{
                Identitas::where(['id'=>$id])->update([
                    'title'   		=> $request->get('title'),
                    'deskripsi'     => $request->get('deskripsi'),
                    'userid_update' => Auth::user()->id,
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Update', 'memperbarui identitas APP', null, null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/admin/index/identitas-app')->with('flash_message_success', 'Identitas APP berhasil diperbarui!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/admin/index/identitas-app')->with('flash_message_error', 'Identitas APP gagal diperbarui!');
            }
        }

        $data = Identitas::first();
        $edit = $request->get('edit') != null ? $request->get('edit') : 'false';

    	return view('admin.index.identitas.index')->with(compact('data', 'edit'));
    }

    public function layanan(Request $request)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        return view('admin.index.layanan.index');
    }

    public function getLayanan()
    {
    	$data = Layanan::orderBy('created_at', 'asc');

    	return DataTables::of($data)

            ->addColumn('judul', function ($data) {

                return $data->title;

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/index/layanan/delete/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i> Hapus</a>&nbsp;<a href="'.url('/admin/index/layanan/create?edit=true&id='.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i> Edit</a></p>';

            })

            ->rawColumns(['judul', 'aksi'])

            ->make(true);
    }

    public function layananCreate(Request $request)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
        	$edit = $request->get('edit') == "" ? 'false' : 'true';
        	if ($edit == "true") {
        		try{
		    		$id = Crypt::decrypt($request->get('id'));
		    	}catch(DecryptException $e){
		    		abort(404);
		    	}

		    	if ($request->hasFile('image')) {
	        	    $image_tmp 	= Input::file('image');
	        	    $file 		= $request->file('image');
	        	    $image_size = $image_tmp->getSize(); //getClientSize()
	        	    $maxsize 	= '2097152';
	        	    if ($image_size < $maxsize) {

	        	        if ($image_tmp->isValid()) {

	        	            $namefile = $request->current_img;

	        	            if ($namefile != "") {

	        	                $path = 'public/images/index/' . $namefile;

	        	                if (File::exists($path)) {
	        	                    # code...
	        	                    File::delete($path);
	        	                }

	        	            }

	        	            $extension = $image_tmp->getClientOriginalExtension();
	        	            $filename = 'LYN'.date('YmdHms') . rand(111, 99999) . '.' . $extension;
	        	            $image_path = 'public/images/index';

	        	            if (!is_dir($image_path )) {
	        	                mkdir("public/images/index", 0777, true);
	        	            }

	        	            ini_set('memory_limit', '256M');
	        	            $file->move($image_path, $filename);
	        	        }
	        	    } else {

	        	        return redirect()->back()->with('flash_message_error', 'Icon layanan gagal diupload...! Ukuran file terlalu besar');

	        	    }
	        	} else {
	        		$filename = $request->current_img;
	        	}

	        	DB::beginTransaction();
	        	try{
	        		$d_data = array(
	        			'title' 		=> $request->get('title'),
	        			'description' 	=> $request->get('deskripsi'),
	        			'image'			=> $filename,
	        			'userid_update' => Auth::user()->id,
	        			'updated_at' 	=> Carbon::now('Asia/Jakarta')
	        		);

	                Layanan::where('id', $id)->update($d_data);

	                Activity::log(Auth::user()->id, 'Update', 'memperbarui daftar layanan', $request->get('title'), null, Carbon::now('Asia/Jakarta'));
	        		DB::commit();
	        		return redirect()->back()->with('flash_message_success', 'Daftar layanan berhasil diperbarui!');
	        	}catch(Exception $e){
	        		DB::rollback();
	        		return redirect()->back()->with('flash_message_error', 'Daftar layanan gagal diperbarui!');
	        	}
        	} else {
        		if ($request->hasFile('image')) {
	        	    $image_tmp 	= Input::file('image');
	        	    $file 		= $request->file('image');
	        	    $image_size = $image_tmp->getSize(); //getClientSize()
	        	    $maxsize 	= '2097152';
	        	    if ($image_size < $maxsize) {

	        	        if ($image_tmp->isValid()) {
	        	            $extension = $image_tmp->getClientOriginalExtension();
	        	            $filename = 'LYN'.date('YmdHms') . rand(111, 99999) . '.' . $extension;
	        	            $image_path = 'public/images/index';

	        	            if (!is_dir($image_path )) {
	        	                mkdir("public/images/index", 0777, true);
	        	            }

	        	            ini_set('memory_limit', '256M');
	        	            $file->move($image_path, $filename);
	        	        }
	        	    } else {

	        	        return redirect()->back()->with('flash_message_error', 'Icon layanan gagal diupload...! Ukuran file terlalu besar');

	        	    }
	        	} else {
	        		$filename = '';
	        	}

	        	DB::beginTransaction();
	        	try{
	        		$layanan = new Layanan;
	                $layanan->title 		= $request->get('title');
	                $layanan->description	= $request->get('deskripsi');
	                $layanan->image 		= $filename;
	                $layanan->userid_create = Auth::user()->id;
	                $layanan->userid_update = Auth::user()->id;
	                $layanan->created_at    = Carbon::now('Asia/Jakarta');
	                $layanan->updated_at    = Carbon::now('Asia/Jakarta');
	                $layanan->save();

	                Activity::log(Auth::user()->id, 'Create', 'membuat daftar layanan', $request->get('title'), null, Carbon::now('Asia/Jakarta'));
	        		DB::commit();
	        		return redirect()->back()->with('flash_message_success', 'Daftar layanan berhasil disimpan!');
	        	}catch(Exception $e){
	        		DB::rollback();
	        		return redirect()->back()->with('flash_message_error', 'Daftar layanan gagal disimpan!');
	        	}
        	}
        }

        $edit = $request->get('edit') == "" ? 'false' : 'true';

        if ($edit == "true") {
        	try{
	    		$id = Crypt::decrypt($request->get('id'));
	    	}catch(DecryptException $e){
	    		abort(404);
	    	}

	    	$data = Layanan::where('id', $id)->first();
	    	$url = url('/admin/index/layanan/create?edit=true&id='.$request->get('id'));
        } else {
        	$data = '';
        	$url = url('/admin/index/layanan/create');
        }

        return view('admin.index.layanan.create')->with(compact('edit', 'data', 'url'));
    }

    public function layananDelete($id=null)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

    	try{
    		$id = Crypt::decrypt($id);
    	}catch(DecryptException $e){
    		abort(404);
    	}

    	DB::beginTransaction();
    	try{
    		$layanan = Layanan::where('id', $id);
    		if ($layanan->count() > 0) {
    			if ($layanan->first()->image != "") {
    				$path = 'public/images/index/' . $layanan->first()->image;

    				if (File::exists($path)) {
    				    # code...
    				    File::delete($path);
    				}
    			}

    			$layanan->delete();
    			DB::commit();
    			return redirect()->back()->with('flash_message_success', 'Daftar layanan berhasil dihapus!');
    		} else {
    			return redirect()->back()->with('flash_message_error', 'Daftar layanan tidak ditemukan!');
    		}
    	}catch(Exception $e){
    		DB::rollback();
    		return redirect()->back()->with('flash_message_error', 'Daftar layanan gagal dihapus!');
    	}
    }

    public function syarat(Request $request)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
        	try {
                $id = Crypt::decrypt($request->get('id'));
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
    		try{
                Syarat::where(['id'=>$id])->update([
                    'title'   		=> $request->get('title'),
                    'description'     => $request->get('deskripsi'),
                    'userid_update' => Auth::user()->id,
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Update', 'memperbarui daftar syarat & ketentuan', $request->get('title'), null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/admin/index/syarat')->with('flash_message_success', 'Daftar syarat & ketentuan berhasil diperbarui!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/admin/index/syarat')->with('flash_message_error', 'Daftar syarat & ketentuan gagal diperbarui!');
            }
        }

        $data = Syarat::first();
        $edit = $request->get('edit') != null ? $request->get('edit') : 'false';

    	return view('admin.index.syarat.index')->with(compact('data', 'edit'));
    }

    public function kebijakan(Request $request)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
        	try {
                $id = Crypt::decrypt($request->get('id'));
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
    		try{
                Kebijakan::where(['id'=>$id])->update([
                    'title'   		=> $request->get('title'),
                    'description'   => $request->get('deskripsi'),
                    'userid_update' => Auth::user()->id,
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Update', 'memperbarui daftar kebijakan privasi', $request->get('title'), null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/admin/index/kebijakan')->with('flash_message_success', 'Daftar kebijakan privasi berhasil diperbarui!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/admin/index/kebijakan')->with('flash_message_error', 'Daftar kebijakan privasi gagal diperbarui!');
            }
        }

        $data = Kebijakan::first();
        $edit = $request->get('edit') != null ? $request->get('edit') : 'false';

    	return view('admin.index.kebijakan.index')->with(compact('data', 'edit'));
    }
}
