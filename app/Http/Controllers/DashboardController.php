<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Credit;

class DashboardController extends Controller
{
    public function dashboard()
    {
    	$deposito = '';
        $credit = '';
        $saldo = '';
        $credit_last_month = '';
        $check_deposito         = Deposito::sum('jumlah');
        $check_credit           = Credit::sum('jumlah');
        $month              = date('m');
        $year               = date('Y');
        $last_month         = $month-1%12;
        if($check_deposito == NULL && $check_credit == NULL){
            $deposito = 0;
            $credit = 0;
            $saldo = 0;
            $credit_last_month = 0;
        } elseif($check_deposito != NULL && $check_credit == NULL){
            $deposito = Deposito::sum('jumlah');
            $credit = 0;
            $saldo = $deposito - $credit;
            $credit_last_month = Credit::whereYear('tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('tanggal', '=', ($last_month==0?'12':$last_month))->sum('jumlah');
        } elseif($check_deposito == NULL && $check_credit != NULL){
            $deposito = 0;
            $credit = Credit::sum('jumlah');
            $saldo = $deposito - $credit;
            $credit_last_month = Credit::whereYear('tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('tanggal', '=', ($last_month==0?'12':$last_month))->sum('jumlah');
        } else {
            $deposito = Deposito::sum('jumlah');
            $credit = Credit::sum('jumlah');
            $saldo = $deposito - $credit;
            $credit_last_month = Credit::whereYear('tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('tanggal', '=', ($last_month==0?'12':$last_month))->sum('jumlah');
        }
        return view('admin.dashboard.dashboard')->with(compact('saldo', 'deposito', 'credit', 'credit_last_month'));
    }
}
