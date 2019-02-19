<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;

class SignController extends Controller
{
    public function login(Request $request) {
		if (Session::has('adminSession')) {
            return redirect('/dashboard');

        }
    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->input();
    		if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password']])) {
    			# code...
    			$data = User::where('username', $data['username'])->first();
    				
    			Session::put('adminSession', $data->email);
				Session::put('adminName', $data->name);

				User::where('id', $data->id)->update([
				    'login' => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log($data->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                // print_r($data);
    			return redirect('/dashboard');
    		} else {
    			return redirect('/')->with('flash_message_error', 'Username atau password salah');
    		}
    	}
    	return view('sign.login');
    }

    public function logout() {
        User::where('id', Auth::user()->id)->update([
            'logout' => Carbon::now('Asia/Jakarta')
        ]);
        Activity::log(Auth::user()->id, 'Logout', 'Logout', 'IP Address: '. \Request::ip() . ' Device: '. \Request::header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
        Session::flush();
        Auth::logout();
        return redirect('/')->with('flash_message_success', 'Logged out berhasil');
    }
}
