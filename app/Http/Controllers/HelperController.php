<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Cache;
use Request;
use App\Cash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
\Carbon\Carbon::setLocale('id');

class HelperController extends Controller
{

    static function monthStrToNumber($param)
    {
        $month = array(
            'January'   => '01',
            'February'  => '02',
            'March'     => '03',
            'April'     => '04',
            'May'       => '05',
            'June'      => '06',
            'July'      => '07',
            'August'    => '08',
            'September' => '09',
            'October'   => '10',
            'November'  => '11',
            'December'  => '12',
        );
        return $month[explode(" ", $param)[0]];
    }

    static function dateReverse($date, $from = 'd-m-Y', $to = 'Y-m-d')
    {
        if ($from == 'd-m-Y' && $to == 'Y-m-d') {
            $date = explode("-", $date);
            return implode("-", array_reverse($date));
        } else if ($from == 'Y-m-d' && $to == 'd-m-Y') {
            $date = explode("-", $date);
            return implode("-", array_reverse($date));
        }
    }

    static function dateFromString($date, $onlyMonth = false)
    {
        if ($onlyMonth) {
            $date = explode(" ", $date);
            $new_date = '01-'.self::month($date[0]).'-'.$date[1];
            return date('d-m-Y', strtotime($new_date));
        } else {
            $date = explode(" ", $date);
            $new_date = $date[0].'-'.self::month($date[1].'-'.$date[2]);
            return date('d-m-Y', strtotime($new_date));
        }
    }

    static function displayDate($date, $display="full")
    {
        $bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");

        $split = explode('-', $date);

        if ($display == "full") {
            return $split[2]. ' ' . $bulan[(int)$split[1]]. ' ' .$split[0];
        } else if ($display == "month") {
            return $bulan[(int)$split[1]]. ' ' .$split[0];
        }
    }

    static function displayRupiah($angka, $decimal=2, $symbol=true)
    {
        if ($decimal == 2 && $symbol == true) {
            $hasil_rupiah = "Rp" . number_format($angka,2,',','.');
        } else if ($symbol == false) {
            $hasil_rupiah = number_format($angka,$decimal,',','.');
        }
        
        return $hasil_rupiah;
    }

    static function month($string)
    {
        $bulan = array("Januari" => "01", "Februari" => "02", "Maret" => "03", "April" => "04", "Mei" => "05", "Juni" => "06", "Juli" => "07", "Agustus" => "08", "September" => "09", "Oktober" => "10", "November" => "11", "Desember" => "12");

        return $bulan[$string];
    }

    static function formatPrice($data)
    {
        $explode_rp =  implode("", explode("Rp", $data));
        return implode("", explode(".", $explode_rp));
    }

    static function accountCode($table, $field, $jenis_akun, $kode, $mulai = 1, $panjang = 1, $lebar = 0)
    {
        $number = DB::table($table)
                    ->select($field)
                    ->where('jenis_akun', $jenis_akun)
                    ->where(DB::raw('substr(' . $field . ', ' . $mulai . ', ' . $panjang . ')'), '=', $kode)
                    ->orderBy($field, 'desc')
                    ->limit(1);
        $countData = $number->count();
        if ($countData == 0) {
            $nomor = 1;
        } else {
            $getData = $number->get();
            $row = array();
            foreach ($getData as $value) {
                $row = array($value->$field);
            }
            $nomor = intval(substr($row[0], strlen($kode))) + 1;
        }

        if ($lebar > 0) {
            $angka = $kode . str_pad($nomor, $lebar, "0", STR_PAD_LEFT);
        } else {
            $angka = $kode . $nomor;
        }

        return $angka;
    }

    static function saldo()
    {
        // Kas
        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_kas  = $debit_kas - $credit_kas;

        // Bank
        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        $saldo = $saldo_bank + $saldo_kas;

        return $saldo;
    }

    static function saldoBank()
    {
        // Bank
        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        return $saldo_bank;
    }

