<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Credit;

class DashboardController extends Controller
{
    public function dashboard()
    {
    	$deposito 			= Deposito::sum('jumlah');
    	$credit 			= Credit::sum('jumlah');
    	$saldo				= $deposito - $credit;
    	$month 				= date('m');
	  	$year 				= date('Y');
	  	$last_month 		= $month-1%12;
	 	// echo ($last_month==0?($year-1):$year)."-".($last_month==0?'12':$last_month);
    	// $credit_last_month 	= ($last_month==0?($year-1):$year)."-".($last_month==0?'12':$last_month);
    	$credit_last_month 	= Credit::whereYear('tanggal', '=', ($last_month==0?($year-1):$year))->whereMonth('tanggal', '=', ($last_month==0?'12':$last_month))->sum('jumlah');
    	$percentase_pendapatan = round($deposito/$credit*100);
        $percentase_pengeluaran = round($credit/$deposito*100);
        return view('admin.dashboard.dashboard')->with(compact('saldo', 'deposito', 'credit', 'credit_last_month', 'percentase_pendapatan', 'percentase_pengeluaran'));
    }
}
