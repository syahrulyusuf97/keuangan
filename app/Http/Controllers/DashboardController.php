<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;

class DashboardController extends Controller
{
    public function dashboard()
    {
    	$debit = '';
        $credit = '';
        $saldo = '';
        $credit_last_month = '';
        $check_debit         = Cash::where('c_jenis', 'D')->sum('c_jumlah');
        $check_credit        = Cash::where('c_jenis', 'D')->sum('c_jumlah');
        $month              = date('m');
        $year               = date('Y');
        $last_month         = $month-1%12;
        if($check_debit == NULL && $check_credit == NULL){
            $debit = 0;
            $credit = 0;
            $saldo = 0;
            $credit_last_month = 0;
        } elseif($check_debit != NULL && $check_credit == NULL){
            $debit = Cash::where('c_jenis', 'D')->sum('c_jumlah');
            $credit = 0;
            $saldo = $debit - $credit;
            $credit_last_month = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('c_tanggal', '=', ($last_month==0?'12':$last_month))->sum('c_jumlah');
        } elseif($check_debit == NULL && $check_credit != NULL){
            $debit = 0;
            $credit = Cash::where('c_jenis', 'K')->sum('c_jumlah');
            $saldo = $debit - $credit;
            $credit_last_month = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('c_tanggal', '=', ($last_month==0?'12':$last_month))->sum('c_jumlah');
        } else {
            $debit = Cash::where('c_jenis', 'D')->sum('c_jumlah');
            $credit = Cash::where('c_jenis', 'K')->sum('c_jumlah');
            $saldo = $debit - $credit;
            $credit_last_month = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('c_tanggal', '=', ($last_month==0?'12':$last_month))->sum('c_jumlah');
        }
        return view('admin.dashboard.dashboard')->with(compact('saldo', 'debit', 'credit', 'credit_last_month'));
    }

    public function riwayat($parameter = null)
    {
        $result_debit   = array();
        $result_credit  = array();

        $tanggal        = date('Y-m-d', strtotime($parameter));
        $data_debit     = Cash::where('c_jenis', 'D')->where('c_tanggal', '=', $tanggal)->get();
        $data_credit    = Cash::where('c_jenis', 'K')->where('c_tanggal', '=', $tanggal)->get();

        foreach ($data_debit as $value) {
            $row = array(
                'tanggal' => date('d-m-Y', strtotime($value->c_tanggal)),
                'keterangan' => $value->c_transaksi,
                'jumlah' => $value->c_jumlah
            );

            $result_debit[] = $row;
        }

        foreach ($data_credit as $value) {
            $row = array(
                'tanggal' => date('d-m-Y', strtotime($value->c_tanggal)),
                'keperluan' => $value->c_transaksi,
                'jumlah' => $value->c_jumlah
            );

            $result_credit[] = $row;
        }

        $result_array = array('result_credit'=>$result_credit, 'result_debit'=>$result_debit);

        echo json_encode($result_array);
    }
}
