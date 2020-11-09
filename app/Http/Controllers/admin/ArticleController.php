<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Article;
use DB;
use Auth;
use Helper;
use DataTables;
use Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class ArticleController extends Controller
{
    public function index()
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

        return view('admin.article.index');
    }

    public function getArticle()
    {
    	$data = Article::orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('judul', function ($data) {

                return $data->title;

            })

            ->addColumn('status', function ($data) {

            	if ($data->status_publish == 0) {
            		$btn_on = 'btn-default';
            		$btn_off = 'btn-success';
            	} else if ($data->status_publish == 1) {
            		$btn_on = 'btn-success';
            		$btn_off = 'btn-default';
            	}
                return '<p class="text-center"><div class="btn-group text-center" data-toggle="btn-toggle">
                  <button type="button" class="btn '.$btn_on.' btn-xs active" data-toggle="on" onclick="statusPublish(\''.Crypt::encrypt($data->id).'\', 1)">On</button>
                  <button type="button" class="btn '.$btn_off.' btn-xs" data-toggle="off" onclick="statusPublish(\''.Crypt::encrypt($data->id).'\', 0)">Off</button>
                </div></p>';

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/article/delete/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i> Hapus</a>&nbsp;<a href="'.url('/admin/article/create?edit=true&id='.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i> Edit</a></p>';

            })

            ->rawColumns(['judul', 'status', 'aksi'])

            ->make(true);
    }

    public function articleCreate(Request $request)
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

		    	$slug = implode('-', explode(" ", strtolower($request->get('title'))));

        		$check_slug = Article::where('slug', $slug)->whereNotIn('id', [$id])->count();

        		if ($check_slug > 0) {
        			return redirect()->back()->with('flash_message_error', 'Judul <i><strong>"'.$request->get('title').'"</strong></i> sudah ada!. Ganti judul artikel Anda.');
        		} else {
        			DB::beginTransaction();
		        	try{
		        		$d_data = array(
		        			'title' 		=> $request->get('title'),
		        			'description' 	=> $request->get('deskripsi'),
		        			'slug'			=> $slug,
		        			'userid_update' => Auth::user()->id,
		        			'updated_at' 	=> Carbon::now('Asia/Jakarta')
		        		);

		                Article::where('id', $id)->update($d_data);

		                Activity::log(Auth::user()->id, 'Update', 'memperbarui daftar artikel', $request->get('title'), null, Carbon::now('Asia/Jakarta'));
		        		DB::commit();
		        		return redirect()->back()->with('flash_message_success', 'Daftar artikel berhasil diperbarui!');
		        	}catch(Exception $e){
		        		DB::rollback();
		        		return redirect()->back()->with('flash_message_error', 'Daftar artikel gagal diperbarui!');
		        	}
        		}
        	} else {
        		$slug = implode('-', explode(" ", strtolower($request->get('title'))));

        		$check_slug = Article::where('slug', $slug)->count();
        		if ($check_slug > 0) {
			        $edit = 'false';
			        $title_denied = $request->get('title') == "" ? '' : $request->get('title');
			        $description_denied = $request->get('deskripsi') == "" ? '' : $request->get('deskripsi');
			        $flash_message_error = 'Judul sudah ada! Ganti judul artikel Anda.';

			        $data = '';
			        $url = url('/admin/article/create');

			        return view('admin.article.create', compact('edit', 'data', 'url', 'title_denied', 'description_denied', 'flash_message_error'));
        			// return redirect()->back()->with('flash_message_error', 'Judul sudah ada! Ganti judul artikel Anda.');
        		} else {
        			DB::beginTransaction();
		        	try{
		        		$article = new Article;
		                $article->title 		= $request->get('title');
		                $article->description	= $request->get('deskripsi');
		                $article->slug 			= $slug;
		                $article->userid_create = Auth::user()->id;
		                $article->userid_update = Auth::user()->id;
		                $article->created_at    = Carbon::now('Asia/Jakarta');
		                $article->updated_at    = Carbon::now('Asia/Jakarta');
		                $article->save();

		                Activity::log(Auth::user()->id, 'Create', 'membuat daftar artikel', $request->get('title'), null, Carbon::now('Asia/Jakarta'));
		        		DB::commit();
		        		return redirect()->back()->with('flash_message_success', 'Daftar artikel berhasil disimpan!');
		        	}catch(Exception $e){
		        		DB::rollback();
		        		return redirect()->back()->with('flash_message_error', 'Daftar artikel gagal disimpan!');
		        	}
        		}
        	}
        }

        $edit = $request->get('edit') == "" ? 'false' : 'true';
        $title_denied = $request->get('title') == "" ? '' : $request->get('title');
        $description_denied = $request->get('deskripsi') == "" ? '' : $request->get('title');

        if ($edit == "true") {
        	try{
	    		$id = Crypt::decrypt($request->get('id'));
	    	}catch(DecryptException $e){
	    		abort(404);
	    	}

	    	$data = Article::where('id', $id)->first();
	    	$url = url('/admin/article/create?edit=true&id='.$request->get('id'));
        } else {
        	$data = '';
        	$url = url('/admin/article/create');
        }

        return view('admin.article.create')->with(compact('edit', 'data', 'url', 'title_denied', 'description_denied'));
    }

    public function status($id=null, $status_publish=null)
    {
    	if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return json_encode(['status'=>'Failed','message'=>'Akses Ditolak']);
            }
        } else {
        	return json_encode(['status'=>'Failed','message'=>'Akses Ditolak']);
        }

        try{
    		$id = Crypt::decrypt($id);
    	}catch(DecryptException $e){
    		return json_encode(['status'=>'Failed','message'=>'Data tidak ditemukan']);
    	}

    	if ($status_publish == 0) {
			$status = 'Off';
			$update = true;
		} else if ($status_publish == 1) {
			$status = 'On';
			$update = true;
		} else {
			$update = false;
		}

		if ($update == true) {
			DB::beginTransaction();
	    	try{
	    		$d_data = array(
	    			'status_publish' => $status_publish
	    		);

	            $article = Article::where('id', $id);

	    		$article->update($d_data);

	            $note_log = "Artikel ". '"'.$article->first()->title.'" status publish '.'"'.$status.'"';

	            Activity::log(Auth::user()->id, 'Update', 'memperbarui status artikel', $note_log, null, Carbon::now('Asia/Jakarta'));

	            
	    		DB::commit();
	    		return json_encode(['status'=>'Success','message'=>'Status publikasi '.$status]);
	    	}catch(Exception $e){
	    		DB::rollback();
	    		return json_encode(['status'=>'Failed','message'=>'Terjadi kesalahan, coba lagi!']);
	    	}
		} else {
			return json_encode(['status'=>'Failed','message'=>'Terjadi kesalahan, coba lagi!']);
		}
    }

    public function articleDelete($id=null)
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
    		$article = Article::where('id', $id);
    		if ($article->count() > 0) {
    			$article->delete();
    			DB::commit();
    			return redirect()->back()->with('flash_message_success', 'Daftar artikel berhasil dihapus!');
    		} else {
    			return redirect()->back()->with('flash_message_error', 'Daftar artikel tidak ditemukan!');
    		}
    	}catch(Exception $e){
    		DB::rollback();
    		return redirect()->back()->with('flash_message_error', 'Daftar artikel gagal dihapus!');
    	}
    }
}
