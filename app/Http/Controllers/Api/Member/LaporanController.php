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
    private function chartKasDebetBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = Helper::month($bulan);

        $kas_debet = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debet'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'D')
            ->whereMonth('c_tanggal', $month)
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        if ($kas_debet->count() > 0) {
            foreach ($kas_debet->get() as $key => $debet) {
                $row[] = array('date' => $debet->month, 'debet' => $debet->jumlah_debet);
            }
        }

        $response_data = [
            'bulan' => $bulan.' '.$tahun,
            'data'  => $row
        ];

        return $response_data;
    }

    private function chartBankDebetBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = Helper::month($bulan);

        $bank_debet = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debet'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'D')
            ->whereMonth('c_tanggal', $month)
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        if ($bank_debet->count() > 0) {
            foreach ($bank_debet->get() as $key => $debit) {
                $row[] = array('date' => $debit->month, 'debet' => $debit->jumlah_debet);
            }
        }

        $response_data = [
            'bulan' => $bulan.' '.$tahun,
            'data'  => $row
        ];

        return $response_data;
    }

    private function chartKasKreditBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = Helper::month($bulan);

        $kas_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Kas')
            ->where('c_jenis', 'K')
            ->whereMonth('c_tanggal', $month)
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        if ($kas_kredit->count() > 0) {
            foreach ($kas_kredit->get() as $key => $credit) {
                $row[] = array('date' => $credit->month, 'kredit' => $credit->jumlah_kredit);
            }
        }

        $response_data = [
            'bulan' => $bulan.' '.$tahun,
            'data'  => $row
        ];

        return $response_data;
    }

    private function chartBankKreditBulan($bulan=null, $tahun=null)
    {
        $row    = array();
        $month  = Helper::month($bulan);

        $bank_kredit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw('DATE_FORMAT(c_tanggal, "%d-%m-%Y") as month'), 'c_jenis')
            ->where('c_iduser', Auth::user()->id)
            ->where('c_flagakun', 'Bank')
            ->where('c_jenis', 'K')
            ->whereMonth('c_tanggal', $month)
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc');

        if ($bank_kredit->count() > 0) {
            foreach ($bank_kredit->get() as $key => $credit) {
                $row[] = array('date' => $credit->month, 'kredit' => $credit->jumlah_kredit);
            }
        }

        $response_data = [
            'bulan' => $bulan.' '.$tahun,
            'data'  => $row
        ];

        return $response_data;
    }

    public function statistikBulan(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                if ($request->jenis_akun == 'Kas') {
                    $data_response = [
                        'Kas' => [
                            'debet'  => $this->chartKasDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKasKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else if ($request->jenis_akun == 'Bank') {
                    $data_response = [
                        'Bank' => [
                            'debet'  => $this->chartBankDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartBankKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else if (is_null($request->jenis_akun)) {
                    $data_response = [
                        'Kas' => [
                            'debet'  => $this->chartKasDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKasKreditBulan($request->bulan, $request->tahun)
                        ],
                        'Bank' => [
                            'debet'  => $this->chartBankDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartBankKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else {
                    $data_response = [];
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
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

    private function chartKasTahun($tahun = null)
    {
        $row = array();
        $arr_bulan = [
            'Januari'   => ['debet'=>0, 'kredit'=>0],
            'Februari'  => ['debet'=>0, 'kredit'=>0],
            'Maret'     => ['debet'=>0, 'kredit'=>0],
            'April'     => ['debet'=>0, 'kredit'=>0],
            'Mei'       => ['debet'=>0, 'kredit'=>0],
            'Juni'      => ['debet'=>0, 'kredit'=>0],
            'Juli'      => ['debet'=>0, 'kredit'=>0],
            'Agustus'   => ['debet'=>0, 'kredit'=>0],
            'September' => ['debet'=>0, 'kredit'=>0],
            'Oktober'   => ['debet'=>0, 'kredit'=>0],
            'November'  => ['debet'=>0, 'kredit'=>0],
            'Desember'  => ['debet'=>0, 'kredit'=>0]
        ];

        $kas_debet = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debet'),
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

        if (sizeof($kas_debet) > 0) {
            foreach ($kas_debet as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['debet'] = $value->jumlah_debet;
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

    private function chartBankTahun($tahun = null)
    {
        $row = array();
        $arr_bulan = [
            'Januari'   => ['debet'=>0, 'kredit'=>0],
            'Februari'  => ['debet'=>0, 'kredit'=>0],
            'Maret'     => ['debet'=>0, 'kredit'=>0],
            'April'     => ['debet'=>0, 'kredit'=>0],
            'Mei'       => ['debet'=>0, 'kredit'=>0],
            'Juni'      => ['debet'=>0, 'kredit'=>0],
            'Juli'      => ['debet'=>0, 'kredit'=>0],
            'Agustus'   => ['debet'=>0, 'kredit'=>0],
            'September' => ['debet'=>0, 'kredit'=>0],
            'Oktober'   => ['debet'=>0, 'kredit'=>0],
            'November'  => ['debet'=>0, 'kredit'=>0],
            'Desember'  => ['debet'=>0, 'kredit'=>0]
        ];

        $bank_debet = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debet'),
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

        if (sizeof($bank_debet) > 0) {
            foreach ($bank_debet as $key => $value) {
                if (Helper::monthStrToNumber($value->month) == '01') {
                    $arr_bulan['Januari']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '02') {
                    $arr_bulan['Februari']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '03') {
                    $arr_bulan['Maret']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '04') {
                    $arr_bulan['April']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '05') {
                    $arr_bulan['Mei']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '06') {
                    $arr_bulan['Juni']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '07') {
                    $arr_bulan['Juli']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '08') {
                    $arr_bulan['Agustus']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '09') {
                    $arr_bulan['September']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '10') {
                    $arr_bulan['Oktober']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '11') {
                    $arr_bulan['November']['debet'] = $value->jumlah_debet;
                } else if (Helper::monthStrToNumber($value->month) == '12') {
                    $arr_bulan['Desember']['debet'] = $value->jumlah_debet;
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

    public function statistikTahun(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                if ($request->jenis_akun == 'Kas') {
                    $data_response = [
                        'Kas' => $this->chartKasTahun($request->tahun)
                    ];
                } else if ($request->jenis_akun == 'Bank') {
                    $data_response = [
                        'Bank' => $this->chartBankTahun($request->tahun)
                    ];
                } else if (is_null($request->jenis_akun)) {
                    $data_response = [
                        'Kas' => $this->chartKasTahun($request->tahun),
                        'Bank' => $this->chartBankTahun($request->tahun)
                    ];
                } else {
                    $data_response = [];
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!",
                    'error' => $e
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

    private function chartKategoriKasDebetBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = Helper::month($bulan);

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
        
        return $dtkkdl_dt;
    }

    private function chartKategoriKasKreditBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = Helper::month($bulan);

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
        return $dtkkkl_dt;
    }

    private function chartKategoriBankDebetBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = Helper::month($bulan);

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

        return $dtkbdl_dt;
    }

    private function chartKategoriBankKreditBulan($bulan=null, $tahun=null)
    {
        $thn = $tahun;
        $bln = Helper::month($bulan);

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

        return $dtkbkl_dt;
    }

    public function statistikKategoriBulan(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                if ($request->jenis_akun == 'Kas') {
                    $data_response = [
                        'Kas' => [
                            'debet' => $this->chartKategoriKasDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKategoriKasKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else if ($request->jenis_akun == 'Bank') {
                    $data_response = [
                        'Bank' => [
                            'debet' => $this->chartKategoriBankDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKategoriBankKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else if (is_null($request->jenis_akun)) {
                    $data_response = [
                        'Kas' => [
                            'debet' => $this->chartKategoriKasDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKategoriKasKreditBulan($request->bulan, $request->tahun)
                        ],
                        'Bank' => [
                            'debet' => $this->chartKategoriBankDebetBulan($request->bulan, $request->tahun),
                            'kredit' => $this->chartKategoriBankKreditBulan($request->bulan, $request->tahun)
                        ]
                    ];
                } else {
                    $data_response = [];
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
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

    private function chartKategoriKasDebetTahun($tahun = null)
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
        return $dtkkkl_dt;
    }

    private function chartKategoriBankDebetTahun($tahun = null)
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

        return $dtkbkl_dt;
    }

    public function statistikKategoriTahun(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                if ($request->jenis_akun == 'Kas') {
                    $data_response = [
                        'Kas' => [
                            'debet' => $this->chartKategoriKasDebetTahun($request->tahun),
                            'kredit' => $this->chartKategoriKasKreditTahun($request->tahun)
                        ]
                    ];
                } else if ($request->jenis_akun == 'Bank') {
                    $data_response = [
                        'Bank' => [
                            'debet' => $this->chartKategoriBankDebetTahun($request->tahun),
                            'kredit' => $this->chartKategoriBankKreditTahun($request->tahun)
                        ]
                    ];
                } else if (is_null($request->jenis_akun)) {
                    $data_response = [
                        'Kas' => [
                            'debet' => $this->chartKategoriKasDebetTahun($request->tahun),
                            'kredit' => $this->chartKategoriKasKreditTahun($request->tahun)
                        ],
                        'Bank' => [
                            'debet' => $this->chartKategoriBankDebetTahun($request->tahun),
                            'kredit' => $this->chartKategoriBankKreditTahun($request->tahun)
                        ]
                    ];
                } else {
                    $data_response = [];
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
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

    private function cashflowBulan($bulan=null, $tahun=null)
    {
        // Kas
        $debit_kas              = 0;
        $credit_kas             = 0;
        $saldo_kas              = 0;

        // Bank
        $debit_bank              = 0;
        $credit_bank             = 0;
        $saldo_bank              = 0;

        $debet_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan.' '.$tahun, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan.' '.$tahun, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');

        $saldo_kas  = $debet_kas - $credit_kas;

        // Bank
        $debet_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan.' '.$tahun, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->where('c_tanggal', '<', Helper::dateReverse(Helper::dateFromString($bulan.' '.$tahun, true), 'd-m-Y', 'Y-m-d'))
                        ->sum('c_jumlah');
        $saldo_bank  = $debet_bank - $credit_bank;

        $data['saldo_awal'] = $saldo_bank + $saldo_kas;

        $data_bank_debet     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', Helper::month($bulan))
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_bank_debet) > 0) {
            foreach ($data_bank_debet as $key => $value) {
                $row_bank_debet[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['bank_debet'] = $row_bank_debet;
        } else {
            $data['bank_debet'] = [];
        }

        $data_bank_kredit    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', Helper::month($bulan))
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
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

            $data['bank_kredit'] = $row_bank_kredit;
        } else {
            $data['bank_kredit'] = [];
        }

        $data_kas_debet      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereMonth('c_tanggal', Helper::month($bulan))
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_debet) > 0) {
            foreach ($data_kas_debet as $key => $value) {
                $row_kas_debet[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['kas_debet'] = $row_kas_debet;
        } else {
            $data['kas_debet'] = [];
        }

        $data_kas_kredit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereMonth('c_tanggal', Helper::month($bulan))
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
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

            $data['kas_kredit'] = $row_kas_kredit;
        } else {
            $data['kas_kredit'] = [];
        }

        $data['periode']        = $bulan.' '.$tahun;

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

        $debet_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');

        $saldo_kas  = $debet_kas - $credit_kas;

        // Bank
        $debet_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->whereYear('c_tanggal', '<', $tahun)
                        ->sum('c_jumlah');
        $saldo_bank  = $debet_bank - $credit_bank;

        $data['saldo_awal'] = $saldo_bank + $saldo_kas;

        $data_bank_debet     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_bank_debet) > 0) {
            foreach ($data_bank_debet as $key => $value) {
                $row_bank_debet[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['bank_debet'] = $row_bank_debet;
        } else {
            $data['bank_debet'] = [];
        }

        $data_bank_kredit    = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Bank')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
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

            $data['bank_kredit'] = $row_bank_kredit;
        } else {
            $data['bank_kredit'] = [];
        }

        $data_kas_debet      = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'D')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                                    ->with(['akun' => function($akun){
                                        $akun->select('id', 'kode_akun', 'nama_akun');
                                    }])->get();

        if (sizeof($data_kas_debet) > 0) {
            foreach ($data_kas_debet as $key => $value) {
                $row_kas_debet[] = [
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah,
                    'jenis_transaksi' => $value->c_jenis,
                    'ke_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                ];
            }

            $data['kas_debet'] = $row_kas_debet;
        } else {
            $data['kas_debet'] = [];
        }

        $data_kas_kredit     = Cash::where('c_iduser', Auth::user()->id)
                                    ->where('c_flagakun', 'Kas')
                                    ->where('c_jenis', 'K')
                                    ->whereYear('c_tanggal', $tahun)
                                    ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
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

            $data['kas_kredit'] = $row_kas_kredit;
        } else {
            $data['kas_kredit'] = [];
        }

        $data['periode']        = $tahun;

        return $data;
    }

    public function getCashflow(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                if ($request->jenis_periode == "Bulan") {
                    $data_response = [
                        'cashflow' => $this->cashflowBulan($request->bulan, $request->tahun)
                    ];
                } else if ($request->jenis_periode == "Tahun") {
                    $data_response = [
                        'cashflow' => $this->cashflowTahun($request->tahun)
                    ];
                } else {
                    $data_response = ['cashflow'=>null];
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
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
