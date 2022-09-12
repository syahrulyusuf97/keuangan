<?php

namespace App\Http\Controllers\Api\Member;

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
use App\Exports\CashflowExcel;
use App\Exports\CashflowPDF;

class LaporanController extends Controller
{
    private function getAllDateByMonth($bulan, $tahun)
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

    private function chartKasDebitBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = $bulan;
        $dates  = $this->getAllDateByMonth($bulan, $tahun);

        $kas_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
        ->where('c_iduser', Auth::user()->id)
        ->where('c_flagakun', 'Kas')
        ->where('c_jenis', 'D')
        ->whereMonth('c_tanggal', $month)
        ->whereYear('c_tanggal', $tahun)
        ->groupBy(['c_tanggal', 'c_jenis'])
        ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($kas_debit->count() > 0) {
            foreach ($kas_debit->get() as $key => $debit) {
                $row[(String)date('d', strtotime($debit->date))] = (float)$debit->jumlah_debit;
            }
        }

        $response_data = [
            'bulan' => $tahun.'-'.$bulan,
            'detail'  => $row
        ];

        return $response_data;
    }

    private function chartBankDebitBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = $bulan;
        $dates  = $this->getAllDateByMonth($bulan, $tahun);

        $bank_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
        ->where('c_iduser', Auth::user()->id)
        ->where('c_flagakun', 'Bank')
        ->where('c_jenis', 'D')
        ->whereMonth('c_tanggal', $month)
        ->whereYear('c_tanggal', $tahun)
        ->groupBy(['c_tanggal', 'c_jenis'])
        ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($bank_debit->count() > 0) {
            foreach ($bank_debit->get() as $key => $debit) {
                $row[(String)date('d', strtotime($debit->date))] = (float)$debit->jumlah_debit;
            }
        }

        $response_data = [
            'bulan' => $tahun.'-'.$bulan,
            'detail'  => $row
        ];

        return $response_data;
    }

    private function chartKasKreditBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = $bulan;
        $dates  = $this->getAllDateByMonth($bulan, $tahun);

        $kas_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
        ->where('c_iduser', Auth::user()->id)
        ->where('c_flagakun', 'Kas')
        ->where('c_jenis', 'K')
        ->whereMonth('c_tanggal', $month)
        ->whereYear('c_tanggal', $tahun)
        ->groupBy(['c_tanggal', 'c_jenis'])
        ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($kas_kredit->count() > 0) {
            foreach ($kas_kredit->get() as $key => $credit) {
                $row[(String)date('d', strtotime($credit->date))] = (float)$credit->jumlah_kredit;
            }
        }

        $response_data = [
            'bulan' => $tahun.'-'.$bulan,
            'detail'  => $row
        ];

        return $response_data;
    }

    private function chartBankKreditBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = $bulan;
        $dates  = $this->getAllDateByMonth($bulan, $tahun);

        $bank_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
        \DB::raw('c_tanggal as date'), 'c_jenis')
        ->where('c_iduser', Auth::user()->id)
        ->where('c_flagakun', 'Bank')
        ->where('c_jenis', 'K')
        ->whereMonth('c_tanggal', $month)
        ->whereYear('c_tanggal', $tahun)
        ->groupBy(['c_tanggal', 'c_jenis'])
        ->orderBy('c_tanggal', 'asc');

        foreach($dates as $key => $values) {
            $row[(String)$values] = 0;
        }

        if ($bank_kredit->count() > 0) {
            foreach ($bank_kredit->get() as $key => $credit) {
                $row[(String)date('d', strtotime($credit->date))] = (float)$credit->jumlah_kredit;
            }
        }

        $response_data = [
            'bulan' => $tahun.'-'.$bulan,
            'detail'  => $row
        ];

        return $response_data;
    }

    private function chartBankTahun($tahun = null)
    {
        $row = array();
        $arr_bulan = [
            'Januari'   => ['debit'=>0, 'kredit'=>0],
            'Februari'  => ['debit'=>0, 'kredit'=>0],
            'Maret'     => ['debit'=>0, 'kredit'=>0],
            'April'     => ['debit'=>0, 'kredit'=>0],
            'Mei'       => ['debit'=>0, 'kredit'=>0],
            'Juni'      => ['debit'=>0, 'kredit'=>0],
            'Juli'      => ['debit'=>0, 'kredit'=>0],
            'Agustus'   => ['debit'=>0, 'kredit'=>0],
            'September' => ['debit'=>0, 'kredit'=>0],
            'Oktober'   => ['debit'=>0, 'kredit'=>0],
            'November'  => ['debit'=>0, 'kredit'=>0],
            'Desember'  => ['debit'=>0, 'kredit'=>0]
        ];

        $bank_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $bank_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        if (sizeof($bank_debit) > 0) {
            foreach ($bank_debit as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['debit'] = $value->jumlah_debit;
                }
            }
        }

        if (sizeof($bank_kredit) > 0) {
            foreach ($bank_kredit as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['kredit'] = $value->jumlah_kredit;
                }
            }
        }

        return $arr_bulan;
    }

    private function chartKasTahun($tahun = null)
    {
        $row = array();
        $arr_bulan = [
            'Januari'   => ['debit'=>0, 'kredit'=>0],
            'Februari'  => ['debit'=>0, 'kredit'=>0],
            'Maret'     => ['debit'=>0, 'kredit'=>0],
            'April'     => ['debit'=>0, 'kredit'=>0],
            'Mei'       => ['debit'=>0, 'kredit'=>0],
            'Juni'      => ['debit'=>0, 'kredit'=>0],
            'Juli'      => ['debit'=>0, 'kredit'=>0],
            'Agustus'   => ['debit'=>0, 'kredit'=>0],
            'September' => ['debit'=>0, 'kredit'=>0],
            'Oktober'   => ['debit'=>0, 'kredit'=>0],
            'November'  => ['debit'=>0, 'kredit'=>0],
            'Desember'  => ['debit'=>0, 'kredit'=>0]
        ];

        $kas_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $kas_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        if (sizeof($kas_debit) > 0) {
            foreach ($kas_debit as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['debit'] = $value->jumlah_debit;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['debit'] = $value->jumlah_debit;
                }
            }
        }

        if (sizeof($kas_kredit) > 0) {
            foreach ($kas_kredit as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['kredit'] = $value->jumlah_kredit;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['kredit'] = $value->jumlah_kredit;
                }
            }
        }

        return $arr_bulan;
    }

    private function chartKategoriKasDebitBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = $bulan;

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
                    'kategori'  => $kategori->nama, 
                    // 'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                    'warna'     => $kategori->warna, 
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
        
        return $dtkkdl_dt;
    }

    private function chartKategoriKasKreditBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = $bulan;

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
                    'kategori'  => $kategori->nama, 
                    // 'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                    'warna'     => $kategori->warna, 
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
        return $dtkkkl_dt;
    }

    private function chartKategoriBankDebitBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = $bulan;

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
                'kategori'  => $kategori->nama, 
                // 'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'warna'     => $kategori->warna, 
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

        return $dtkbdl_dt;
    }

    private function chartKategoriBankKreditBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = $bulan;

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
                'kategori'  => $kategori->nama, 
                // 'warna'     => implode('0xff', explode('#', $kategori->warna)), 
                'warna'     => $kategori->warna, 
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

        return $dtkbkl_dt;
    }

    private function chartKategoriKasDebitTahun($tahun = null)
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
                'kategori'  => $kategori->nama, 
                'warna'     => $kategori->warna, 
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
        return $dtkkdl_dt;
    }

    private function chartKategoriKasKreditTahun($tahun = null)
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
                'kategori'  => $kategori->nama, 
                'warna'     => $kategori->warna, 
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
        return $dtkkkl_dt;
    }

    private function chartKategoriBankDebitTahun($tahun = null)
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
                'kategori'  => $kategori->nama, 
                'warna'     => $kategori->warna, 
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

        return $dtkbdl_dt;
    }

    private function chartKategoriBankKreditTahun($tahun = null)
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

        return $dtkbkl_dt;
    }

    public function statistik(Request $request)
    {
        try {
            $jenis_periode = $request->jenis_periode;
            $jenis_akun    = $request->jenis_akun;
            $periode       = $request->periode;
            $kategori      = $request->kategori == "" ? false : $request->kategori;
            
            if ($kategori) {
                if ($jenis_periode == "BULAN") {
                    $bulan = explode('-', $periode)[1];
                    $tahun = explode('-', $periode)[0];

                    if ($jenis_akun == 'BANK') {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartKategoriBankDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKategoriBankKreditBulan($bulan, $tahun)
                            ]
                        ];
                    } elseif ($jenis_akun == 'KAS') {
                        $data_response = [
                            'Kas' => [
                                'debit'  => $this->chartKategoriKasDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKategoriKasKreditBulan($bulan, $tahun)
                            ]
                        ];
                    } else {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartKategoriBankDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKategoriBankKreditBulan($bulan, $tahun)
                            ],
                            'Kas' => [
                                'debit'  => $this->chartKategoriKasDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKategoriKasKreditBulan($bulan, $tahun)
                            ]
                        ];
                    }
                } else {
                    if ($jenis_akun == 'BANK') {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartKategoriBankDebitTahun($periode),
                                'kredit' => $this->chartKategoriBankKreditTahun($periode)
                            ]
                        ];
                    } elseif ($jenis_akun == 'KAS') {
                        $data_response = [
                            'Kas' => [
                                'debit'  => $this->chartKategoriKasDebitTahun($periode),
                                'kredit' => $this->chartKategoriKasKreditTahun($periode)
                            ]
                        ];
                    } else {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartKategoriBankDebitTahun($periode),
                                'kredit' => $this->chartKategoriBankKreditTahun($periode)
                            ],
                            'Kas' => [
                                'debit'  => $this->chartKategoriKasDebitTahun($periode),
                                'kredit' => $this->chartKategoriKasKreditTahun($periode)
                            ]
                        ];
                    }
                }
            } else {
                if ($jenis_periode == "BULAN") {
                    $bulan = explode('-', $periode)[1];
                    $tahun = explode('-', $periode)[0];
                    
                    if ($jenis_akun == 'BANK') {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartBankDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartBankKreditBulan($bulan, $tahun)
                            ]
                        ];
                    } elseif ($jenis_akun == 'KAS') {
                        $data_response = [
                            'Kas' => [
                                'debit'  => $this->chartKasDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKasKreditBulan($bulan, $tahun)
                            ]
                        ];
                    } else {
                        $data_response = [
                            'Bank' => [
                                'debit'  => $this->chartBankDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartBankKreditBulan($bulan, $tahun)
                            ],
                            'Kas' => [
                                'debit'  => $this->chartKasDebitBulan($bulan, $tahun),
                                'kredit' => $this->chartKasKreditBulan($bulan, $tahun)
                            ]
                        ];
                    }
                } else {
                    if ($jenis_akun == 'BANK') {
                        $data_response = [
                            'Bank' => $this->chartBankTahun($periode)
                        ];
                    } elseif ($jenis_akun == 'KAS') {
                        $data_response = [
                            'Kas' => $this->chartKasTahun($periode)
                        ];
                    } else {
                        $data_response = [
                            'Bank' => $this->chartBankTahun($periode),
                            'Kas' => $this->chartKasTahun($periode)
                        ];
                    }
                }
            }

            $response = [
                'success'    => true,
                'message'    => "Data tersedia",
                'error_code' => null,
                'data'       => $data_response
            ];
        } catch (Exception $e) {
            $response = [
                'success'    => false,
                'message'    => Helper::errorCode(1401),
                'error_code' => 1401,
                'data'       => []
            ];
        }

        return response()->json($response);
    }

    private function cashflowBulan($bulan=null, $tahun=null)
    {
        // Kas
        $debit_kas   = 0;
        $credit_kas  = 0;
        $saldo_kas   = 0;

        // Bank
        $debit_bank  = 0;
        $credit_bank = 0;
        $saldo_bank  = 0;

        $date_first  = $tahun.'-'.$bulan.'-01';

        $debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<',$date_first)
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', $date_first)
                        ->sum('c_jumlah');

        $saldo_kas  = $debit_kas - $credit_kas;

        // Bank
        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<', $date_first)
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', $date_first)
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        $data['saldo_awal'] = $saldo_bank + $saldo_kas;

        $data_bank_debit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', $bulan)
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_bank_debit) > 0) {
            foreach ($data_bank_debit as $key => $value) {
                $row_bank_debit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['BANK']['debit'] = $row_bank_debit;
        } else {
            $data['BANK']['debit'] = [];
        }

        $data_bank_kredit    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', $bulan)
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_bank_kredit) > 0) {
            foreach ($data_bank_kredit as $key => $value) {
                $row_bank_kredit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['BANK']['kredit'] = $row_bank_kredit;
        } else {
            $data['BANK']['kredit'] = [];
        }

        $data_kas_debit      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', $bulan)
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_debit) > 0) {
            foreach ($data_kas_debit as $key => $value) {
                $row_kas_debit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['KAS']['debit'] = $row_kas_debit;
        } else {
            $data['KAS']['debit'] = [];
        }

        $data_kas_kredit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', $bulan)
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_kredit) > 0) {
            foreach ($data_kas_kredit as $key => $value) {
                $row_kas_kredit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['KAS']['kredit'] = $row_kas_kredit;
        } else {
            $data['KAS']['kredit'] = [];
        }

        $data['periode']        = $tahun.'-'.$bulan;

        return $data;
    }

    private function cashflowTahun($tahun = null)
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

        $data_bank_debit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_bank_debit) > 0) {
            foreach ($data_bank_debit as $key => $value) {
                $row_bank_debit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['BANK']['debit'] = $row_bank_debit;
        } else {
            $data['BANK']['debit'] = [];
        }

        $data_bank_kredit    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->where('c_jenis', 'K')->get();

        if (sizeof($data_bank_kredit) > 0) {
            foreach ($data_bank_kredit as $key => $value) {
                $row_bank_kredit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['BANK']['kredit'] = $row_bank_kredit;
        } else {
            $data['BANK']['kredit'] = [];
        }

        $data_kas_debit      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_debit) > 0) {
            foreach ($data_kas_debit as $key => $value) {
                $row_kas_debit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['KAS']['debit'] = $row_kas_debit;
        } else {
            $data['KAS']['debit'] = [];
        }

        $data_kas_kredit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select('cash.c_tanggal', 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_kredit) > 0) {
            foreach ($data_kas_kredit as $key => $value) {
                $row_kas_kredit[] = [
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['KAS']['kredit'] = $row_kas_kredit;
        } else {
            $data['KAS']['kredit'] = [];
        }

        $data['periode'] = $tahun;

        return $data;
    }

    public function getCashflow(Request $request)
    {
        try{
            $jenis_periode = $request->jenis_periode;
            $periode       = $request->periode;

            if ($jenis_periode == "BULAN") {
                $bulan = explode('-', $periode)[1];
                $tahun = explode('-', $periode)[0];

                $data_response = [
                    'cashflow' => $this->cashflowBulan($bulan, $tahun)
                ];
            } else{
                $data_response = [
                    'cashflow' => $this->cashflowTahun($periode)
                ];
            }

            $response = [
                'success'    => true,
                'message'    => "Data tersedia",
                'error_code' => null,
                'data'       => $data_response
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
    }
}
