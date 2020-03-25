<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Helper;
use DataTables;
use Session;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class DashboardController extends Controller
{
    public function dashboard()
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 1) {
                return redirect('/login');
            }
        }
        
    	$data['member_active'] = User::where(['is_active' => 1, 'level' => 2])->count();
    	$data['member_nonactive'] = User::where(['is_active' => 0, 'level' => 2])->count();
    	return view('admin.dashboard.dashboard')->with($data);
    }
}
