<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Auth;
use Session;
use DB;
use Mail;
use Helper;
use Jenssegers\Agent\Agent;
use App\Identitas;
use App\User;
use App\Syarat;
use App\Kebijakan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;

class SignController extends Controller
{

    private $agent;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function login(Request $request) {
		if (Auth::check()) {
            if (Auth::user()->level == 1) {
                return redirect('/admin/dashboard');
            } else if (Auth::user()->level == 2) {
                return redirect('/dashboard');
            }
        }

    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->input();
    		if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1], true)) {

                User::where('id', Auth::user()->id)->update([
                    'login' => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                if (Auth::user()->level == 1) {
                    return redirect('/admin/dashboard');
                } else if (Auth::user()->level == 2) {
                    return redirect('/dashboard');
                } else {
                    return redirect('/logout');
                }
    		} else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>0])) {

                $message = array(
                    'flash_message_error' => 'Akun Anda belum diaktivasi!',
                    'resendmail' => '<a href="'.url('/resend_email?user='.Crypt::encrypt(Auth::user()->id).'&code='.urlencode(Auth::user()->active_code).'&email='.urlencode(Auth::user()->email)).'&expired='.urlencode(Auth::user()->code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Link Aktivasi</a>'
                );
                Session::flush();
                Auth::logout();
                return redirect()->back()->with($message);
            } else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>2])) {
                Session::flush();
                Auth::logout();
                return redirect()->back()->with('flash_message_error', 'Akun Anda sedang ditangguhkan, silahkan menghubungi Admin melalui form pesan <a href="'.url('/#contact').'">Disini</a>');
            } else {
    			return redirect()->back()->with('flash_message_error', 'Username atau password salah');
    		}
    	} else {
            $identitas  = Identitas::first();
            if ($this->agent->isMobile()) {
                return view('sign.mobile.login');
            } else {
                return view('sign.login')->with(compact('identitas'));
            }
        }
    }

    public function mobileLogin(Request $request) {
        if ($request->isMethod('post')) {
            $data = $request->input();
            if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1], true)) {

                User::where('id', Auth::user()->id)->update([
                    'login' => Carbon::now('Asia/Jakarta')
                ]);

                Activity::log(Auth::user()->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                if (Auth::user()->level == 1) {
                    $message = array(
                        'status' => "success",
                        'message'=> "/admin/dashboard"
                    );
                    return response()->json($message);
                } else if (Auth::user()->level == 2) {
                    $message = array(
                        'status' => "success",
                        'message'=> "/dashboard"
                    );
                    return response()->json($message);
                } else {
                    $message = array(
                        'status' => "failed",
                        'message'=> "Pengguna tidak diketahui"
                    );
                    return response()->json($message);
                }
            } else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>0])) {

                $message = array(
                    'status' => "failed",
                    'message' => 'Akun Anda belum diaktivasi! '.'<a href="'.url('/resend_email?user='.Crypt::encrypt(Auth::user()->id).'&code='.urlencode(Auth::user()->active_code).'&email='.urlencode(Auth::user()->email)).'&expired='.urlencode(Auth::user()->code_expired).'" id="btn_resendmail" class="btn btn-info loading-submit">Kirim Link Aktivasi</a>'
                );
                Session::flush();
                Auth::logout();
                return response()->json($message);
            } else if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>2])) {
                $message = array(
                    'status' => "failed",
                    'message' => 'Akun Anda sedang ditangguhkan, silahkan menghubungi Admin melalui form pesan <a href="'.url('/#contact').'" target="_blank">Disini</a>'
                );
                Session::flush();
                Auth::logout();
                return response()->json($message);
            } else {
                $message = array(
                    'status' => "failed",
                    'message' => 'Username atau password salah'
                );
                return response()->json($message);
            }
        } else {
            $message = array(
                'status' => "failed",
                'message' => 'Permintaan tidak diketahui'
            );
            return response()->json($message);
        }
    }

    public function registrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $data           = $request->input();
            $check_mail     = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            $active_code    = Helper::randomNumber(6);
            $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));
            if ($check_mail > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, email '.Helper::obfuscateEmail($data['email']).' sudah terdaftar!');
            } else if ($check_username > 0) {
                return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran, username <strong>"'.$data['username'].'"</strong> sudah terdaftar!');
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['name']),
                        'sex'           => $data['sex'],
                        'email'         => $data['email'],
                        'username'      => $data['username'],
                        'password'      => bcrypt($data['password']),
                        'is_active'     => 0,//0:nonactive|1:active|2:suspend
                        'level'         => 2,//member
                        'active_code'   => $active_code,
                        'code_expired'  => $code_expired,
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s'),
                        'updated_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();
                    $send_mail = $this->konfirmasiEmail(Crypt::encrypt($user), $active_code, $data['email'], $code_expired);
                    if ($send_mail == TRUE) {
                        $message = array(
                            'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($data['email']).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini',
                            'resendmail' => '<a href="'.url('/resend_email?user='.Crypt::encrypt($user).'&code='.urlencode($active_code).'&email='.urlencode($data['email'])).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                        );
                    } else {
                        $message = array(
                            'flash_message_success' => 'Anda berhasil melakukan pendaftaran! Tautan konfirmasi gagal dikirim ke alamat email Anda '.Helper::obfuscateEmail($data['email']).'. Silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini',
                            'resendmail' => '<a href="'.url('/resend_email?user='.Crypt::encrypt($user).'&code='.urlencode($active_code).'&email='.urlencode($data['email'])).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                        );
                    }
                    return redirect('/registrasi')->with($message);                    
                }catch (\Exception $e){
                    DB::rollback();
                    return redirect('/registrasi')->with('flash_message_error', 'Anda gagal melakukan pendaftaran! COBA LAGI...!');
                }
            }
        } else {
            if ($this->agent->isMobile()) {
                $syarat     = Syarat::first();
                $kebijakan  = Kebijakan::first();
                return view('sign.mobile.registrasi')->with(compact('syarat', 'kebijakan'));
            } else {
                $identitas  = Identitas::first();
                return view('sign.registrasi')->with(compact('identitas'));
            }
        }
    }

    public function mobileRegistrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $data           = $request->input();
            $check_mail     = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            $active_code    = Helper::randomNumber(6);
            $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));
            if ($check_mail > 0) {
                $message = array(
                    'status' => "failed",
                    'message'=> 'Anda gagal melakukan pendaftaran, email '.Helper::obfuscateEmail($data['email']).' sudah terdaftar!'
                );
                return response()->json($message);
            } else if ($check_username > 0) {
                $message = array(
                    'status' => "failed",
                    'message'=> 'Anda gagal melakukan pendaftaran, username <strong>"'.$data['username'].'"</strong> sudah terdaftar!'
                );
                return response()->json($message);
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['name']),
                        'sex'           => $data['jenis_kelamin'],
                        'email'         => $data['email'],
                        'username'      => $data['username'],
                        'password'      => bcrypt($data['password']),
                        'is_active'     => 0,//0:nonactive|1:active|2:suspend
                        'level'         => 2,//member
                        'active_code'   => $active_code,
                        'code_expired'  => $code_expired,
                        'created_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s'),
                        'updated_at'    => Carbon::now('Asia/Jakarta')->format('Y-m-d H:m:s')
                    ]);
                    DB::commit();
                    $send_mail = $this->konfirmasiEmail(Crypt::encrypt($user), $active_code, $data['email'], $code_expired);
                    if ($send_mail == TRUE) {
                        $message = array(
                            'status' => 'success',
                            'message' => 'Anda berhasil melakukan pendaftaran! Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($data['email']).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini '.'<a href="'.url('/resend_email?user='.Crypt::encrypt($user).'&code='.urlencode($active_code).'&email='.urlencode($data['email'])).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info loading-submit">Kirim Ulang</a>'
                        );
                    } else {
                        $message = array(
                            'status' => 'success',
                            'message' => 'Anda berhasil melakukan pendaftaran! Tautan konfirmasi gagal dikirim ke alamat email Anda '.Helper::obfuscateEmail($data['email']).'. Silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini '.'<a href="'.url('/resend_email?user='.Crypt::encrypt($user).'&code='.urlencode($active_code).'&email='.urlencode($data['email'])).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info loading-submit">Kirim Ulang</a>'
                        );
                    }
                    return response()->json($message);                 
                }catch (\Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> 'Anda gagal melakukan pendaftaran! COBA LAGI...!'
                    );
                    return response()->json($message);
                }
            }
        } else {
            $message = array(
                'status' => "failed",
                'message'=> 'Anda gagal melakukan pendaftaran! COBA LAGI...!'
            );
            return response()->json($message);
        }
    }

    public function resendEmail(Request $request) {
        $id = $request->get('user') == "" ? NULL : $request->get('user');
        $code = urldecode($request->get('code')) == "" ? NULL : urldecode($request->get('code'));
        $email = urldecode($request->get('email')) == "" ? NULL : urldecode($request->get('email'));
        $code_expired = urldecode($request->get('expired')) == "" ? NULL : urldecode($request->get('expired'));
        
        if($id === NULL || $code ===NULL || $email === NULL || $code_expired === NULL) {
            $message = array(
                'flash_message_error' => 'Invalid Data!'
            );
            return redirect()->back()->with($message);
        } else {
            $link = url('/konfirmasi/'.$code.'/'.$id);
            try {
                Mail::to($email)->send(new \App\Mail\KonfirmasiEmail($link, $code_expired));
                $message = array(
                    'flash_message_success' => 'Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ',
                    'resendmail' => '<a href="'.url('/resend_email?user='.$id.'&code='.urlencode($code).'&email='.urlencode($email)).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                );
                return redirect('/registrasi')->with($message);
            } catch (\Exception $e) {
                $message = array(
                    'flash_message_error' => 'Tautan konfirmasi gagal dikirim ke alamat email Anda '.Helper::obfuscateEmail($email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ',
                    'resendmail' => '<a href="'.url('/resend_email?user='.$id.'&code='.urlencode($code).'&email='.urlencode($email)).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
                );
                return redirect('/registrasi')->with($message);
            }
        }
    }

    public function newEmailCode(Request $request) {
        $id = $request->get('user') == "" ? NULL : $request->get('user');
        $email = urldecode($request->get('email')) == "" ? NULL : urldecode($request->get('email'));

        if ($id === NULL || $email === NULL) {
            $message = array(
                'flash_message_error' => 'Invalid Data!'
            );
            return redirect()->back()->with($message);
        }

        try {
            $userid = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return abort(404);
        }

        $active_code    = Helper::randomNumber(6);
        $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));

        DB::beginTransaction();
        try{
            User::where('id', $userid)->update(['active_code'=>$active_code, 'code_expired'=>$code_expired]);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            $message = array(
                'flash_message_error' => 'Kode aktivasi gagal dibuat!',
                'resendmail' => '<a href="'.url('/new_email_code?user='.urlencode(Crypt::encrypt($id)).'&email='.urlencode($email)).'" class="btn btn-primary">Buat kode aktivasi baru</a>'
            );
            return redirect('/login')->with($message);
        }

        $link = url('/konfirmasi/'.$active_code.'/'.$id);
        try {
            Mail::to($email)->send(new \App\Mail\KonfirmasiEmail($link, $code_expired));
            $message = array(
                'flash_message_success' => 'Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ',
                'resendmail' => '<a href="'.url('/resend_email?user='.$id.'&code='.urlencode($active_code).'&email='.urlencode($email)).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
            );
            return redirect('/login')->with($message);
        } catch (\Exception $e) {
            $message = array(
                'flash_message_error' => 'Tautan konfirmasi gagal dikirim ke alamat email Anda '.Helper::obfuscateEmail($email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ',
                'resendmail' => '<a href="'.url('/resend_email?user='.$id.'&code='.urlencode($active_code).'&email='.urlencode($email)).'&expired='.urlencode($code_expired).'" id="btn_resendmail" class="btn btn-info">Kirim Ulang</a>'
            );
            return redirect('/login')->with($message);
        }
    }

    private function konfirmasiEmail($id=null, $code=null, $email=null, $code_expired=null) {
        $link = url('/konfirmasi/'.$code.'/'.$id);
        try {
            Mail::to($email)->send(new \App\Mail\KonfirmasiEmail($link, $code_expired));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function konfirmasi($code=null, $id=null)
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
            } else if ($user->first()->is_active == 2) {
                return redirect('/login')->with('flash_message_error', 'Akun Anda sedang ditangguhkan, silahkan menghubungi Admin melalui form pesan <a href="'.url('/#contact').'">Disini</a>');
            } else {
                if (ctype_digit($code) == true) {
                    $check_code_activasi = $user->where('active_code', $code);
                    if ($check_code_activasi->count() > 0) {
                        $now = date("Y-m-d H:i:s");
                        if ($now > $check_code_activasi->first()->code_expired) {
                            $message = array(
                                'flash_message_error' => 'Kode aktivasi Anda sudah kedaluwarsa!',
                                'resendmail' => '<a href="'.url('/new_email_code?user='.urlencode(Crypt::encrypt($check_code_activasi->first()->id)).'&email='.urlencode($check_code_activasi->first()->email)).'" class="btn btn-primary">Buat kode aktivasi baru</a>'
                            );
                            return redirect('/login')->with($message);
                        } else {
                            DB::beginTransaction();
                            try{
                                $user->update(['is_active'=>1]);
                                DB::commit();
                                return redirect('/login')->with('flash_message_success', 'Konfirmasi email berhasil, silahkan login untuk memulai sesi Anda!');
                            }catch(Exception $e){
                                DB::rollback();
                                return redirect('/login')->with('flash_message_error', 'Konfirmasi email gagal! Silahkan coba lagi.');
                            }
                        }
                    } else {
                        return redirect('/login')->with('flash_message_error', 'User & kode aktivasi tidak ditemukan!');
                    }
                } else {
                    return redirect('/login')->with('flash_message_error', 'Kode aktivasi Anda tidak valid');
                }
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
                    if ($this->sendResetEmail($request->email, $email->first()->username, $tokenData->token)) {
                        return redirect()->back()->with('flash_message_success', 'Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($request->email).'. Silahkan cek kotak masuk email Anda atau folder spam. Apabila Anda tidak menerima email, silahkan kirim ulang email menggunakan tombol yang tersedia dibawah ini ');
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
                if ($this->agent->isMobile()) {
                    return view('sign.mobile.resetPassword')->with(compact('token', 'email'));
                } else {
                    return view('sign.resetPassword')->with(compact('token', 'email'));
                }
            } else {
                return redirect('/login')->with('flash_message_error', 'Tidak ada permintaan perubahan kata sandi!');
            }
        }
    }

    public function mobileResetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = User::where('email', $request->email_rpwd);
            if ($email->count() > 0) {
                DB::beginTransaction();
                try{
                    DB::table('password_resets')->insert([
                        'email' => $request->email_rpwd,
                        'token' => str_random(60),
                        'created_at' => Carbon::now('Asia/Jakarta')
                    ]);

                    $tokenData = DB::table('password_resets')
                        ->where('email', $request->email_rpwd)->first();
                    Activity::log($email->first()->id, 'Login', 'permintaan reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                    DB::commit();
                    if ($this->sendResetEmail($request->email_rpwd, $email->first()->username, $tokenData->token)) {
                        $message = array(
                            'status' => "success",
                            'message'=> 'Tautan konfirmasi telah dikirim ke alamat email Anda '.Helper::obfuscateEmail($request->email).'. Silahkan cek kotak masuk email Anda atau folder spam.'
                        );
                        return response()->json($message);
                    } else {
                        $message = array(
                            'status' => "failed",
                            'message'=> 'A Network Error occurred. Please try again.'
                        );
                        return response()->json($message);
                    }
                }catch(Exception $e){
                    DB::rollback();
                    $message = array(
                        'status' => "failed",
                        'message'=> 'A Network Error occurred. Please try again.'
                    );
                    return response()->json($message);
                }
            } else {
                $message = array(
                    'status' => "failed",
                    'message'=> 'Email belum terdaftar!'
                );
                return response()->json($message);
            }
        } else {
            $message = array(
                'status' => "failed",
                'message'=> 'A Network Error occurred. Please try again.'
            );
            return response()->json($message);
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
            return redirect('/login')->with('flash_message_error', 'Silahkan login untuk memulai sesi Anda');
        }
    }

    public function mobilePasswordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $email = User::where('email', $request->email);

            if ($email->count() < 1) {
                $message = array(
                    'status' => "failed",
                    'message'=> 'Email tidak ditemukan!'
                );
                return response()->json($message);
            }

            $check_token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token);
            
            if ($check_token->count() < 1) {
                $message = array(
                    'status' => "failed",
                    'message'=> 'Tidak ada permintaan perubahan kata sandi!'
                );
                return response()->json($message);
            }

            DB::beginTransaction();
            try{
                $email->update(['password'=>bcrypt($request->password)]);
                Activity::log($email->first()->id, 'Login', 'reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                DB::commit();
                if ($this->sendPWDEmail($email->first()->email, $email->first()->username, $request->password)) {
                    DB::table('password_resets')->where('email', $request->email)->delete();
                    $message = array(
                        'status' => "success",
                        'message'=> 'Password berhasil diperbarui!'
                    );
                    return response()->json($message);
                } else {
                    $message = array(
                        'status' => "failed",
                        'message'=> 'A Network Error occurred. Please try again.'
                    );
                    return response()->json($message);
                }
            }catch(Exception $e){
                DB::rollback();
                $message = array(
                    'status' => "failed",
                    'message'=> 'A Network Error occurred. Please try again.'
                );
                return response()->json($message);
            }
        } else {
            $message = array(
                'status' => "failed",
                'message'=> 'Silahkan login untuk memulai sesi Anda'
            );
            return response()->json($message);
        }
    }

    private function sendResetEmail($email, $username, $token)
    {
        $user = DB::table('users')->where('email', $email)->select('name', 'email')->first();
        $link = url('/reset_password') . '?token=' . $token . '&email=' . urlencode($user->email);
        try {
            Mail::to($user->email)->send(new \App\Mail\LinkResetPWD($link, $email, $username));
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
        if (Auth::check()) {
            User::where('id', Auth::user()->id)->update([
                'logout' => Carbon::now('Asia/Jakarta')
            ]);
            Activity::log(Auth::user()->id, 'Logout', 'Logout', 'IP Address: '. \Request::ip() . ' Device: '. \Request::header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
            Session::flush();
            Auth::logout();
            return redirect('/login')->with('flash_message_success', 'Logged out berhasil');
        } else {
            Session::flush();
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
    }
}
