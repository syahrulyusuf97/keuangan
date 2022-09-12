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
                    "saldo"                 => $saldo_bank + $saldo_kas,
                    "saldo_kas"              => $saldo_kas,
                    "debit_kas_last_month"   => (int)$debit_kas_last_month,
                    "credit_kas_last_month"  => (int)$credit_kas_last_month,
                    "debit_kas_last_year"    => (int)$debit_kas_last_year,
                    "credit_kas_last_year"   => (int)$credit_kas_last_year,
                    "saldo_bank"             => $saldo_bank,
                    "debit_bank_last_month"  => (int)$debit_bank_last_month,
                    "credit_bank_last_month" => (int)$credit_bank_last_month,
                    "debit_bank_last_year"   => (int)$debit_bank_last_year,
                    "credit_bank_last_year"  => (int)$credit_bank_last_year
                ];

                
                $response = [
                    'success'    => true,
                    'message'    => 'Data available',
                    'error_code' => null,
                    'data'       => $data_saldo
                ];
            }catch(\Exception $e){
                $response = [
                    'success'    => false,
                    'message'    => 'Gagal mendapatkan informasi saldo',
                    'error_code' => null,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    public function detailSaldo(Request $request, $param=null)
    {
        if ($request->isMethod('get')) {
            try{
                $akun = Akun::where('jenis_akun', ucwords($param))->where('iduser', Auth::user()->id);
                if ($akun->count() > 0) {
                    $saldo = array_reduce($akun->get()->toArray(), function($carry, $item){
                        $item['saldo'] = $this->saldo($item['id']);
                        $item['id'] = Crypt::encrypt($item['id']);
                        $item['iduser'] = Crypt::encrypt($item['iduser']);
                        $item['enabled'] = ($item['enabled'] == 1) ? true : false;
                        $carry[] = (object)$item;
                        return $carry;
                    });

                    $data = [
                        'saldo' => $saldo,
                        'jenis_akun' => ucwords($param)
                    ];
                } else {
                    $data = [
                        'saldo' => [],
                        'jenis_akun' => ucwords($param)
                    ];
                }

                $response = [
                    'success'    => true,
                    'message'    => 'data available',
                    'error_code' => null,
                    'data'       => $data
                ];
            }catch(\Exception $e){
                $response = [
                    'success'    => false,
                    'message'    => "Gagal mendapatkan informasi saldo",
                    'error_code' => null,
                    'data'       => []
                ];
            }
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    // Chart Mutasi Bulan Lalu
    private function chartMutasiBulanLalu($akun = 'Bank')
    {
        $row = array();
        // $month = explode("-", $this->last_month);
        $month = explode("-", '2020-05');
        $t = $month[0].'-'.$month[1].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));
        $dates  = Helper::getAllDateByMonth($month[1], $month[0]);

        $data_mutasi = Cash::select(\DB::raw('c_tanggal as date'), \DB::raw("SUM(CASE WHEN c_jenis = 'D' THEN c_jumlah ELSE 0 END) as jumlah_debit"), \DB::raw("SUM(CASE WHEN c_jenis = 'K' THEN c_jumlah ELSE 0 END) as jumlah_kredit"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', $akun)
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_jenis','c_tanggal'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach($dates as $key => $values) {
            $row[(String)$values] = ['debit'=>0, 'kredit'=>0];
        }

        foreach ($data_mutasi as $key => $mutasi) {
            $row[(String)date('d', strtotime($mutasi->date))] = ['debit' => (float)$mutasi->jumlah_debit, 'kredit' => (float)$mutasi->jumlah_kredit];
        }

        $response_data = [
            "data" => $row,
            "month" => $month[0].'-'.$month[1]
        ];

        return $response_data;
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
        $dates  = Helper::getAllDateByMonth($month[0], $month[1]);

        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($data_debit->count() > 0) {
            foreach ($data_debit->get() as $key => $debit) {
                $row[(String)date('d', strtotime($debit->date))] = (float)$debit->jumlah_debit;
            }
        }

        $response_data = [
            'bulan' => $month[1].'-'.$month[0],
            'detail'  => $row
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
        $dates  = Helper::getAllDateByMonth($month[0], $month[1]);

        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($data_debit->count() > 0) {
            foreach ($data_debit->get() as $key => $debit) {
                $row[(String)date('d', strtotime($debit->date))] = (float)$debit->jumlah_debit;
            }
        }

        $response_data = [
            'bulan' => $month[1].'-'.$month[0],
            'detail'  => $row
        ];

        return $response_data;
    }

    // Chart Bulan Lalu Kredit Kas
    private function chartBulanKreditKas()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));
        $dates  = Helper::getAllDateByMonth($month[0], $month[1]);

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($data_credit->count() > 0) {
            foreach ($data_credit->get() as $key => $credit) {
                $row[(String)date('d', strtotime($credit->date))] = (float)$credit->jumlah_kredit;
            }
        }

        $response_data = [
            'bulan' => $month[1].'-'.$month[0],
            'detail'  => $row
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
        $dates  = Helper::getAllDateByMonth($month[0], $month[1]);

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($data_credit->count() > 0) {
            foreach ($data_credit->get() as $key => $credit) {
                $row[(String)date('d', strtotime($credit->date))] = (float)$credit->jumlah_kredit;
            }
        }

        $response_data = [
            'bulan' => $month[1].'-'.$month[0],
            'detail'  => $row
        ];

        return $response_data;
    }

    // Grafik Kas Bulan Lalu
    private function grafikKasLastMonth()
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
        $dates = Helper::getAllDateByMonth(explode('-',$this->last_month)[1], explode('-',$this->last_month)[0]);

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);

            $row[(String)date('d', strtotime($debit->date))] = (float)$debit->jumlah_debit;
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

    // Grafik Kas Tahun lalu
    private function grafikKasLastYear()
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

    private function grafikBanklastYear()
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
                    // Mutasi Bank Bulan Lalu
                    "mutasi_bank_last_month" => $this->chartMutasiBulanLalu('Bank'),
                    // Mutasi Kas Bulan Lalu
                    "mutasi_kas_last_month" => $this->chartMutasiBulanLalu('Kas'),
                    // Chart Kategori Kas Debit Bulan Lalu
                    "kas_debit_last_month_category" => $this->chartKKDBL(),
                    // Chart Kategori Kas Kredit Bulan lalu
                    "kas_credit_last_month_category" => $this->chartKKKBL(),
                    // Chart Kategori Bank Debit Bulan Lalu
                    "bank_debit_last_month_category" => $this->chartKBDBL(),
                    // Chart Kategori Bank Kredit Bulan Lalu
                    "bank_credit_last_month_category" => $this->chartKBKBL(),
                    // Chart Kategori Kas Debit Tahun Lalu
                    "kas_debit_last_year_category" => $this->chartKKDTL(),
                    // Chart Kategori kas Kredit Tahun Lalu
                    "kas_credit_last_year_category" => $this->chartKKKTL(),
                    // Chart Kategori Bank Debit Tahun Lalu
                    "bank_debit_last_year_category" => $this->chartKBDTL(),
                    // Chart Kategori Bank Kredit Tahun lalu
                    "bank_credit_last_year_category" => $this->chartKBKTL(),
                    // Debit Kas Bulaln Lalu
                    "kas_debit_last_month" => $this->chartBulanDebitKas(),
                    // Debit Bank Bulan lalu
                    "bank_debit_last_month" => $this->chartBulanDebitBank(),
                    // Kas Kredit Bulan lalu
                    "kas_credit_last_month" => $this->chartBulanKreditKas(),
                    // Bnak Kredit Bulan lalu
                    "bank_credit_last_month" => $this->chartBulanKreditBank(),
                    // Kas Tahun lalu
                    "kas_last_year" => $this->grafikKasLastYear(),
                    // Bank Tahun lalu
                    "bank_last_year" => $this->grafikBankLastYear()
                ];

                $response = [
                    'success'    => true,
                    'message'    => "Data tersedia",
                    'error_code' => null,
                    'data'       => $response_data
                ];
            }catch(\Exception $e){
                $response = [
                    'success'    => false,
                    'message'    => Helper::errorCode(1401),
                    'error_code' => 1401,
                    'data'       => []
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1106),
                'error_code' => 1106,
                'data'       => []
            ];
            return response()->json($response);
        }
    }
}
