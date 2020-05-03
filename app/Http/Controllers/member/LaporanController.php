<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;
use Excel;
use PDF;
use Helper;
use Auth;
use Session;
use App\Exports\CashflowExcel;
use App\Exports\CashflowPDF;

class LaporanController extends Controller
{
    public function chart()
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        return view('member.laporan.chart');
    }

    public function chartBulanDebitKas($bulan = null)
    {
        $row = array();
        $month = explode(" ", $bulan);
        $t = $month[1].'-'.Helper::month($month[0]).'-'.'1';
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

    public function chartBulanDebitBank($bulan = null)
    {
        $row = array();
        $month = explode(" ", $bulan);
        $t = $month[1].'-'.Helper::month($month[0]).'-'.'1';
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

    public function chartBulanKreditKas($bulan = null)
    {
        $row = array();
        $month = explode(" ", $bulan);
        $t = $month[1].'-'.Helper::month($month[0]).'-'.'1';
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

    public function chartBulanKreditBank($bulan = null)
    {
        $row = array();
        $month = explode(" ", $bulan);
        $t = $month[1].'-'.Helper::month($month[0]).'-'.'1';
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

    public function chartTahunKas($tahun = null)
    {
        $row = array();
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        usort($row, function($a, $b) {
            return Helper::monthStrToNumber($a['month']) <=> Helper::monthStrToNumber($b['month']);
        });

        return Response::json($row);
    }

    public function chartTahunBank($tahun = null)
    {
        $row = array();
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        usort($row, function($a, $b) {
            return Helper::monthStrToNumber($a['month']) <=> Helper::monthStrToNumber($b['month']);
        });

        return Response::json($row);
    }

    public function chartKKDB($bulan = null)
    {
        $month = explode(" ", $bulan);
        $thn = $month[1];
        $bln = Helper::month($month[0]);

        $ktg_kas_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $thn)
                        ->whereMonth('c.c_tanggal', '=', $bln)
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

    public function chartKKKB($bulan = null)
    {
        $month = explode(" ", $bulan);
        $thn = $month[1];
        $bln = Helper::month($month[0]);

        $ktg_kas_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $thn)
                        ->whereMonth('c.c_tanggal', '=', $bln)
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

    public function chartKBDB($bulan = null)
    {
        $month = explode(" ", $bulan);
        $thn = $month[1];
        $bln = Helper::month($month[0]);

        $ktg_bank_dbt_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $thn)
                        ->whereMonth('c.c_tanggal', '=', $bln)
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

    public function chartKBKB($bulan = null)
    {
        $month = explode(" ", $bulan);
        $thn = $month[1];
        $bln = Helper::month($month[0]);

        $ktg_bank_krd_lm = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $thn)
                        ->whereMonth('c.c_tanggal', '=', $bln)
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

    public function chartKKDT($tahun = null)
    {
        $ktg_kas_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $tahun)
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

    public function chartKKKT($tahun = null)
    {
        $ktg_kas_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Kas')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $tahun)
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

    public function chartKBDT($tahun = null)
    {
        $ktg_bank_dbt_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'D')
                        ->whereYear('c.c_tanggal', '=', $tahun)
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

    public function chartKBKT($tahun = null)
    {
        $ktg_bank_krd_ly = DB::table('cash as c')
                        ->join('ms_kategori as ktg', 'c.c_kategori', 'ktg.id')
                        ->where('c.c_iduser', Auth::user()->id)
                        ->where('c.c_flagakun', 'Bank')
                        ->where('c.c_jenis', 'K')
                        ->whereYear('c.c_tanggal', '=', $tahun)
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

    public function cashflow()
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        return view('member.laporan.cashflow');
    }

    public function cashflowBulan($bulan = null)
    {
        $month                  = explode(" ", $bulan);

        // Kas
        $debit_kas              = 0;
        $credit_kas             = 0;
        $saldo_kas              = 0;

        // Bank
        $debit_bank              = 0;
        $credit_bank             = 0;
        $saldo_bank              = 0;

        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');

        $saldo_kas  = $debit_kas - $credit_kas;

        // Bank
        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        $data['saldo_awal'] = $saldo_bank + $saldo_kas;

        $data['bank_debit']     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', Helper::month($month[0]))
                                    ->whereYear('c_tanggal', $month[1])
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['bank_kredit']    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', Helper::month($month[0]))
                                    ->whereYear('c_tanggal', $month[1])
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['kas_debit']      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', Helper::month($month[0]))
                                    ->whereYear('c_tanggal', $month[1])
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['kas_kredit']     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', Helper::month($month[0]))
                                    ->whereYear('c_tanggal', $month[1])
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['periode']        = $bulan;

        return Response::json($data);
    }

    public function cashflowTahun($tahun = null)
    {
        // Kas
        $debit_kas              = 0;
        $credit_kas             = 0;
        $saldo_kas              = 0;

        // Bank
        $debit_bank              = 0;
        $credit_bank             = 0;
        $saldo_bank              = 0;

        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');

        $saldo_kas  = $debit_kas - $credit_kas;

        // Bank
        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        $data['saldo_awal'] = $saldo_bank + $saldo_kas;

        $data['bank_debit']     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['bank_kredit']    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->where('c_jenis', 'K')->get();

        $data['kas_debit']      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        $data['kas_kredit']     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        return Response::json($data);
    }

    public function excel($month, $year)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        return (new CashflowExcel($month, $year))->download('Cashflow.xlsx');
    }

    public function pdf($month, $year)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }
        
        if ($year == "null") {
            $bulan = explode(" ", $month);

            $bank_debit 	= DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'D')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($bulan[0])))
                                ->whereYear('cash.c_tanggal', $bulan[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $bank_kredit    = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'K')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($bulan[0])))
                                ->whereYear('cash.c_tanggal', $bulan[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_debit      = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'D')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($bulan[0])))
                                ->whereYear('cash.c_tanggal', $bulan[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_kredit     = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'K')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($bulan[0])))
                                ->whereYear('cash.c_tanggal', $bulan[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $periode = $month;
            $param = 'bulan';
        } else if ($month == "null") {

            $bank_debit 	= DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'D')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $bank_kredit    = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'K')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_debit      = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'D')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_kredit     = DB::table('cash')
            					->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
            					->where('cash.c_iduser', Auth::user()->id)
            					->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'K')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $periode = $year;
            $param = 'tahun';
        }
        $data['bank_debit']     = $bank_debit;
        $data['bank_kredit']    = $bank_kredit;
        $data['kas_debit']      = $kas_debit;
        $data['kas_kredit']     = $kas_kredit;
        $data['periode']        = $periode;
        $data['param']          = $param;
        
        $pdf = PDF::loadView('member.laporan.pdf', $data);
        return $pdf->download('Cashflow.pdf');
    }
}
