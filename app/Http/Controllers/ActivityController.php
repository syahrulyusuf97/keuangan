<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Activity;
use App\Cash;
use DB;
use Auth;
use Response;
use Carbon\Carbon;
\Carbon\Carbon::setLocale('id');

class ActivityController extends Controller
{
    public static function log($user, $action, $title, $note, $oldnote, $date)
    {
        DB::beginTransaction();
        try{
            $activity = new Activity();
            $activity->iduser = $user;
            $activity->action = $action;
            $activity->title = $title;
            $activity->note = $note;
            $activity->oldnote = $oldnote;
            $activity->date = $date;
            $activity->save();
            DB::commit();
            return true;
        }catch (\Exception $e){
            DB::rollback();
            return $e;
        }
    }

    public function historyAktivitas()
    {
        $date = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
            ->orderBy('id', 'desc')
            ->distinct()
            ->limit(10)
            ->get();

        $data = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                'users.name as user',
                'activity.action',
                'activity.title',
                'activity.note',
                'activity.oldnote',
                'activity.created_at',
                'activity.id',
                DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
            ->join('users', 'activity.iduser', '=', 'users.id')
            ->orderBy('activity.id', 'desc')
            ->get();

        return view('member.history.aktivitas')->with(compact('data', 'date'));
    }

    public function historyAktivitasAdmin()
    {
        $date = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
            ->orderBy('id', 'desc')
            ->distinct()
            ->limit(10)
            ->get();

        $data = DB::table('activity')
            ->where('iduser', Auth::user()->id)
            ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                'users.name as user',
                'activity.action',
                'activity.title',
                'activity.note',
                'activity.oldnote',
                'activity.created_at',
                'activity.id',
                DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
            ->join('users', 'activity.iduser', '=', 'users.id')
            ->orderBy('activity.id', 'desc')
            ->get();

        return view('admin.history.aktivitas')->with(compact('data', 'date'));
    }

    public function filterHistoryAktivitas($tanggal)
    {
        if ($tanggal != "null") {
            $tanggal = date('Y-m-d', strtotime($tanggal));
            $date = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->where(DB::raw('substr(date, 1, 10)'), '=', $tanggal)
                ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
                ->orderBy('id', 'desc')
                ->distinct()
                ->get();

            $data = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->where(DB::raw('substr(date, 1, 10)'), '=', $tanggal)
                ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                    'users.name as user',
                    'activity.action',
                    'activity.title',
                    'activity.note',
                    'activity.oldnote',
                    'activity.created_at',
                    'activity.id',
                    DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
                ->join('users', 'activity.iduser', '=', 'users.id')
                ->orderBy('activity.id', 'desc')
                ->get();
        } else {
            $date = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->select(DB::raw("id, DATE_FORMAT(date, '%d %M %Y') as date"))
                ->orderBy('id', 'desc')
                ->distinct()
                ->limit(10)
                ->get();

            $data = DB::table('activity')
                ->where('iduser', Auth::user()->id)
                ->select(DB::raw("DATE_FORMAT(date, '%d %M %Y') as date"),
                    'users.name as user',
                    'activity.action',
                    'activity.title',
                    'activity.note',
                    'activity.oldnote',
                    'activity.created_at',
                    'activity.id',
                    DB::raw("DATE_FORMAT(date, '%d %M %Y %H:%m:%s') as tgl"))
                ->join('users', 'activity.iduser', '=', 'users.id')
                ->orderBy('activity.id', 'desc')
                ->get();
        }


        $result = array();
        foreach ($data as $key => $dt){
            $result[] = [
                'date' => $dt->date,
                'user' => $dt->user,
                'action' => $dt->action,
                'title' => $dt->title,
                'note' => $dt->note,
                'oldnote' => $dt->oldnote,
                'created_at' => $dt->created_at,
                'tgl' => $dt->tgl,
                'times' => Carbon::parse($dt->created_at)->diffForHumans()
            ];
        }

        return Response::json(['tanggal' => $date, 'data' => $result]);
    }

    public function historyKas()
    {
        return view('member.history.kas');
    }

    public function getHistoryKas($parameter = null)
    {
        $result_debit   = array();
        $result_credit  = array();

        $tanggal        = date('Y-m-d', strtotime($parameter));

        $data_debit     = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                            ->with(['akun' => function($akun){
                                $akun->select('id', 'kode_akun', 'nama_akun');
                            }])->get();

        $data_credit    = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                            ->with(['akun' => function($akun){
                                $akun->select('id', 'kode_akun', 'nama_akun');
                            }])->get();

        foreach ($data_debit as $value) {
            $row = array(
                'tanggal'    => date('d-m-Y', strtotime($value->c_tanggal)),
                'akun'       => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun,
                'keterangan' => $value->c_transaksi,
                'jumlah'     => $value->c_jumlah
            );

            $result_debit[] = $row;
        }

        foreach ($data_credit as $value) {
            $row = array(
                'tanggal'   => date('d-m-Y', strtotime($value->c_tanggal)),
                'akun'      => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun,
                'keperluan' => $value->c_transaksi,
                'jumlah'    => $value->c_jumlah
            );

            $result_credit[] = $row;
        }

        $result_array = array('result_credit'=>$result_credit, 'result_debit'=>$result_debit);

        return Response::json($result_array);
    }

    public function historyBank()
    {
        return view('member.history.bank');
    }

    public function getHistoryBank($parameter = null)
    {
        $result_debit   = array();
        $result_credit  = array();

        $tanggal        = date('Y-m-d', strtotime($parameter));

        $data_debit     = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                            ->with(['akun' => function($akun){
                                $akun->select('id', 'kode_akun', 'nama_akun');
                            }])->get();

        $data_credit    = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis', 'cash.c_akun')
                            ->with(['akun' => function($akun){
                                $akun->select('id', 'kode_akun', 'nama_akun');
                            }])->get();

        foreach ($data_debit as $value) {
            $row = array(
                'tanggal'    => date('d-m-Y', strtotime($value->c_tanggal)),
                'akun'       => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun,
                'keterangan' => $value->c_transaksi,
                'jumlah'     => $value->c_jumlah
            );

            $result_debit[] = $row;
        }

        foreach ($data_credit as $value) {
            $row = array(
                'tanggal'   => date('d-m-Y', strtotime($value->c_tanggal)),
                'akun'      => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun,
                'keperluan' => $value->c_transaksi,
                'jumlah'    => $value->c_jumlah
            );

            $result_credit[] = $row;
        }

        $result_array = array('result_credit'=>$result_credit, 'result_debit'=>$result_debit);

        return Response::json($result_array);
    }
}