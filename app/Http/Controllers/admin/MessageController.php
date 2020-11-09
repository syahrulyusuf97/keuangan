<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Helper;
use DataTables;
use Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class MessageController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }

    	return view('admin.message.index');
    }

    public function messageUnread()
    {
    	$data = DB::table('message')->where(['is_read'=>0, 'is_bookmark'=>0])->orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('date', function ($data) {

                return Carbon::parse($data->created_at)->diffForHumans();

            })

            ->addColumn('name', function ($data) {

                return '<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue">'.$data->name.'</a>';

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('subject', function ($data) {

                return $data->subject;

            })

            ->addColumn('action', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/pesan/bookmark/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Bookmark pesan ini?'.'\')" class="text-warning" style="padding: 4px; font-size: 14px;"><i class="fa fa-star-o text-yellow"></i></a>&nbsp;<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i></a></p>';

            })

            ->rawColumns(['date', 'name', 'email', 'subject', 'action'])

            ->make(true);
    }

    public function messageRead()
    {
    	$data = DB::table('message')->where(['is_read'=>1, 'is_bookmark'=>0])->orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('date', function ($data) {

                return Carbon::parse($data->created_at)->diffForHumans();

            })

            ->addColumn('name', function ($data) {

                return '<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue">'.$data->name.'</a>';

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('subject', function ($data) {

                return $data->subject;

            })

            ->addColumn('action', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/pesan/bookmark/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Bookmark pesan ini?'.'\')" class="text-warning" style="padding: 4px; font-size: 14px;"><i class="fa fa-star-o text-yellow"></i></a>&nbsp;<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i></a></p>';

            })

            ->rawColumns(['date', 'name', 'email', 'subject', 'action'])

            ->make(true);
    }

    public function messageBookmark()
    {
    	$data = DB::table('message')->where(['is_bookmark'=>1])->orderBy('created_at', 'desc');

    	return DataTables::of($data)

            ->addColumn('date', function ($data) {

                return Carbon::parse($data->created_at)->diffForHumans();

            })

            ->addColumn('name', function ($data) {

                return '<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue">'.$data->name.'</a>';

            })

            ->addColumn('email', function ($data) {

                return $data->email;

            })

            ->addColumn('subject', function ($data) {

                return $data->subject;

            })

            ->addColumn('action', function ($data) {

                return '<p class="text-center"><a href="'.url('/admin/pesan/unbookmark/'.Crypt::encrypt($data->id)).'" onclick="return confirm(\''. 'Unbookmark pesan ini?'.'\')" class="text-warning" style="padding: 4px; font-size: 14px;"><i class="fa fa-star text-yellow"></i></a>&nbsp;<a href="'.url('/admin/pesan/read/'.Crypt::encrypt($data->id)).'" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-info"></i></a></p>';

            })

            ->rawColumns(['date', 'name', 'email', 'subject', 'action'])

            ->make(true);
    }

    public function read($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
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
            DB::table('message')->where('id', $id)->update(['is_read' => 1]);
            $message = DB::table('message')->where('id', $id)->first();
            DB::commit();
            return view('admin.message.read')->with(compact('message'));
        }catch (\Exception $e){
            DB::rollback();
            return abort(404);
        }
    }

    public function bookmark($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
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
            DB::table('message')->where('id', $id)->update(['is_bookmark' => 1]);
            DB::commit();
            return redirect()->back();
        }catch (\Exception $e){
            DB::rollback();
            return abort(404);
        }
    }

    public function unBookmark($id = null)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 1) {
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
            DB::table('message')->where('id', $id)->update(['is_bookmark' => 0]);
            DB::commit();
            return redirect()->back();
        }catch (\Exception $e){
            DB::rollback();
            return abort(404);
        }
    }
}
