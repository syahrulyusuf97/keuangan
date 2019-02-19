<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\User;
use Illuminate\Support\Facades\Hash;
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
        Session::flush();
        Auth::logout();
        return redirect('/')->with('flash_message_success', 'Logged out berhasil');
    }

    public function checkPassword(Request $request) {
        $data = $request->all();
        $current_password = $data['current_pwd'];
        $check_password = User::where(['admin'=>'1'])->first();
        if (Hash::check($current_password, $check_password->password)) {
            # code...
            echo("true"); die;
        } else {
            echo("false"); die;
        }
    }

    public function updatePassword(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $data = $request->all();
            $check_password = User::where(['email' => Auth::user()->email])->first();
            $current_password = $data['current_pwd'];
            if (Hash::check($current_password, $check_password->password)) {
                # code...
                $password = bcrypt($data['new_pwd']);
                User::where('id', '1')->update(['password'=>$password]);
                return redirect('/admin/settings')->with('flash_message_success', 'Password update successfully!');
            } else {
                return redirect('/admin/settings')->with('flash_message_error', 'Incorrect current password!');
            }
        }
    }
}
