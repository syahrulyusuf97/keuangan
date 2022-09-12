<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use Mail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Validator;
use Helper;

class SignController extends Controller
{
    function randomNumber($length) {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    // public function details()
    // {
    //     $user = Auth::user();
    //     return response()->json(['success' => $user], $this->successStatus);
    // }

    public function login(Request $request) {

    	if ($request->isMethod('post')) {
    		# code...
            $rules_of_validator = [
                'username' => 'required',
                'password' => 'required',
                'remember' => 'boolean'
            ];

            $message_of_validator = [
                'username.required' => 'Username tidak boleh kosong',
                'password.required' => 'Password tidak boleh kosong',
                'remember.boolean'  => 'Remember boolean (True/False)'
            ];

            $validator = Validator::make($request->all(), $rules_of_validator, $message_of_validator);
    
            if ($validator->fails()) {
                if (sizeof($validator->messages()->all()) <= 2) {
                    $message = implode(' & ', $validator->messages()->all());
                } elseif (sizeof($validator->messages()->all()) > 2) {
                    $message = implode(', ', $validator->messages()->all());
                }
                
                $response = [
                    'success' => false,
                    'message' => $message,
                    'error_code' => 1207,
                    'data' => []
                ];
            } else {
                $data = $request->input();

                $user_exists = User::where('username', $data['username'])->exists();

                if ($user_exists) {
                    if (Auth::attempt(['username'=>$data['username'], 'password'=>$data['password'], 'is_active'=>1, 'level'=>2])) {
                        # code...
                        $user = $request->user();
                        $tokenResult = $user->createToken('Personal Access Token');
                        $token = $tokenResult->token;
                        if ($request->remember) {
                            $token->expires_at = Carbon::now('Asia/Jakarta')->addWeeks(1);
                        } else {
                            $token->expires_at = Carbon::now('Asia/Jakarta')->addDays(1);
                        }
                        $token->save();

                        User::where('id', $user->id)->update([
                            'login_mobile' => Carbon::now('Asia/Jakarta')
                        ]);

                        Activity::log($user->id, 'Login', 'Login', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

                        $response = [
                            'success' => true,
                            'message' => 'User Found',
                            'error_code' => null,
                            'data' => [
                                'token_access'  => $tokenResult->accessToken,
                                'token_type'    => 'Bearer',
                                'expires_at'    => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString()
                            ]                    
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => Helper::errorCode(1208),
                            'error_code' => 1208,
                            'data' => []
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => Helper::errorCode(1206),
                        'error_code' => 1206,
                        'data' => []
                    ];
                }
            }
    	} else {
            $response = [
                'success' => false,
                'message' => Helper::errorCode(1106),
                'error_code' => 1106,
                'data' => []
            ];
        }

        return response()->json($response);
    }

    public function registrasi(Request $request) {
        if ($request->isMethod('post')) {
            # code...
            $validator = Validator::make($request->all(), [
                'nama'      => 'required',
                'jenis_kelamin' => 'required',
                'email'     => 'required|email',
                'username'  => 'required',
                'password'  => 'required'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

            $data           = $request->input();
            $check_mail     = User::where('email', $data['email'])->count();
            $check_username = User::where('username', $data['username'])->count();
            $active_code    = $this->randomNumber(6);
            $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));
            if ($check_mail > 0) {
                $response = [
                    'status'    => "failed",
                    'message'   => "Anda gagal melakukan pendaftaran, email sudah terdaftar!"
                ];
            } else if ($check_username > 0) {
                $response = [
                    'status'    => "failed",
                    'message'   => "Anda gagal melakukan pendaftaran, username sudah terdaftar!"
                ];
            } else {
                DB::beginTransaction();
                try{
                    $user = DB::table('users')->insertGetId([
                        'name'          => ucwords($data['nama']),
                        'sex'           => ($data['jenis_kelamin'] == 0 || is_null($data['jenis_kelamin'])) ? 'Laki-laki' : 'Perempuan',
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
                    if ($send_mail) {
                        $response = [
                            'status'    => "success",
                            'message'   => "Anda berhasil melakukan pendaftaran! Kami telah mengirimkan konfirmasi ke email Anda",
                            'data'      => [
                                'user'      => Crypt::encrypt($user),
                                'code'      => $active_code,
                                'email'     => $data['email'],
                                'expired'   => $code_expired
                            ]
                        ];
                    } else {
                        $response = [
                            'status'    => "success",
                            'message'   => "Anda berhasil melakukan pendaftaran! Gagal mengirimkan konfirmasi ke email Anda",
                            'data'      => [
                                'user'      => Crypt::encrypt($user),
                                'code'      => $active_code,
                                'email'     => $data['email'],
                                'expired'   => $code_expired
                            ]
                        ];
                    }                  
                }catch (\Exception $e){
                    DB::rollback();
                    $response = [
                        'status'    => "error",
                        'message'   => "Anda gagal melakukan pendaftaran. Coba Lagi...!"
                    ];
                }
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function resendEmail(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'user'     => 'required',
                'code'     => 'required',
                'email'    => 'required|email',
                'expired'  => 'required'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

            $data           = $request->all();

            $link = url('/konfirmasi/'.$code.'/'.$data['user']);

            try {
                Mail::to($data['email'])->send(new \App\Mail\KonfirmasiEmail($link, $data['expired']));
                $response = [
                    'status'    => "success",
                    'message'   => "Kami telah mengirimkan konfirmasi ke email Anda",
                    'data'      => [
                        'user'      => $data['user'],
                        'code'      => $data['code'],
                        'email'     => $data['email'],
                        'expired'   => $data['expired']
                    ]
                ];
            } catch (\Exception $e) {
                $response = [
                    'status'    => "error",
                    'message'   => "Gagal mengirimkan konfirmasi ke email Anda",
                    'data'      => [
                        'user'      => $data['user'],
                        'code'      => $data['code'],
                        'email'     => $data['email'],
                        'expired'   => $data['expired']
                    ]
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function newEmailCode(Request $request) {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'user'     => 'required',
                'email'    => 'required|email'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

            try {
                $userid = Crypt::decrypt($request->user);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "error",
                    'message'   => 'A Network Error occurred. Please try again!'
                ];
                return response()->json($response); 
            }

            $active_code    = $this->randomNumber(6);
            $code_expired   = date('Y-m-d H:i:s', strtotime('+1 day'));

            DB::beginTransaction();
            try{
                User::where('id', $userid)->update(['active_code'=>$active_code, 'code_expired'=>$code_expired]);
                DB::commit();
            }catch(Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => 'Kode aktivasi gagal dibuat!',
                    'data'      => [
                        'user' => $request->user,
                        'email'=> $request->email
                    ]
                ];
                return response()->json($response);
            }

            $link = url('/konfirmasi/'.$active_code.'/'.$request->user);
            try {
                Mail::to($request->email)->send(new \App\Mail\KonfirmasiEmail($link, $code_expired));
                $response = [
                    'status'    => "success",
                    'message'   => "Kami telah mengirimkan konfirmasi ke email Anda",
                    'data'      => [
                        'user'      => $request->user,
                        'code'      => $active_code,
                        'email'     => $request->email,
                        'expired'   => $code_expired
                    ]
                ];
            } catch (\Exception $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal mengirimkan konfirmasi ke email Anda",
                    'data'      => [
                        'user'      => $request->user,
                        'code'      => $active_code,
                        'email'     => $request->email,
                        'expired'   => $code_expired
                    ]
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
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

    public function requestResetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'email'    => 'required|email'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

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
                        $response = [
                            'status'    => "success",
                            'message'   => 'Link konfirmasi telah kami kirim ke email "'.$request->email.'"'
                        ];
                    } else {
                        $response = [
                            'status'    => "failed",
                            'message'   => 'A Network Error occurred. Please try again!'
                        ];
                    }
                }catch(Exception $e){
                    DB::rollback();
                    $response = [
                        'status'    => "error",
                        'message'   => 'A Network Error occurred. Please try again!'
                    ];
                }
            } else {
                $response = [
                    'status'    => "failed",
                    'message'   => 'Email belum terdaftar!'
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function resetPassword(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

            $check_token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token);
            if ($check_token->count() > 0) {
                $response = [
                    'status'    => "success",
                    'message'   => "Identifikasi berhasil",
                    'data'      => [
                        'token' => $request->token,
                        'email' => $request->email
                    ]
                ];
            } else {
                $response = [
                    'status'    => "failed",
                    'message'   => "Tidak ada permintaan perubahan kata sandi!"
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function passwordReset(Request $request)
    {
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                $response = [
                    'status'    => "failed",
                    'message'   => $validator->errors()
                ];
                return response()->json($response);            
            }

            $check_token = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token);
            
            if ($check_token->count() < 1) {
                $response = [
                    'status'    => "failed",
                    'message'   => "Tidak ada permintaan perubahan kata sandi!"
                ];
                return response()->json($response);
            }

            $email = User::where('email', $request->email);

            if ($email->count() < 1) {
                $response = [
                    'status'    => "failed",
                    'message'   => "Email tidak ditemukan!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $email->update(['password'=>bcrypt($request->password)]);
                Activity::log($email->first()->id, 'Login', 'reset password', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));
                DB::commit();
                if ($this->sendPWDEmail($email->first()->email, $email->first()->username, $request->password)) {
                    DB::table('password_resets')->where('email', $request->email)->delete();
                    $response = [
                        'status'    => "success",
                        'message'   => "Password berhasil diperbarui!"
                    ];
                } else {
                    $response = [
                        'status'    => "failed",
                        'message'   => "A Network Error occurred. Please try again!"
                    ];
                }
                return response()->json($response);
            }catch(Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "error",
                    'message'   => "A Network Error occurred. Please try again!"
                ];
                return response()->json($response);
            }
        } else {
            $response = [
                'status'    => 'error',
                'message'   => 'Method Not Allowed'
            ];
            return response()->json($response);
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

    public function logout(Request $request) {
        $user = Auth::user();

        User::where('id', $user->id)->update([
            'logout_mobile' => Carbon::now('Asia/Jakarta')
        ]);

        Activity::log($user->id, 'Logout', 'Logout', 'IP Address: '. $request->ip() . ' Device: '. $request->header('User-Agent'), null, Carbon::now('Asia/Jakarta'));

        $logout = $user->token()->revoke();

        if($logout){
            $response = [
                'success' => true,
                'message' => 'Successfully logged out',
                'error_code' => null,
                'data' => []
            ];
            return response()->json($response);
        } else {
            $response = [
                'success' => false,
                'message' => Helper::errorCode(1323),
                'error_code' => 1323,
                'data' => []
            ];
            return response()->json($response);
        }
    }
}
