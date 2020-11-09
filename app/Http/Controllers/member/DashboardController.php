<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
use Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use App\Http\Controllers\ActivityController as Activity;
use App\Cash;
use App\User;
use App\Kategori;
use App\Akun;
use File;
use Auth;
use DB;
use Helper;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class DashboardController extends Controller
{
    private $last_month;
    private $last_year;
    private $agent;

    public function __construct()
    {
        $this->last_month = date('Y-m', strtotime('-1 months'));
        $this->last_year = date('Y', strtotime('-1 year'));
        $this->agent = new Agent();
    }

    private function saldo($id_akun)
    {
        // $debit_kas = Cache::remember("saldo_debit_kas", 10 * 60, function () {
        //     return Cash::where('c_iduser', Auth::user()->id)
        //             ->where('c_akun', $id_akun)
        //             ->where('c_jenis', 'D')
        //             ->sum('c_jumlah');
        // });
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

    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
        
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

        if ($this->agent->isMobile()) {
            return view('member.dashboard.mobile.dashboard')->with(compact('saldo_kas', 'debit_kas_last_month', 'credit_kas_last_month', 'debit_kas_last_year', 'credit_kas_last_year', 'saldo_bank', 'debit_bank_last_month', 'credit_bank_last_month', 'debit_bank_last_year', 'credit_bank_last_year'));
        } else {
            return view('member.dashboard.dashboard')->with(compact('saldo_kas', 'debit_kas_last_month', 'credit_kas_last_month', 'debit_kas_last_year', 'credit_kas_last_year', 'saldo_bank', 'debit_bank_last_month', 'credit_bank_last_month', 'debit_bank_last_year', 'credit_bank_last_year'));
        }
    }

    public function detailSaldo($param)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        $akun = Akun::where('jenis_akun', ucwords($param))->where('iduser', Auth::user()->id);
        if ($akun->count() > 0) {
            $data = array_reduce($akun->get()->toArray(), function($carry, $item){
                $item['saldo'] = $this->saldo($item['id']);
                $carry[] = (object)$item;
                return $carry;
            });
        } else {
            $data = [];
        }
        return view('member.dashboard.detail_saldo')->with(compact('data', 'param'));
    }

    private function chartMutasiBank()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_mutasi = Cash::select(\DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), \DB::raw("SUM(CASE WHEN c_jenis = 'D' THEN c_jumlah ELSE 0 END) as jumlah_debit"), \DB::raw("SUM(CASE WHEN c_jenis = 'K' THEN c_jumlah ELSE 0 END) as jumlah_kredit"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_jenis','c_tanggal'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_mutasi as $key => $mutasi) {
            $row[] = array('date' => $mutasi->month, 'debit' => $mutasi->jumlah_debit, 'kredit' => $mutasi->jumlah_kredit);
        }

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

    private function chartMutasiKas()
    {
        $row = array();
        $month = explode("-", date('m-Y', strtotime('-1 months')));
        $t = $month[1].'-'.$month[0].'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_mutasi = Cash::select(\DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), \DB::raw("SUM(CASE WHEN c_jenis = 'D' THEN c_jumlah ELSE 0 END) as jumlah_debit"), \DB::raw("SUM(CASE WHEN c_jenis = 'K' THEN c_jumlah ELSE 0 END) as jumlah_kredit"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_jenis','c_tanggal'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_mutasi as $key => $mutasi) {
            $row[] = array('date' => $mutasi->month, 'debit' => $mutasi->jumlah_debit, 'kredit' => $mutasi->jumlah_kredit);
        }

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

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
                    'kategori'=>$kategori->nama, 
                    'warna'=>$kategori->warna, 
                    'jumlah'=>$kkdl->jumlah
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
        
        // return Response::json($dtkkdl_dt);\
        $response_data = [
            "data" => $dtkkdl_dt
        ];
        return $response_data;
    }

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
                    'kategori'=>$kategori->nama, 
                    'warna'=>$kategori->warna, 
                    'jumlah'=>$kkkl->jumlah
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

        // return Response::json($dtkkkl_dt);
        $response_data = [
            "data" => $dtkkkl_dt
        ];
        return $response_data;
    }

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
                'kategori'=>$kategori->nama, 
                'warna'=>$kategori->warna, 
                'jumlah'=>$kbdl->jumlah
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

        // return Response::json($dtkbdl_dt);
        $response_data = [
            "data" => $dtkbdl_dt
        ];
        return $response_data;
    }

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
                'kategori'=>$kategori->nama, 
                'warna'=>$kategori->warna, 
                'jumlah'=>$kbkl->jumlah
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

        // return Response::json($dtkbkl_dt);
        $response_data = [
            "data" => $dtkbkl_dt
        ];
        return $response_data;
    }

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
                'kategori'=>$kategori->nama, 
                'warna'=>$kategori->warna, 
                'jumlah'=>$kkdl->jumlah
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

        // return Response::json($dtkkdl_dt);
        $response_data = [
            "data" => $dtkkdl_dt
        ];
        return $response_data;
    }

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
                'kategori'=>$kategori->nama, 
                'warna'=>$kategori->warna, 
                'jumlah'=>$kkkl->jumlah
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

        // return Response::json($dtkkkl_dt);
        $response_data = [
            "data" => $dtkkkl_dt
        ];
        return $response_data;
    }

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
                'kategori'=>$kategori->nama, 
                'warna'=>$kategori->warna, 
                'jumlah'=>$kbdl->jumlah
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

        // return Response::json($dtkbdl_dt);
        $response_data = [
            "data" => $dtkbdl_dt
        ];
        return $response_data;
    }

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
            $dtkbkl[] = array('kategori'=>$kategori->nama, 'warna'=>$kategori->warna, 'jumlah'=>$kbkl->jumlah);
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

        // return Response::json($dtkbkl_dt);
        $response_data = [
            "data" => $dtkbkl_dt
        ];
        return $response_data;
    }

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

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

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

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

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

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

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

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

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

        // return Response::json($row);
        $response_data = [
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

        // return Response::json($row);
        $response_data = [
            "data" => $row
        ];
        return $response_data;
    }

    public function getStatistik()
    {
        if (Request::ajax()) {
            try{
                $response_data = [
                    // Chart Mutasi Bank Bulan Lalu
                    "mutasi_bank_bulan_lalu" => $this->chartMutasiBank(),
                    // Chart Mutasi Kas Bulan Lalu
                    "mutasi_kas_bulan_lalu" => $this->chartMutasiKas(),
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
                    // "kas_debit_bulan_lalu" => $this->chartBulanDebitKas(),
                    // Debit Bank Bulan lalu
                    // "bank_debit_bulan_lalu" => $this->chartBulanDebitBank(),
                    // Kas Kredit Bulan lalu
                    // "kas_kredit_bulan_lalu" => $this->chartBulanKreditKas(),
                    // Bnak Kredit Bulan lalu
                    // "bank_kredit_bulan_lalu" => $this->chartBulanKreditBank(),
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
                'message'   => 'Not Allowed'
            ];
            return response()->json($response);
        }
    }

    public function profil()
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        $tgllahir = User::select('tgl_lahir')
            ->where('id', Auth::user()->id)->first();
        $date = [];
        if ($tgllahir->tgl_lahir != null) {
            $date = explode('-', $tgllahir->tgl_lahir);
            $day = $date[2];
            $month = $date[1];
            $year = $date[0];
        } else {
            $day = null;
            $month = null;
            $year = null;
        }

        if ($this->agent->isMobile()) {
            return view('member.profile.mobile.index')->with(compact('day', 'month', 'year'));
        } else {
            return view('member.profile.index')->with(compact('day', 'month', 'year'));
        }
    }

    public function updateNama(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();
            Activity::log(Auth::user()->id, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi ' . $request->nama, 'Nama sebelumnya ' . $user->name, Carbon::now('Asia/Jakarta'));
            User::where('id', Auth::user()->id)->update([
                'name' => $request->nama
            ]);
            DB::commit();
            return redirect()->back()->with('flash_message_success', 'Nama Anda berhasil diubah!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Nama Anda gagal diubah!');
        }
    }

    public function mobileUpdateNama(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        DB::beginTransaction();
        try{
            $user = User::where('id', Auth::user()->id)->first();
            Activity::log(Auth::user()->id, 'Update', 'merubah nama pengguna', 'Diperbarui menjadi ' . $request->nama, 'Nama sebelumnya ' . $user->name, Carbon::now('Asia/Jakarta'));
            User::where('id', Auth::user()->id)->update([
                'name' => $request->nama
            ]);
            DB::commit();
            $message = array(
                'status'    => "success",
                'message'   => "Nama Anda berhasil diperbarui",
                'data'      => array("nama"=>$request->nama)
            );
            return response()->json($message);
        }catch (\Exception $e){
            DB::rollback();
            $message = array(
                'status' => "failed",
                'message'=> "Nama Anda gagal diperbarui"
            );
            return response()->json($message);
        }
    }

    public function updateEmail(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $check = User::where('email', $request->email)->count();
            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui email, email sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah email', 'Diperbarui menjadi ' . $request->email, 'Email sebelumnya ' . $user->email, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'email' => $request->email
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Email Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Email Anda gagal diubah!');
        }
    }

    public function mobileUpdateEmail(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        DB::beginTransaction();
        try{
            $check = User::where('email', $request->email)->count();
            if ($check > 0) {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal memperbarui email, email sudah digunakan!"
                );
                return response()->json($message);
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah email', 'Diperbarui menjadi ' . $request->email, 'Email sebelumnya ' . $user->email, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'email' => $request->email
                ]);
                DB::commit();
                $message = array(
                    'status'    => "success",
                    'message'   => "Email Anda berhasil diperbarui",
                    'data'      => array("email"=>$request->email)
                );
                return response()->json($message);
            }
        }catch (\Exception $e){
            DB::rollback();
            $message = array(
                'status' => "failed",
                'message'=> "Email Anda gagal diperbarui"
            );
            return response()->json($message);
        }
    }

    public function updateUsername(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            $check = User::where('username', $request->username)->count();
            if ($check > 0) {
                return redirect()->back()->with('flash_message_error', 'Gagal memperbarui username, username sudah digunakan!');
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah username', 'Diperbarui menjadi ' . $request->username, 'Username sebelumnya ' . $user->username, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'username' => $request->username
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Username Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Username Anda gagal diubah!');
        }
    }

    public function mobileUpdateUsername(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        DB::beginTransaction();
        try{
            $check = User::where('username', $request->username)->count();
            if ($check > 0) {
                $message = array(
                    'status' => "failed",
                    'message'=> "Gagal memperbarui username, username sudah digunakan!"
                );
                return response()->json($message);
            } else {
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah username', 'Diperbarui menjadi ' . $request->username, 'Username sebelumnya ' . $user->username, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'username' => $request->username
                ]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Username Anda berhasil diperbarui",
                    'data'      => array("username"=>$request->username)
                );
                return response()->json($message);
            }
        }catch (\Exception $e){
            DB::rollback();
            $message = array(
                'status' => "failed",
                'message'=> "Username Anda gagal diperbarui"
            );
            return response()->json($message);
        }
    }

    public function updatePassword(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        DB::beginTransaction();
        try{
            if ($request->oldPassword == "" || $request->newPassword == "" || $request->vernewPassword == ""){
                return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
            }

            $pwd = User::where('id', Auth::user()->id)->first();
            $check_pwd = Hash::check($request->oldPassword, $pwd->password, [true]);

            if ($check_pwd == false){
                return redirect()->back()->with('flash_message_error', 'Kata sandi tidak ditemukan!');
            }else if ($request->vernewPassword != $request->newPassword){
                return redirect()->back()->with('flash_message_error', 'Konfirmasi kata sandi baru salah!');
            } else if ($check_pwd == true && $request->vernewPassword == $request->newPassword){
                Activity::log(Auth::user()->id, 'Update', 'merubah kata sandi', 'Kata sandi telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'password' => bcrypt($request->newPassword)
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Kata sandi Anda berhasil diubah!');
            }
        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Kata sandi Anda gagal diubah!');
        }
    }

    public function mobileUpdatePassword(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        DB::beginTransaction();
        try{
            if ($request->oldpassword == "" || $request->newpassword == "" || $request->confnewpassword == ""){
                $message = array(
                    'status' => "failed",
                    'message'=> "Lengkapi data!"
                );
                return response()->json($message);
            }

            $pwd = User::where('id', Auth::user()->id)->first();
            $check_pwd = Hash::check($request->oldpassword, $pwd->password, [true]);

            if ($check_pwd == false){
                $message = array(
                    'status' => "failed",
                    'message'=> "Kata sandi tidak ditemukan!"
                );
                return response()->json($message);
            }else if ($request->confnewpassword != $request->newpassword){
                $message = array(
                    'status' => "failed",
                    'message'=> "Konfirmasi kata sandi baru salah!"
                );
                return response()->json($message);
            } else if ($check_pwd == true && $request->confnewpassword == $request->newpassword){
                Activity::log(Auth::user()->id, 'Update', 'merubah kata sandi', 'Kata sandi telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'password' => bcrypt($request->newpassword)
                ]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Kata sandi Anda berhasil diperbarui"
                );
                return response()->json($message);
            }
        }catch (\Exception $e){
            DB::rollback();
            $message = array(
                'status' => "failed",
                'message'=> "Kata sandi Anda gagal diperbarui"
            );
            return response()->json($message);
        }
    }

    public function updateTtl(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        if ($request->tempat == "" || $request->tanggal == "" || $request->bulan == "" || $request->tahun == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $tempat = $request->tempat;
                $tgllahir = $request->tahun . '-' . $request->bulan . '-' . $request->tanggal;
                $tgl = $request->tanggal . '-' . $request->bulan . '-' . $request->tahun;
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah tempat, tanggal lahir', 'Diperbarui menjadi Tempat Lahir: '.$tempat.', Tanggal Lahir: '. $tgl, 'Tempat, Tanggal lahir sebelumnya Tempat Lahir: '.$user->tempat_lahir.', Tanggal Lahir: '. date('d-m-Y', strtotime($user->tgl_lahir)), Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'tempat_lahir' => $tempat,
                    'tgl_lahir' => $tgllahir
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Tempat, tanggal lahir Anda berhasil diubah!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Tempat, tanggal lahir Anda gagal diubah!');
            }
        }
    }

    public function mobileUpdateTtl(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        if ($request->tempat == "" || $request->tanggal == "" || $request->bulan == "" || $request->tahun == "") {
            $message = array(
                'status' => "failed",
                'message'=> "Lengkapi data!"
            );
            return response()->json($message);
        } else {
            DB::beginTransaction();
            try{
                $tempat = $request->tempat;
                $tgllahir = $request->tahun . '-' . $request->bulan . '-' . $request->tanggal;
                $tgl = $request->tanggal . '-' . $request->bulan . '-' . $request->tahun;
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah tempat, tanggal lahir', 'Diperbarui menjadi Tempat Lahir: '.$tempat.', Tanggal Lahir: '. $tgl, 'Tempat, Tanggal lahir sebelumnya Tempat Lahir: '.$user->tempat_lahir.', Tanggal Lahir: '. date('d-m-Y', strtotime($user->tgl_lahir)), Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'tempat_lahir' => $tempat,
                    'tgl_lahir' => $tgllahir
                ]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Tempat, tanggal lahir Anda berhasil diperbarui",
                    'data'   => array(
                        'tempat'=> $request->tempat,
                        'tanggal' => $request->tanggal,
                        'bulan' => $request->bulan,
                        'tahun' => $request->tahun
                    )
                );
                return response()->json($message);
            }catch (\Exception $e){
                DB::rollback();
                $message = array(
                    'status' => "failed",
                    'message'=> "Tempat, tanggal lahir Anda gagal diperbarui"
                );
                return response()->json($message);
            }
        }
    }

    public function updateAlamat(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }

        if ($request->alamat == "") {
            return redirect()->back()->with('flash_message_error', 'Lengkapi data!');
        } else {
            DB::beginTransaction();
            try{
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah alamat', 'Diperbarui menjadi ' . $request->alamat, 'Alamat sebelumnya ' . $user->address, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'address' => $request->alamat
                ]);
                DB::commit();
                return redirect()->back()->with('flash_message_success', 'Alamat Anda berhasil diubah!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect()->back()->with('flash_message_error', 'Alamat Anda gagal diubah!');
            }
        }
    }

    public function mobileUpdateAlamat(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }

        if ($request->alamat == "") {
            $message = array(
                'status' => "failed",
                'message'=> "Lengkapi data!"
            );
            return response()->json($message);
        } else {
            DB::beginTransaction();
            try{
                $user = User::where('id', Auth::user()->id)->first();
                Activity::log(Auth::user()->id, 'Update', 'merubah alamat', 'Diperbarui menjadi ' . $request->alamat, 'Alamat sebelumnya ' . $user->address, Carbon::now('Asia/Jakarta'));
                User::where('id', Auth::user()->id)->update([
                    'address' => $request->alamat
                ]);
                DB::commit();
                $message = array(
                    'status' => "success",
                    'message'=> "Alamat Anda berhasil diperbarui",
                    'data'   => array('alamat'=>$request->alamat)
                );
                return response()->json($message);
            }catch (\Exception $e){
                DB::rollback();
                $message = array(
                    'status' => "failed",
                    'message'=> "Alamat Anda gagal diperbarui"
                );
                return response()->json($message);
            }
        }
    }

    public function updateFoto(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        } else {
            return redirect('/login')->with('flash_message_error', 'Anda belum login!');
        }
        
        DB::beginTransaction();
        try{

            if ($request->hasFile('foto')) {
                $image_tmp = Input::file('foto');
                $file = $request->file('foto');
                $image_size = $image_tmp->getSize(); //getClientSize()
                $maxsize = '2097152';
                if ($image_size < $maxsize) {

                    if ($image_tmp->isValid()) {

                        $namefile = $request->current_img;

                        if ($namefile != "") {

                            $path = 'public/images/' . $namefile;

                            if (File::exists($path)) {
                                # code...
                                File::delete($path);
                            }

                        }

                        $extension = $image_tmp->getClientOriginalExtension();
                        $filename = date('YmdHms') . rand(111, 99999) . '.' . $extension;
                        $image_path = 'public/images';

                        if (!is_dir($image_path )) {
                            mkdir("public/images", 0777, true);
                        }

                        ini_set('memory_limit', '256M');
                        $file->move($image_path, $filename);
                        User::where('id', Auth::user()->id)->update(['img' => $filename]);
                        Activity::log(Auth::user()->id, 'Update', 'merubah foto profil', 'Foto profil telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                        DB::commit();
                        return redirect()->back()->with('flash_message_success', 'Foto profil Anda berhasil diperbarui!');
                    }
                } else {

                    return redirect()->back()->with('flash_message_error', 'Foto profil gagal diperbarui...! Ukuran file terlalu besar');

                }
            }

        }catch (\Exception $e){
            DB::rollback();
            return redirect()->back()->with('flash_message_error', 'Foto profil Anda gagal diperbarui!');
        }
    }

    public function mobileUpdateFoto(Request $request)
    {
        if (!Auth::check()) {
            $message = array(
                'status' => "failed",
                'message'=> "Invalid Session! Please, sign in again."
            );
            return response()->json($message);
        }
        
        DB::beginTransaction();
        try{

            if ($request->hasFile('foto')) {
                $image_tmp = Input::file('foto');
                $file = $request->file('foto');
                $image_size = $image_tmp->getSize(); //getClientSize()
                $maxsize = '2097152';
                if ($image_size < $maxsize) {

                    if ($image_tmp->isValid()) {

                        $namefile = $request->current_img;

                        if ($namefile != "") {

                            $path = 'public/images/' . $namefile;

                            if (File::exists($path)) {
                                # code...
                                File::delete($path);
                            }

                        }

                        $extension = $image_tmp->getClientOriginalExtension();
                        $filename = date('YmdHms') . rand(111, 99999) . '.' . $extension;
                        $image_path = 'public/images';

                        if (!is_dir($image_path )) {
                            mkdir("public/images", 0777, true);
                        }

                        ini_set('memory_limit', '256M');
                        $file->move($image_path, $filename);
                        User::where('id', Auth::user()->id)->update(['img' => $filename]);
                        Activity::log(Auth::user()->id, 'Update', 'merubah foto profil', 'Foto profil telah diperbarui', null, Carbon::now('Asia/Jakarta'));
                        DB::commit();
                        $message = array(
                            'status' => "success",
                            'message'=> "Foto profil Anda berhasil diperbarui",
                            'data'  => array('image'=>$filename)
                        );
                        return response()->json($message);
                    }
                } else {
                    $message = array(
                        'status' => "failed",
                        'message'=> "Foto profil gagal diperbarui...! Ukuran file terlalu besar"
                    );
                    return response()->json($message);

                }
            }

        }catch (\Exception $e){
            DB::rollback();
            $message = array(
                'status' => "failed",
                'message'=> "Foto profil Anda gagal diperbarui!"
            );
            return response()->json($message);
        }
    }
}
