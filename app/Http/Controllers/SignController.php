<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Mail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;

class SignController extends Controller
{
    public function login(Request $request) {
		if (Session::has('adminSession')) {
            if (Auth::user()->level == 1) {
                return redirect('/admin/dashboard');
            } else if (Auth::user()->level == 2) {
                return redirect('/dashboard');
            }
        }

    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->input();
    		if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1])) {
    			# code...
    			$data = User::where('username', $data['username'])->first();
    				
    			Session::put('adminSession', $data->email);
				Session::put('adminName', $data->name);

				User::where('id', $data->id)->update([
				    'login' => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log($data->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                if ($data->level == 1) {
                    return redirect('/admin/dashboard');
                } else if ($data->level == 2) {
                    return redirect('/dashboard');
                } else {
                    return redirect('/logout');
                }
    		} else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>0])) {
                return redirect()->back()->with('flash_message_error', 'Akun Anda belum diaktivasi!');
            } else {
    			return redirect()->back()->with('flash_message_error', 'Username atau password salah');
    		}
    	}
    	return view('sign.login');
    }

    public function registrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $data = $request->input();
            $check_mail = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            if ($check_mail > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, email sudah terdaftar!');
            } else if ($check_username > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, username sudah terdaftar!');
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['name']),
                        'sex'           => $data['sex'],
                        'email'         => $data['email'],
                        'username'      => $data['username'],
                        'password'      => bcrypt($data['password']),
                        'is_active'     => 1,
                        'level'         => 2,//member
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();
                    // $send_mail = $this->konfirmasiEmail(Crypt::encrypt($user), $data['email'], $data['name']);
                    // if ($send_mail == TRUE) {
                    //     $message = array(
                    //         'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Kami telah mengirimkan konfirmasi ke email Anda',
                    //         'resendmail' => Crypt::encrypt($user)
                    //     );
                    //     return redirect('/registrasi')->with($message);
                    // } else {
                    //     $message = array(
                    //         'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Gagal mengirimkan konfirmasi ke email Anda',
                    //         'resendmail' => Crypt::encrypt($user)
                    //     );
                    //     return redirect('/registrasi')->with($message);
                    // }

                    $message = array(
                            'flash_message_success' => 'Anda berhasil melakukan pendaftaran! <i>Silahkan melakukan sesi login Anda.</i>'
                        );
                        return redirect('/registrasi')->with($message);                    
                }catch (\Exception $e){
                    DB::rollback();
                    return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran! COBA LAGI...!');
                }
            }
        }
        return view('sign.registrasi');
    }

    public function konfirmasiEmail($id=null, $email=null, $name=null) {
        try{
            $data = array('userID'=>$id);
            Mail::send('mail', $data, function($message) use ($email, $name) {
                $message->to($email, $name)->subject('Konfirmasi email');
                $message->from('syahrulyusuf52@gmail.com','KeuanganKu');
            });
            return TRUE;
        }catch(Exception $e){
            DB::rollback();
            return FALSE;
        }
    }

    public function konfirmasi($id=null)
    {
        try {
            $user = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        DB::beginTransaction();
        try{
            User::where('id', $user)->update(['is_active'=>1]);
            DB::commit();
            return redirect('/')->with('flash_message_success', 'Konfirmasi email berhasil, silahkan login untuk memulai sesi Anda!');
        }catch(Exception $e){
            DB::rollback();
            return redirect('/')->with('flash_message_error', 'Konfirmasi email gagal! Silahkan coba lagi.');
        }
    }

    public function logout() {
        User::where('id', Auth::user()->id)->update([
            'logout' => Carbon::now('Asia/Jakarta')
        ]);
        Activity::log(Auth::user()->id, 'Logout', 'Logout', 'IP Address: '. \Request::ip() . ' Device: '. \Request::header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
        Session::flush();
        Auth::logout();
        return redirect('/login')->with('flash_message_success', 'Logged out berhasil');
    }
}
