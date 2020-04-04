<?php

namespace App\Http\Controllers\index;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Identitas;
use App\Layanan;
use App\Syarat;
use App\Kebijakan;
use App\Article;
use Carbon\Carbon;
use DB;

class IndexController extends Controller
{
    public function index()
    {
    	$identitas 	= Identitas::first();
    	$layanan 	= Layanan::get();
    	$syarat 	= Syarat::first();
    	$kebijakan 	= Kebijakan::first();
    	return view('index.index')->with(compact('identitas', 'layanan', 'syarat', 'kebijakan'));
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

    public function article(Request $request)
    {
    	if ($request->get('q') != "") {
    		$article = Article::where('status_publish', 1)->where('title', 'LIKE', '%'.$request->get('q').'%')->orWhere('description', 'LIKE', '%'.$request->get('q').'%')->paginate(5);
    		$keyword = $request->get('q');
    		$article->appends(array('q'=>$keyword));
    	} else {
    		$article = Article::where('status_publish', 1)->inRandomOrder()->paginate(5);
    		$keyword = '';
    	}
    	$identitas 	= Identitas::first();
    	$article_other = Article::where('status_publish', 1)->inRandomOrder()->limit(5)->get();
    	return view('index.article')->with(compact('identitas', 'article', 'article_other', 'keyword'));
    }

    public function articleSlug($slug=null)
    {
    	$identitas 	= Identitas::first();
    	$identitas 	= Identitas::first();
    	$article 	= Article::where('status_publish', 1)->where('slug', $slug);
    	$article_other = Article::where('status_publish', 1)->inRandomOrder()->limit(5)->get();
    	if ($article->count() > 0) {
    		$data = $article->first();
    	} else {
    		abort(404);
    	}
    	return view('index.detailArticle')->with(compact('identitas', 'data', 'article_other'));
    }
}
