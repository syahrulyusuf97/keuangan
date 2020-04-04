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
                $data = User::where('username', $data['username'])->first();
                $message = array(
                    'flash_message_error' => 'Akun Anda belum diaktivasi!',
                    'resendmail' => '<a href="'.url('/resend_email/'.Crypt::encrypt($data->id).'/'.$data->email).'" id="btn_resendmail" class="btn btn-info">Kirim Link Aktivasi</a>'
                );
                return redirect()->back()->with($message);
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
                        'is_active'     => 0,//0:nonactive|1:active
                        'level'         => 2,//member
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s'),
                        'updated_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();
                    $send_mail = $this->konfirmasiEmail(Crypt::encrypt($user), $data['email']);
                    if ($send_mail == TRUE) {
                        $message = array(
                            'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Kami telah mengirimkan konfirmasi ke email Anda',
                            'resendmail' => '<a href="'.url('/resend_email/'.Crypt::encrypt($user).'/'.$data['email']).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                        );
                    } else {
                        $message = array(
                            'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Gagal mengirimkan konfirmasi ke email Anda',
                            'resendmail' => '<a href="'.url('/resend_email/'.Crypt::encrypt($user).'/'.$data['email']).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                        );
                    }
                    return redirect('/registrasi')->with($message);                    
                }catch (\Exception $e){
                    DB::rollback();
                    return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran! COBA LAGI...!');
                }
            }
        }
        return view('sign.registrasi');
    }

    public function resendEmail($id=null, $email=null) {
        $link = url('/konfirmasi/'.$id);
        try {
            Mail::to($email)->send(new \App\Mail\KonfirmasiEmail($link));
            $message = array(
                'flash_message_success' => 'Kami telah mengirimkan konfirmasi ke email Anda',
                'resendmail' => '<a href="'.url('/resend_email/'.$id.'/'.$email).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
            );
            return redirect('/registrasi')->with($message);
        } catch (\Exception $e) {
            $message = array(
                'flash_message_error' => 'Gagal mengirimkan konfirmasi ke email Anda',
                'resendmail' => '<a href="'.url('/resend_email/'.$id.'/'.$email).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
            );
            return redirect('/registrasi')->with($message);
        }
    }

    private function konfirmasiEmail($id=null, $email=null) {
        $link = url('/konfirmasi/'.$id);
        try {
            Mail::to($email)->send(new \App\Mail\KonfirmasiEmail($link));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function konfirmasi($id=null)
    {
        try {
            $user = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $user = User::where('id', $user);

        if ($user->count() > 0) {
            if ($user->first()->is_active == 1) {
                return redirect('/login')->with('flash_message_success', 'Akun Anda sudah aktif, silahkan login untuk memulai sesi Anda!');
            }
            DB::beginTransaction();
            try{
                $user->update(['is_active'=>1]);
                DB::commit();
                return redirect('/login')->with('flash_message_success', 'Konfirmasi email berhasil, silahkan login untuk memulai sesi Anda!');
            }catch(Exception $e){
                DB::rollback();
                return redirect('/login')->with('flash_message_error', 'Konfirmasi email gagal! Silahkan coba lagi.');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'User tidak ditemukan!');
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = User::where('email', $request->email);
            if ($email->count() > 0) {
                DB::beginTransaction();
                try{
                    DB::table('password_resets')->insert([
                        'email' => $request->email,
                        'token' => str_random(60),
                        'created_at' => Carbon::now('Asia/Jakarta')
                    ]);

                    $tokenData = DB::table('password_resets')
                        ->where('email', $request->email)->first();
                    Activity::log($email->first()->id, 'Login', 'permintaan reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                    DB::commit();
                    if ($this->sendResetEmail($request->email, $tokenData->token)) {
                        return redirect()->back()->with('flash_message_success', 'Link konfirmasi telah kami kirim ke email "'.$request->email.'"');
                    } else {
                        return redirect()->back()->with('flash_message_error', 'A Network Error occurred. Please try again.');
                    }
                }catch(Exception $e){
                    DB::rollback();
                    return redirect()->back()->with('flash_message_error', 'A Network Error occurred. Please try again.');
                }
            } else {
                return redirect('/login')->with('flash_message_error', 'Email belum terdaftar!');
            }
        } else {
            $token = $request->get('token') ;
            $email = $request->get('email');

            if ($token == "" || $email == "") {
                return abort(404);
            }

            $check_token = DB::table('password_resets')->where('email', $request->get('email'))->where('token', $request->get('token'));
            if ($check_token->count() > 0) {
                return view('sign.resetPassword')->with(compact('token', 'email'));
            } else {
                return redirect('/login')->with('flash_message_error', 'Tidak ada permintaan perubahan kata sandi!');
            }
        }
    }

    public function passwordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $check_token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token);
            
            if ($check_token->count() < 1) {
                return redirect('/login')->with('flash_message_error', 'Tidak ada permintaan perubahan kata sandi!');
            }

            $email = User::where('email', $request->email);

            if ($email->count() < 1) {
                return redirect('/login')->with('flash_message_error', 'Email tidak ditemukan!');
            }

            DB::beginTransaction();
            try{
                $email->update(['password'=>bcrypt($request->password)]);
                Activity::log($email->first()->id, 'Login', 'reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                DB::commit();
                if ($this->sendPWDEmail($email->first()->email, $email->first()->username, $request->password)) {
                    DB::table('password_resets')->where('email', $request->email)->delete();
                    return redirect('/login')->with('flash_message_success', 'Password berhasil diperbarui!');
                } else {
                    return redirect('/login')->with('flash_message_error', 'A Network Error occurred. Please try again.');
                }
            }catch(Exception $e){
                DB::rollback();
                return redirect('/login')->with('flash_message_error', 'A Network Error occurred. Please try again.');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Silahkan ligin untuk memulai sesi Anda');
        }
    }

    private function sendResetEmail($email, $token)
    {
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        $link = url('/reset_password') . '?token=' . $token . '&email=' . urlencode($user->email);
        try {
            Mail::to($user->email)->send(new \App\Mail\LinkResetPWD($link));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function sendPWDEmail($email, $username, $pwd)
    {
        try {
            Mail::to($email)->send(new \App\Mail\InfoPWD($email, $username, $pwd));
            return true;
        } catch (\Exception $e) {
            return false;
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
