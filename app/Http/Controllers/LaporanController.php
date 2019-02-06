<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;

class LaporanController extends Controller
{
    public function chart()
    {
        return view('admin.laporan.chart');
    }

    public function chartBulanDebit($bulan = null)
    {
        $month = explode(" ", $bulan);
        $date = date('m', strtotime($month[0]));
        $t = $month[1].'-'.$date.'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            'c_tanggal as month', 'c_jenis')
            ->where('c_jenis', 'D')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'desc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('date' => $debit->month, 'debit' => $debit->jumlah_debit);
        }

        echo json_encode($row);
    }

    public function chartBulanKredit($bulan = null)
    {
        $month = explode(" ", $bulan);
        $date = date('m', strtotime($month[0]));
        $t = $month[1].'-'.$date.'-'.'1';
        $from = date('Y-m-d', strtotime($t));
        $to = date("Y-m-t", strtotime($from));

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            'c_tanggal as month', 'c_jenis')
            ->where('c_jenis', 'K')
            ->whereBetween('c_tanggal', [$from, $to])
            ->groupBy(['c_tanggal', 'c_jenis'])
            ->orderBy('c_tanggal', 'asc')
            ->get();

        foreach ($data_credit as $key => $credit) {
            $row[] = array('date' => $credit->month, 'kredit' => $credit->jumlah_kredit);
        }

        echo json_encode($row);
    }

    public function chartTahun($tahun = null)
    {
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_jenis', 'D')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
            \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
            ->where('c_jenis', 'K')
            ->whereYear('c_tanggal', $tahun)
            ->groupBy(['month', 'c_jenis'])
            ->orderBy('month', 'desc')
            ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        echo json_encode($row);
    }

    public function cashflow()
    {
        //
    }
}
