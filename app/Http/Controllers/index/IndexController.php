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
use Mail;
// use Webklex\IMAP\Facades\Client;

class IndexController extends Controller
{
    public function index()
    {
        // $client = Client::account('default');
        // $client->connect();
        //Get all Mailboxes
        // $folders = $client->getFolders();

        // Get a specific folder:
        // $folder = $client->getFolder('INBOX.name');

        //Loop through every Mailbox
        /** @var \Webklex\PHPIMAP\Folder $folder */
        // foreach($folders as $folder){

        //     //Get all Messages of the current Mailbox $folder
        //     /** @var \Webklex\PHPIMAP\Support\MessageCollection $messages */
        //     $messages = $folder->messages()->all()->get();
            
        //     /** @var \Webklex\PHPIMAP\Message $message */
        //     foreach($messages as $message){
        //         echo $message->getSubject().'<br />';
        //         echo 'Attachments: '.$message->getAttachments()->count().'<br />';
        //         echo $message->getHTMLBody();
                
        //         //Move the current Message to 'INBOX.read'
        //         // if($message->moveToFolder('INBOX.read') == true){
        //         //     echo 'Message has ben moved';
        //         // }else{
        //         //     echo 'Message could not be moved';
        //         // }
        //     }
        // }

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
            $rules = ['ccaptcha' => 'required|captcha'];
            $validator = validator()->make($data, $rules);
            if ($validator->fails()) {
                $pesan = array(
                    'cname' => $data['nama'],
                    'cemail' => $data['email'],
                    'csubject' => $data['subyek'],
                    'cmessage' => $data['pesan'],
                    'flash_message_error' => 'Captcha Invalid!'
                );
                return redirect('/#contact')->with($pesan);
            } else {
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
                    $this->messageMail(urlencode($data['nama']), urlencode($data['email']), urlencode($data['subyek']), urlencode($data['pesan']));
                    return redirect('/#contact')->with('flash_message_success', 'Pesan Anda berhasil terkirim!');
                }catch(Exception $e){
                    DB::rollback();
                    return redirect('/#contact')->with('flash_message_error', 'Pesan Anda gagal terkirim!');
                }
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

    private function messageMail($name=null, $email=null, $subject=null, $message=null)
    {
        try {
            Mail::to("syahrulyusuf52@gmail.com")->send(new \App\Mail\SendMessage($name, $email, $subject, $message));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function refreshCaptcha()
    {
        return response()->json(['captcha'=> captcha_img()]);
    }
}