    static function saldoKas()
    {
        // Kas
        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_kas  = $debit_kas - $credit_kas;

        return $saldo_kas;
    }

    static function userOnlineStatus($userID = null)
    {
        try {
            $id = Crypt::decrypt($userID);

            if ($id != null) {
                $user = DB::table('users')->where('id', $id)->first();
            
                if (Cache::has('user-is-online-' . $user->id)) {
                    return "Online";
                } else {
                    return "Offline";
                }
            } else {
                return "Undefined User ID";
            }
        } catch (DecryptException $e) {
            return "Undefined User ID";
        }
    }

    static function countUnread()
    {
        return DB::table('message')->where(['is_read'=>0, 'is_bookmark'=>0])->count();
    }

    static function countRead()
    {
        return DB::table('message')->where(['is_read'=>1, 'is_bookmark'=>0])->count();
    }

    static function countBookmark()
    {
        return DB::table('message')->where('is_bookmark', 1)->count();
    }

    static function obfuscateEmail($email = null)
    {
        if (!is_null($email)) {
            $em   = explode("@",$email);
            $name = implode('@', array_slice($em, 0, count($em)-1));
            $len  = floor(strlen($name)/2);

            return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);
        } else {
            return;
        }
    }

    static function stringLimit($x, $length = 20)
    {
      if(strlen($x)<=$length)
      {
        return $x;
      }
      else
      {
        $y=substr($x,0,$length) . '...';
        return $y;
      }
    }

    static function randomNumber($length = 6) {
        $result = '';
        for($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }
        return $result;
    }

    static function errorCode($code = null) {
        /* 
            errors:
                # 10XX : Main App Errors
                    '1000': 'App Server Error, please contact the admin' # Global Error
                    '1001': 'Missing Headers'
                    '1002': 'Missing Parameters'
                    '1003': 'Invalid offset or limit'
                    '1004': 'Invalid Locale'
                    '1005': 'Invalid Timezone'
                    '1006': 'You exceeded the limit of requests per minute, Please try again after sometime.'
                # 11XX : Http Errors
                    '1101': 'Unauthorized'
                    '1102': 'Not authorized to access'
                    '1103': 'Unprocessable Entity'
                    '1104': 'Authentication Failed'
                    '1105': 'Not Found'
                # 12XX : Auth Erorrs
                    '1201': 'Your session is expired, please login again' # Token expired
                    '1202': 'Your sessions is invalid' # JWT verification error
                    '1203': 'Your sessions is invalid' # Error encountered while decoding JWT token
                    '1204': 'Your sessions token is invalid' # Invalid token
                    '1205': 'You are Unauthorized, Please login' # You are Unauthorized, Please login
                    '1206': 'Authentication Error, User Not found' # Authentication Error, User Not found
                # 13XX Session Errors
                    '1301': 'Invalid Credentials'
                    '1302': 'Invalid Login Type'
                    '1303': 'Invalid Social Type'
                    '1304': 'Login Error'
                    '1305': 'You Account is disabled by the admin.'
                    '1306': 'Invalid mobile number.'
                    '1307': 'Wrong confirmation code! Try again.'
                    '1308': 'Invalid email or password'
                    '1309': 'Your account already exist in the app, please try to login.'
                    '1310': 'Your request is invalid or your request time is over, please try again.'
                    '1311': 'You are not authorized to access this app'
                    '1312': 'An issue in the Active Directory Service, please contat the Administrator'
                    '1313': 'your email still not confirmed, please confirm your email'
                    '1314': 'Email link has been expired'
                    '1315': 'Your account is not activated Please verify your email to activate the account'
                    '1316': 'You cannot delete user until his requests been completed or cancelled'
                    '1317': 'This number has already registered'
                    '1318': 'Please before you login with google account first sign up'
                    '1319': 'Your old mobile number is wrong'
                    '1320': 'confirmation code is expired! Try again'
                    '1321': 'You cannot delete provider until he completed or cancelled his requests'
                    '1322': 'Your account was blocked by Admin. Please contact admin at support@laancare.com'
                data_found:             'Data found'
                no_data_found:          'No data found'
                not_found:              'Not found'
                x_not_found:            '%{name} not found!'
                update_successfully:    'Updated successfully'
                x_update_successfully:  '%{name} updated successfully'
                created_successfully:   'Created successfully'
                x_created_successfully: '%{name} created successfully'
                deleted_successfully:   'Deleted successfully'
                x_deleted_successfully: '%{name} deleted successfully'
                request_submitted:      'Order %{code} Code has been Submitted successfully'
                orders_not_found:       'No orders yet'
        */

        $errors = [
            // 10XX : Main App Errors
            '1000' => 'App Server Error, please contact the admin',
            '1001' => 'Missing Headers',
            '1002' => 'Missing Parameters',
            '1003' => 'Invalid offset or limit',
            '1004' => 'Invalid Locale',
            '1005' => 'Invalid Timezone',
            '1006' => 'You exceeded the limit of requests per minute, Please try again after sometime',
            // 11XX : Http Errors
            '1101' => 'Unauthorized',
            '1102' => 'Not authorized to access',
            '1103' => 'Unprocessable Entity',
            '1104' => 'Authentication Failed',
            '1105' => 'Not Found',
            '1106' => 'Method Not Allowed',
            '1107' => 'Unauthenticated',
            // 12XX : Auth Erorrs
            '1201' => 'Your session is expired, please login again', # Token expired
            '1202' => 'Your sessions is invalid', # JWT verification error
            '1203' => 'Your sessions is invalid', # Error encountered while decoding JWT token
            '1204' => 'Your sessions token is invalid', # Invalid token
            '1205' => 'You are Unauthorized, Please login', # You are Unauthorized, Please login
            '1206' => 'Authentication Error, User not found or status user is pending/suspend', # Authentication Error, User Not found
            '1207' => 'Validator Error', # Validator error
            '1208' => 'Authentication Error, User not verified', # Validator error
            // 13XX : Session Errors
            '1301' => 'Invalid Credentials',
            '1302' => 'Invalid Login Type',
            '1303' => 'Invalid Social Type',
            '1304' => 'Login Error',
            '1305' => 'You Account is disabled by the admin.',
            '1306' => 'Invalid mobile number.',
            '1307' => 'Wrong confirmation code! Try again.',
            '1308' => 'Invalid email or password',
            '1309' => 'Your account already exist in the app, please try to login.',
            '1310' => 'Your request is invalid or your request time is over, please try again.',
            '1311' => 'You are not authorized to access this app',
            '1312' => 'An issue in the Active Directory Service, please contat the Administrator',
            '1313' => 'your email still not confirmed, please confirm your email',
            '1314' => 'Email link has been expired',
            '1315' => 'Your account is not activated Please verify your email to activate the account',
            '1316' => 'You cannot delete user until his requests been completed or cancelled',
            '1317' => 'This number has already registered',
            '1318' => 'Please before you login with google account first sign up',
            '1319' => 'Your old mobile number is wrong',
            '1320' => 'confirmation code is expired! Try again',
            '1321' => 'You cannot delete provider until he completed or cancelled his requests',
            '1322' => 'Your account was blocked by Admin. Please contact admin',
            '1323' => 'Logout error',
            // 14XX : error get data
            '1401' => 'System error occurred',
            '1402' => 'Invalid data',
        ];

        if (!is_null($code)) {
            return $errors[$code];
        } else {
            return $errors;
        }
    }

    static function tryDecrypt($encryption)
    {
      try {
        $data = Crypt::decrypt($encryption);
      } catch (DecryptException $e) {
        return null;
      }
      return $data;
    }

    static function getAllDateByMonth($bulan, $tahun)
    {
        $list = array();
        $month = $bulan;
        $year = $tahun;

        for($d = 1; $d <= 31; $d++)
        {
            $time = mktime(12, 0, 0, $month, $d, $year);          
            if (date('m', $time)==$month)       
                $list[]= date('d', $time);
        }

        return $list;
    }
}