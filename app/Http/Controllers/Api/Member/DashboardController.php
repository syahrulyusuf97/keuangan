<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\ActivityController as Activity;
use App\Cash;
use App\User;
use App\Kategori;
use App\Akun;
use Auth;
use DB;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class DashboardController extends Controller
{
    public $last_month;
    public $last_year;

    public function __construct()
    {
        $this->last_month = date('Y-m', strtotime('-1 months'));
        $this->last_year = date('Y', strtotime('-1 year'));
    }

    private function saldo($id_akun)
    {
        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_akun', $id_akun)
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_akun', $id_akun)
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_kas  = $debit_kas - $credit_kas;
        return $saldo_kas;
    }

    public function getSaldo(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                //
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

                $debit_kas_last_month   = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Kas')
                                        ->where('c_jenis', 'D')
                                        ->whereYear('c_tanggal', '=', explode("-", $this->last_month)[0])
                                        ->whereMonth('c_tanggal', '=', explode("-", $this->last_month)[1])
                                        ->sum('c_jumlah');
                $credit_kas_last_month  = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Kas')
                                        ->where('c_jenis', 'K')
                                        ->whereYear('c_tanggal', '=', explode("-", $this->last_month)[0])
                                        ->whereMonth('c_tanggal', '=', explode("-", $this->last_month)[1])
                                        ->sum('c_jumlah');
                $debit_kas_last_year   = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Kas')
                                        ->where('c_jenis', 'D')
                                        ->whereYear('c_tanggal', '=', $this->last_year)
                                        ->sum('c_jumlah');
                $credit_kas_last_year  = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Kas')
                                        ->where('c_jenis', 'K')
                                        ->whereYear('c_tanggal', '=', $this->last_year)
                                        ->sum('c_jumlah');

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

                $debit_bank_last_month   = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Bank')
                                        ->where('c_jenis', 'D')
                                        ->whereYear('c_tanggal', '=', explode("-", $this->last_month)[0])
                                        ->whereMonth('c_tanggal', '=', explode("-", $this->last_month)[1])
                                        ->sum('c_jumlah');
                $credit_bank_last_month  = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Bank')
                                        ->where('c_jenis', 'K')
                                        ->whereYear('c_tanggal', '=', explode("-", $this->last_month)[0])
                                        ->whereMonth('c_tanggal', '=', explode("-", $this->last_month)[1])
                                        ->sum('c_jumlah');
                $debit_bank_last_year   = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Bank')
                                        ->where('c_jenis', 'D')
                                        ->whereYear('c_tanggal', '=', $this->last_year)
                                        ->sum('c_jumlah');
                $credit_bank_last_year  = Cash::where('c_iduser', Auth::user()->id)
                                        ->where('c_flagakun', 'Bank')
                                        ->where('c_jenis', 'K')
                                        ->whereYear('c_tanggal', '=', $this->last_year)
                                        ->sum('c_jumlah');

                $data_saldo = [
                    "saldo_kas"              => $saldo_kas,
                    "debit_kas_last_month"   => $debit_kas_last_month,
                    "credit_kas_last_month"  => $credit_kas_last_month,
                    "debit_kas_last_year"    => $debit_kas_last_year,
                    "credit_kas_last_year"   => $credit_kas_last_year,
                    "saldo_bank"             => $saldo_bank,
                    "debit_bank_last_month"  => $debit_bank_last_month,
                    "credit_bank_last_month" => $credit_bank_last_month,
                    "debit_bank_last_year"   => $debit_bank_last_year,
                    "credit_bank_last_year"  => $credit_bank_last_year
                ];

                $response = [
                    'status' => "success",
                    'data'   => $data_saldo
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal mendapatkan informasi saldo"
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

    public function detailSaldo(Request $request, $param=null)
    {
        if ($request->isMethod('get')) {
            try{
                $akun = Akun::where('jenis_akun', ucwords($param))->where('iduser', Auth::user()->id);
                if ($akun->count() > 0) {
                    $data = array_reduce($akun->get()->toArray(), function($carry, $item){
                        $item['saldo'] = $this->saldo($item['id']);
                        $item['id'] = Crypt::encrypt($item['id']);
                        $item['iduser'] = Crypt::encrypt($item['iduser']);
                        $item['enabled'] = ($item['enabled'] == 1) ? true : false;
                        $carry[] = (object)$item;
                        return $carry;
                    });
                } else {
                    $data = [];
                }

                $response = [
                    'status'     => "success",
                    'jenis_akun' => ucwords($param),
                    'data'       => $data
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal mendapatkan informasi saldo"
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

    // Chart Kategori Kas Debit Bulan Lalu
    private function chartKKDBL()
    {
        $ktg_kas_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', explode("-", $this->last_month)[0])
                        ->whereMonth('c.c_tanggal', '=', explode("-", $this->last_month)[1])
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkkdl = [];
        if (sizeof($ktg_kas_dbt_lm) > 0) {
            foreach ($ktg_kas_dbt_lm as $key => $kkdl) {
                $kategori = DB::table('ms_kategori')->where('id', $kkdl->id_kategori)->first();
                $dtkkdl[] = array(
                    'kategori'  => $kategori->nama, 
                    'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                    'jumlah'    => $kkdl->jumlah
                );
            }
        }

        $total = 0;
        $dtkkdl_dt = [];
        if (sizeof($dtkkdl) > 0) {
            foreach ($dtkkdl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkkdl as $key => $val_dt) {
                $dtkkdl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkkdl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $dtkkdl_dt
        ];
        
        return $response_data;
    }

    // Chart Kategori Kas Kredit Bulan lalu
    private function chartKKKBL()
    {
        $ktg_kas_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', explode("-", $this->last_month)[0])
                        ->whereMonth('c.c_tanggal', '=', explode("-", $this->last_month)[1])
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkkkl = [];
        if (sizeof($ktg_kas_krd_lm) > 0) {
            foreach ($ktg_kas_krd_lm as $key => $kkkl) {
                $kategori = DB::table('ms_kategori')->where('id', $kkkl->id_kategori)->first();
                $dtkkkl[] = array(
                    'kategori'  => $kategori->nama, 
                    'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                    'jumlah'    => $kkkl->jumlah
                );
            }
        }

        $total = 0;
        $dtkkkl_dt = [];
        if (sizeof($dtkkkl) > 0) {
            foreach ($dtkkkl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkkkl as $key => $val_dt) {
                $dtkkkl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkkkl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $dtkkkl_dt
        ];

        return $response_data;
    }

    // Chart Kategori Bank Debit Bulan Lalu
    private function chartKBDBL()
    {
        $ktg_bank_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', explode("-", $this->last_month)[0])
                        ->whereMonth('c.c_tanggal', '=', explode("-", $this->last_month)[1])
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkbdl = [];
        foreach ($ktg_bank_dbt_lm as $key => $kbdl) {
            $kategori = DB::table('ms_kategori')->where('id', $kbdl->id_kategori)->first();
            $dtkbdl[] = array(
                'kategori'  => $kategori->nama, 
                'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'jumlah'    => $kbdl->jumlah
            );
        }

        $total = 0;
        $dtkbdl_dt = [];
        if (sizeof($dtkbdl) > 0) {
            foreach ($dtkbdl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkbdl as $key => $val_dt) {
                $dtkbdl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkbdl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $dtkbdl_dt
        ];

        return $response_data;
    }

    // Chart Kategori Bank Kredit Bulan Lalu
    private function chartKBKBL()
    {
        $ktg_bank_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', explode("-", $this->last_month)[0])
                        ->whereMonth('c.c_tanggal', '=', explode("-", $this->last_month)[1])
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkbkl = [];
        foreach ($ktg_bank_krd_lm as $key => $kbkl) {
            $kategori = DB::table('ms_kategori')->where('id', $kbkl->id_kategori)->first();
            $dtkbkl[] = array(
                'kategori'  => $kategori->nama, 
                'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'jumlah'    => $kbkl->jumlah
            );
        }

        $total = 0;
        $dtkbkl_dt = [];
        if (sizeof($dtkbkl) > 0) {
            foreach ($dtkbkl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkbkl as $key => $val_dt) {
                $dtkbkl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkbkl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $dtkbkl_dt
        ];

        return $response_data;
    }

    // Chart Kategori Kas Debit Tahun Lalu
    private function chartKKDTL()
    {
        $ktg_kas_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $this->last_year)
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkkdl = [];
        foreach ($ktg_kas_dbt_ly as $key => $kkdl) {
            $kategori = DB::table('ms_kategori')->where('id', $kkdl->id_kategori)->first();
            $dtkkdl[] = array(
                'kategori'  => $kategori->nama, 
                'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'jumlah'    => $kkdl->jumlah
            );
        }

        $total = 0;
        $dtkkdl_dt = [];
        if (sizeof($dtkkdl) > 0) {
            foreach ($dtkkdl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkkdl as $key => $val_dt) {
                $dtkkdl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkkdl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $dtkkdl_dt
        ];

        return $response_data;
    }

    // Chart Kategori kas Kredit Tahun Lalu
    private function chartKKKTL()
    {
        $ktg_kas_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $this->last_year)
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkkkl = [];
        foreach ($ktg_kas_krd_ly as $key => $kkkl) {
            $kategori = DB::table('ms_kategori')->where('id', $kkkl->id_kategori)->first();
            $dtkkkl[] = array(
                'kategori'  => $kategori->nama, 
                'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'jumlah'    => $kkkl->jumlah
            );
        }

        $total = 0;
        $dtkkkl_dt = [];
        if (sizeof($dtkkkl) > 0) {
            foreach ($dtkkkl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkkkl as $key => $val_dt) {
                $dtkkkl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkkkl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $dtkkkl_dt
        ];

        return $response_data;
    }

    // Chart Kategori Bank Debit Tahun Lalu
    private function chartKBDTL()
    {
        $ktg_bank_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $this->last_year)
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkbdl = [];
        foreach ($ktg_bank_dbt_ly as $key => $kbdl) {
            $kategori = DB::table('ms_kategori')->where('id', $kbdl->id_kategori)->first();
            $dtkbdl[] = array(
                'kategori'  => $kategori->nama, 
                'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'jumlah'    => $kbdl->jumlah
            );
        }

        $total = 0;
        $dtkbdl_dt = [];
        if (sizeof($dtkbdl) > 0) {
            foreach ($dtkbdl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkbdl as $key => $val_dt) {
                $dtkbdl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkbdl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $dtkbdl_dt
        ];

        return $response_data;
    }

    // Chart Kategori Bank Kredit Tahun lalu
    private function chartKBKTL()
    {
        $ktg_bank_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $this->last_year)
                        ->groupBy(['c.c_kategori'])
                        ->selectRaw('c.c_kategori as id_kategori, SUM(c.c_jumlah) as jumlah')
                        ->get();
        $dtkbkl = [];
        foreach ($ktg_bank_krd_ly as $key => $kbkl) {
            $kategori = DB::table('ms_kategori')->where('id', $kbkl->id_kategori)->first();
            $dtkbkl[] = array('kategori'=>$kategori->nama, 'warna'=>implode('0xff', explode('#', $kategori->warna)), 'jumlah'=>$kbkl->jumlah);
        }

        $total = 0;
        $dtkbkl_dt = [];
        if (sizeof($dtkbkl) > 0) {
            foreach ($dtkbkl as $key => $value) {
                $total += $value['jumlah'];
            }

            foreach ($dtkbkl as $key => $val_dt) {
                $dtkbkl_dt[] = array(
                    'kategori'=>$val_dt['kategori'], 
                    'warna'=>$val_dt['warna'], 
                    'jumlah'=>$val_dt['jumlah'],
                    'total'=> $total,
                    'persen'=>round(($val_dt['jumlah']/$total)*100, 2)
                );
            }

            usort($dtkbkl_dt, function($a, $b) {
                return $a['persen'] <=> $b['persen'];
            });
        }

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $dtkbkl_dt
        ];

        return $response_data;
    }

    // Chart Bulan Debit kas
    private function chartBulanDebitKas()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('date' => $debit->month, 'debit' => $debit->jumlah_debit);
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $row
        ];

        return $response_data;
    }

    // Chart Bulan Debit Bank
    private function chartBulanDebitBank()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('date' => $debit->month, 'debit' => $debit->jumlah_debit);
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $row
        ];

        return $response_data;
    }

    // Chart Bulan Kredit Kas
    private function chartBulanKreditKas()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_credit as $key => $credit) {
            $row[] = array('date' => $credit->month, 'kredit' => $credit->jumlah_kredit);
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $row
        ];

        return $response_data;
    }

    // Chart Bulan Lalu Kredit Bank
    private function chartBulanKreditBank()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_credit as $key => $credit) {
            $row[] = array('date' => $credit->month, 'kredit' => $credit->jumlah_kredit);
        }

        $response_data = [
            "bulan" => Helper::displayDate(date('Y-m-d', strtotime('-1 months')), 'month'),
            "data" => $row
        ];

        return $response_data;
    }

    // Grafik Kas Tahun lalu
    private function grafikKas()
    {
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();
        $row = [];
        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        usort($row, function($a, $b) {
            return Helper::monthStrToNumber($a['month']) <=> Helper::monthStrToNumber($b['month']);
        });

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $row
        ];

        return $response_data;
    }

    private function grafikBank()
    {
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

       	$row = [];
        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        usort($row, function($a, $b) {
            return Helper::monthStrToNumber($a['month']) <=> Helper::monthStrToNumber($b['month']);
        });

        $response_data = [
            "tahun" => date('Y', strtotime('-1 year')),
            "data" => $row
        ];

        return $response_data;
    }

    public function getStatistik(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $response_data = [
                    // Chart Kategori Kas Debit Bulan Lalu
                    "kas_debit_bulan_lalu_kategori" => $this->chartKKDBL(),
                    // Chart Kategori Kas Kredit Bulan lalu
                    "kas_kredit_bulan_lalu_kategori" => $this->chartKKKBL(),
                    // Chart Kategori Bank Debit Bulan Lalu
                    "bank_debit_bulan_lalu_kategori" => $this->chartKBDBL(),
                    // Chart Kategori Bank Kredit Bulan Lalu
                    "bank_kredit_bulan_lalu_kategori" => $this->chartKBKBL(),
                    // Chart Kategori Kas Debit Tahun Lalu
                    "kas_debit_tahun_lalu_kategori" => $this->chartKKDTL(),
                    // Chart Kategori kas Kredit Tahun Lalu
                    "kas_kredit_tahun_lalu_kategori" => $this->chartKKKTL(),
                    // Chart Kategori Bank Debit Tahun Lalu
                    "bank_debit_tahun_lalu_kategori" => $this->chartKBDTL(),
                    // Chart Kategori Bank Kredit Tahun lalu
                    "bank_kredit_tahun_lalu_kategori" => $this->chartKBKTL(),
                    // Debit Kas Bulaln Lalu
                    "kas_debit_bulan_lalu" => $this->chartBulanDebitKas(),
                    // Debit Bank Bulan lalu
                    "bank_debit_bulan_lalu" => $this->chartBulanDebitBank(),
                    // Kas Kredit Bulan lalu
                    "kas_kredit_bulan_lalu" => $this->chartBulanKreditKas(),
                    // Bnak Kredit Bulan lalu
                    "bank_kredit_bulan_lalu" => $this->chartBulanKreditBank(),
                    // Kas Tahun lalu
                    "kas_tahun_lalu" => $this->grafikKas(),
                    // Bank Tahun lalu
                    "bank_tahun_lalu" => $this->grafikBank()
                ];

                $response = [
                    'status' => "success",
                    'data'   => $response_data
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal mendapatkan data statistik"
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
}
