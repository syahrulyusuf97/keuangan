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
}