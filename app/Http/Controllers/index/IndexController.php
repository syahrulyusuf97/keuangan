<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;

class IndexController extends Controller
{
    public function index()
    {
    	return view('index.index');
    }

    public function message(Request $request)
    {
    	if ($request->isMethod('post')) {
    		$data = $request->all();
	    	DB::beginTransaction();
	    	try{
	    		$pesan = array(
	    			'name' => $data['nama'],
	    			'email' => $data['email'],
	    			'subject' => $data['subyek'],
	    			'message' => $data['pesan'],
	    			'created_at' => Carbon::now('Asia/Jakarta'),
	    			'is_read' => 0
	    		);
	    		DB::table('message')->insert($pesan);
	    		DB::commit();
	    		return redirect()->back()->with('flash_message_success', 'Pesan Anda berhasil terkirim!');
	    	}catch(Exception $e){
	    		DB::rollback();
	    		return redirect()->back()->with('flash_message_error', 'Pesan Anda gagal terkirim!');
	    	}
    	} else {
    		abort(404);
    	}
    }
}
