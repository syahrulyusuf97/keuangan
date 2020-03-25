<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\ActivityController as Activity;
use App\Cash;
use App\User;
use App\Kategori;
use File;
use Auth;
use DB;
use Helper;
use Response;
use Session;
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

    public function dashboard()
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

        return view('member.dashboard.dashboard')->with(compact('saldo_kas', 'debit_kas_last_month', 'credit_kas_last_month', 'debit_kas_last_year', 'credit_kas_last_year', 'saldo_bank', 'debit_bank_last_month', 'credit_bank_last_month', 'debit_bank_last_year', 'credit_bank_last_year'));
    }

    public function chartKKDBL()
    {
        $ktg_kas_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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
        
        return Response::json($dtkkdl_dt);
    }

    public function chartKKKBL()
    {
        $ktg_kas_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkkkl_dt);
    }

    public function chartKBDBL()
    {
        $ktg_bank_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkbdl_dt);
    }

    public function chartKBKBL()
    {
        $ktg_bank_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkbkl_dt);
    }

    public function chartKKDTL()
    {
        $ktg_kas_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkkdl_dt);
    }

    public function chartKKKTL()
    {
        $ktg_kas_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkkkl_dt);
    }

    public function chartKBDTL()
    {
        $ktg_bank_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkbdl_dt);
    }

    public function chartKBKTL()
    {
        $ktg_bank_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
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

        return Response::json($dtkbkl_dt);
    }

    public function chartBulanDebitKas()
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

        return Response::json($row);
    }

    public function chartBulanDebitBank()
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

        return Response::json($row);
    }

    public function chartBulanKreditKas()
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

        return Response::json($row);
    }

    public function chartBulanKreditBank()
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

        return Response::json($row);
    }

    public function grafikKas()
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

        return Response::json($row);
    }

    public function grafikBank()
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

        return Response::json($row);
    }

    public function profil()
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        $tgllahir = User::select('tgl_lahir')
            ->where('id', Auth::user()->id)->first();
        $date = [];
        $date = explode('-', $tgllahir->tgl_lahir);
        $day = $date[2];
        $month = $date[1];
        $year = $date[0];
        return view('member.profile.index')->with(compact('day', 'month', 'year'));
    }

    public function updateNama(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

    public function updateEmail(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

    public function updateUsername(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

    public function updatePassword(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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
                return redirect()->back()->with('flash_message_error', 'Verifikasi kata sandi baru salah!');
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

    public function updateTtl(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

    public function updateAlamat(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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

    public function updateFoto(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
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
}
