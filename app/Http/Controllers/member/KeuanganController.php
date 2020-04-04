<?php

namespace App\Http\Controllers\member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cash;
use App\Akun;
use App\Kategori;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DataTables;
use DB;
use Response;
use Auth;
use Helper;
use Session;

class KeuanganController extends Controller
{    
    // Start Kas
    public function debit(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
            $tgl_explode = explode(" ", $data['tanggal']);
            $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
            $tanggal = date('Y-m-d', strtotime($tgl));
    		$jumlah = Helper::formatPrice($data['jumlah']);
            $keakun = explode("_", $data['keakun'])[0];
            $flagakun = explode("_", $data['keakun'])[1];

            try {
                $keakun = Crypt::decrypt($keakun);
                $kategori = Crypt::decrypt($data['kategori']);
            } catch (DecryptException $e) {
                return view('errors/404');
            }

    		DB::beginTransaction();
    		try{
                $debit = new Cash;
                $debit->c_transaksi 	= $data['ket'];
                $debit->c_jumlah		= $jumlah;
                $debit->c_jenis         = "D";
                $debit->c_tanggal       = $tanggal;
                $debit->c_kategori      = $kategori;
                $debit->c_akun          = $keakun;
                $debit->c_flag          = "Pemasukan";
                $debit->c_flagakun      = $flagakun;
                $debit->c_iduser        = Auth::user()->id;
                $debit->created_at      = Carbon::now('Asia/Jakarta');
                $debit->updated_at      = Carbon::now('Asia/Jakarta');
                $debit->save();

                Activity::log(Auth::user()->id, 'Create', 'membuat kas masuk', $tgl . ' ' .$data['ket'] . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . explode("_", $data['keakun'])[2], null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/kas/masuk')->with('flash_message_success', 'Kas masuk berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/kas/masuk')->with('flash_message_error', 'Kas masuk gagal disimpan!');
            }
    	}

        $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', 'Kas')->get();
        $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', 'Pemasukan')->get();

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

    	return view('member.kas.debit.index')->with(compact('akun', 'kategori', 'saldo_kas'));
    }

    public function getDebit()
    {
        $data = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'D')
                ->orderBy('c_tanggal', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('keakun', function ($data) {

                return "(".$data->akun->kode_akun.") ".$data->akun->nama_akun;

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/kas/masuk/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'keakun', 'aksi'])

            ->make(true);
    }

    public function deleteDebit($id = null)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
            Activity::log(Auth::user()->id, 'Delete', 'menghapus kas masuk', $cash->c_tanggal .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
            Cash::where(['c_id'=> $id])->delete();
            DB::commit();
            return redirect('/kas/masuk')->with('flash_message_success', 'Berhasil menghapus kas masuk!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/kas/masuk')->with('flash_message_error', 'Gagal menghapus kas masuk!');
        }
    }

    public function getCurrentDebit($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json(['status'=>"Failed"]);
        }

        $data = Cash::with(['akun'])->where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keterangan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal,
            'kategori' => $data->c_kategori,
            'keakun' => $data->akun->kode_akun
        );

        return Response::json($results);

    }

    public function updateDebit(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

    	$data = $request->all();
        $tgl_explode = explode(" ", $data['tanggal_edit']);
        $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
        $tanggal = date('Y-m-d', strtotime($tgl));
    	$jumlah = Helper::formatPrice($data['jumlah_edit']);
        $keakun = explode("_", $data['keakun_edit'])[0];
        $flagakun = explode("_", $data['keakun_edit'])[1];
    	$id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
            $keakun = Crypt::decrypt($keakun);
            $kategori = Crypt::decrypt($data['kategori_edit']);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

    	DB::beginTransaction();
    	try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

            Activity::log(Auth::user()->id, 'Update', 'memperbarui kas masuk', 'Diperbarui menjadi ' . $tgl . ' ' . $data['ket_edit'] . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.explode("_", $data['keakun_edit'])[2].'"', 'Transaksi sebelumnya ' . $cash->c_tanggal . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['ket_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal,
                'c_kategori'    => $kategori,
                'c_akun'        => $keakun,
                'c_flagakun'    => $flagakun,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            DB::commit();

            return redirect('/kas/masuk')->with('flash_message_success', 'Berhasil mengubah kas masuk!');
        }catch (\Exception $e){
    	    DB::rollback();
            return redirect('/kas/masuk')->with('flash_message_error', 'Gagal mengubah kas masuk!'.$e);
        }

    }

    public function credit(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
    		$jumlah = Helper::formatPrice($data['jumlah']);
            $tgl_explode = explode(" ", $data['tanggal']);
            $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
            $tanggal = date('Y-m-d', strtotime($tgl));
            $dariakun = explode("_", $data['dariakun'])[0];
            $flagakun = explode("_", $data['dariakun'])[1];

            try {
                $dariakun = Crypt::decrypt($dariakun);
                $kategori = Crypt::decrypt($data['kategori']);
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
            try{
                $credit = new Cash;
                $credit->c_transaksi = $data['kep'];
                $credit->c_jumlah	 = $jumlah;
                $credit->c_jenis	 = "K";
                $credit->c_tanggal   = $tanggal;
                $credit->c_kategori  = $kategori;
                $credit->c_akun      = $dariakun;
                $credit->c_flag      = "Pengeluaran";
                $credit->c_flagakun  = $flagakun;
                $credit->c_iduser    = Auth::user()->id;
                $credit->created_at  = Carbon::now('Asia/Jakarta');
                $credit->updated_at  = Carbon::now('Asia/Jakarta');
                $credit->save();

                Activity::log(Auth::user()->id, 'Create', 'membuat kas keluar', $tgl . ' ' .$data['kep'] . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . explode("_", $data['dariakun'])[2], null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/kas/keluar')->with('flash_message_success', 'Kas keluar berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/kas/keluar')->with('flash_message_error', 'Kas keluar gagal disimpan!');
            }

    	}

        $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', 'Kas')->get();
        $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', 'Pengeluaran')->get();

    	$debit_kas  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_kas = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Kas')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_kas  = $debit_kas - $credit_kas;
    	
    	return view('member.kas.credit.index')->with(compact('akun', 'kategori', 'saldo_kas'));
    }

    public function getCredit()
    {
        $data = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'K')
                ->orderBy('c_tanggal', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('dariakun', function ($data) {

                return "(".$data->akun->kode_akun.") ".$data->akun->nama_akun;

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/kas/keluar/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'dariakun', 'aksi'])

            ->make(true);
    }

    public function deleteCredit($id = null)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
            Activity::log(Auth::user()->id, 'Delete', 'menghapus kas keluar', $cash->c_tanggal . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
            Cash::where(['c_id'=> $id])->delete();
            DB::commit();
            return redirect('/kas/keluar')->with('flash_message_success', 'kas keluar berhasil dihapus!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/kas/keluar')->with('flash_message_error', 'kas keluar gagal dihapus!');
        }

    }

    public function getCurrentCredit($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json(['status'=>"Failed"]);
        }

        $data = Cash::with(['akun'])->where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keperluan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal,
            'kategori' => $data->c_kategori,
            'dariakun' => $data->akun->kode_akun
        );

        return Response::json($results);
    }

    public function updateCredit(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

    	$data = $request->all();
    	$jumlah = Helper::formatPrice($data['jumlah_edit']);
        $tgl_explode = explode(" ", $data['tanggal_edit']);
        $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
        $tanggal = date('Y-m-d', strtotime($tgl));
        $dariakun = explode("_", $data['dariakun_edit'])[0];
        $flagakun = explode("_", $data['dariakun_edit'])[1];
        $id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
            $dariakun = Crypt::decrypt($dariakun);
            $kategori = Crypt::decrypt($data['kategori_edit']);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

            Activity::log(Auth::user()->id, 'Update', 'memperbarui kas keluar', 'Diperbarui menjadi ' . $tgl . ' ' . $data['kep_edit'] . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.explode("_", $data['dariakun_edit'])[2].'"', 'Transaksi sebelumnya ' . $cash->c_tanggal . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['kep_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal,
                'c_kategori'    => $kategori,
                'c_akun'        => $dariakun,
                'c_flagakun'    => $flagakun,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            DB::commit();

            return redirect('/kas/keluar')->with('flash_message_success', 'Berhasil mengubah kas keluar!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/kas/keluar')->with('flash_message_error', 'Gagal mengubah kas keluar!');
        }

    }

    public function akumulasiTotalCredit($parameter = null)
    {
        $param           = explode("_", $parameter);
        $tampil          = $param[0];
        $tanggal         = $param[1];
        $result          = array();
        $row             = array();
        $last_month      = date('m-Y', strtotime('-1 months'));
        $last_month_year = explode("-", $last_month)[1];
        $last_month_month = explode("-", $last_month)[0];
        $last_year       = date('Y', strtotime('-1 year'));

        if ($tampil == "BulanLalu") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "TahunLalu") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Perbulan") {
            $ex_params = explode("-", $tanggal);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];

            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        }
    }

    public function akumulasiTotalDebit($parameter = null)
    {
        $param           = explode("_", $parameter);
        $tampil          = $param[0];
        $tanggal         = $param[1];
        $result          = array();
        $row             = array();
        $last_month      = date('m-Y', strtotime('-1 months'));
        $last_month_year = explode("-", $last_month)[1];
        $last_month_month = explode("-", $last_month)[0];
        $last_year       = date('Y', strtotime('-1 year'));

        if ($tampil == "BulanLalu") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "TahunLalu") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Perbulan") {
            $ex_params = explode("-", $tanggal);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];

            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Kas')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        }
    }

    public function grafikDebit()
    {
        $data = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'D')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        return Response::json($data);
    }

    public function grafikCredit()
    {
        $data = Credit::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'K')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        return Response::json($data);
    }
    // End Kas

    // Start Bank
    public function debitBank(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
            # code...
            $data = $request->all();
            $tgl_explode = explode(" ", $data['tanggal']);
            $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
            $tanggal = date('Y-m-d', strtotime($tgl));
            $jumlah = Helper::formatPrice($data['jumlah']);
            $keakun = explode("_", $data['keakun'])[0];
            $flagakun = explode("_", $data['keakun'])[1];

            try {
                $keakun = Crypt::decrypt($keakun);
                $kategori = Crypt::decrypt($data['kategori']);
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
            try{
                $debit = new Cash;
                $debit->c_transaksi     = $data['ket'];
                $debit->c_jumlah        = $jumlah;
                $debit->c_jenis         = "D";
                $debit->c_tanggal       = $tanggal;
                $debit->c_kategori      = $kategori;
                $debit->c_akun          = $keakun;
                $debit->c_flag          = "Pemasukan";
                $debit->c_flagakun      = $flagakun;
                $debit->c_iduser        = Auth::user()->id;
                $debit->created_at      = Carbon::now('Asia/Jakarta');
                $debit->updated_at      = Carbon::now('Asia/Jakarta');
                $debit->save();

                Activity::log(Auth::user()->id, 'Create', 'membuat bank masuk', $tgl . ' ' .$data['ket'] . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . explode("_", $data['keakun'])[2], null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/bank/bank-masuk')->with('flash_message_success', 'Bank masuk berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/bank/bank-masuk')->with('flash_message_error', 'Bank masuk gagal disimpan!');
            }
        }

        $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', 'Bank')->get();
        $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', 'Pemasukan')->get();

        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;

        return view('member.bank.debit.index')->with(compact('akun', 'kategori', 'saldo_bank'));
    }

    public function getDebitBank()
    {
        $data = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'D')
                ->orderBy('c_tanggal', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('keakun', function ($data) {

                return "(".$data->akun->kode_akun.") ".$data->akun->nama_akun;

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/bank/masuk/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'keakun', 'aksi'])

            ->make(true);
    }

    public function deleteDebitBank($id = null)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
            Activity::log(Auth::user()->id, 'Delete', 'menghapus bank masuk', $cash->c_tanggal .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
            Cash::where(['c_id'=> $id])->delete();
            DB::commit();
            return redirect('/bank/bank-masuk')->with('flash_message_success', 'Berhasil menghapus bank masuk!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/bank/bank-masuk')->with('flash_message_error', 'Gagal menghapus bank masuk!');
        }
    }

    public function getCurrentDebitBank($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json(['status'=>"Failed"]);
        }

        $data = Cash::with(['akun'])->where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keterangan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal,
            'kategori' => $data->c_kategori,
            'keakun' => $data->akun->kode_akun
        );

        return Response::json($results);

    }

    public function updateDebitBank(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        $data = $request->all();
        $tgl_explode = explode(" ", $data['tanggal_edit']);
        $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
        $tanggal = date('Y-m-d', strtotime($tgl));
        $jumlah = Helper::formatPrice($data['jumlah_edit']);
        $keakun = explode("_", $data['keakun_edit'])[0];
        $flagakun = explode("_", $data['keakun_edit'])[1];
        $id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
            $keakun = Crypt::decrypt($keakun);
            $kategori = Crypt::decrypt($data['kategori_edit']);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

            Activity::log(Auth::user()->id, 'Update', 'memperbarui bank masuk', 'Diperbarui menjadi ' . $tgl . ' ' . $data['ket_edit'] . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.explode("_", $data['keakun_edit'])[2].'"', 'Transaksi sebelumnya ' . $cash->c_tanggal . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['ket_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal,
                'c_kategori'    => $kategori,
                'c_akun'        => $keakun,
                'c_flagakun'    => $flagakun,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            DB::commit();

            return redirect('/bank/bank-masuk')->with('flash_message_success', 'Berhasil mengubah bank masuk!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/bank/bank-masuk')->with('flash_message_error', 'Gagal mengubah bank masuk!'.$e);
        }

    }

    public function creditBank(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        if ($request->isMethod('post')) {
            # code...
            $data = $request->all();
            $jumlah = Helper::formatPrice($data['jumlah']);
            $tgl_explode = explode(" ", $data['tanggal']);
            $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
            $tanggal = date('Y-m-d', strtotime($tgl));
            $dariakun = explode("_", $data['dariakun'])[0];
            $flagakun = explode("_", $data['dariakun'])[1];

            try {
                $dariakun = Crypt::decrypt($dariakun);
                $kategori = Crypt::decrypt($data['kategori']);
            } catch (DecryptException $e) {
                return view('errors/404');
            }

            DB::beginTransaction();
            try{
                $credit = new Cash;
                $credit->c_transaksi = $data['kep'];
                $credit->c_jumlah    = $jumlah;
                $credit->c_jenis     = "K";
                $credit->c_tanggal   = $tanggal;
                $credit->c_kategori  = $kategori;
                $credit->c_akun      = $dariakun;
                $credit->c_flag      = "Pengeluaran";
                $credit->c_flagakun  = $flagakun;
                $credit->c_iduser    = Auth::user()->id;
                $credit->created_at  = Carbon::now('Asia/Jakarta');
                $credit->updated_at  = Carbon::now('Asia/Jakarta');
                $credit->save();

                Activity::log(Auth::user()->id, 'Create', 'membuat bank keluar', $tgl . ' ' .$data['kep'] . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . explode("_", $data['dariakun'])[2], null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                return redirect('/bank/bank-keluar')->with('flash_message_success', 'bank keluar berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/bank/bank-keluar')->with('flash_message_error', 'bank keluar gagal disimpan!');
            }

        }

        $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', 'bank')->get();
        $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', 'Pengeluaran')->get();

        $debit_bank  = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'D')
                        ->sum('c_jumlah');
        $credit_bank = Cash::where('c_iduser', Auth::user()->id)
                        ->where('c_flagakun', 'Bank')
                        ->where('c_jenis', 'K')
                        ->sum('c_jumlah');
        $saldo_bank  = $debit_bank - $credit_bank;
        
        return view('member.bank.credit.index')->with(compact('akun', 'kategori', 'saldo_bank'));
    }

    public function getCreditBank()
    {
        $data = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'K')
                ->orderBy('c_tanggal', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('dariakun', function ($data) {

                return "(".$data->akun->kode_akun.") ".$data->akun->nama_akun;

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/bank/keluar/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'dariakun', 'aksi'])

            ->make(true);
    }

    public function deleteCreditBank($id = null)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
            Activity::log(Auth::user()->id, 'Delete', 'menghapus bank keluar', $cash->c_tanggal . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
            Cash::where(['c_id'=> $id])->delete();
            DB::commit();
            return redirect('/bank/bank-keluar')->with('flash_message_success', 'bank keluar berhasil dihapus!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/bank/bank-keluar')->with('flash_message_error', 'bank keluar gagal dihapus!');
        }

    }

    public function getCurrentCreditBank($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return Response::json(['status'=>"Failed"]);
        }

        $data = Cash::with(['akun'])->where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keperluan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal,
            'kategori' => $data->c_kategori,
            'dariakun' => $data->akun->kode_akun
        );

        return Response::json($results);
    }

    public function updateCreditBank(Request $request)
    {
        if (Session::has('adminSession')) {
            if (Auth::user()->level != 2) {
                return redirect('/login');
            }
        }
        
        $data = $request->all();
        $jumlah = Helper::formatPrice($data['jumlah_edit']);
        $tgl_explode = explode(" ", $data['tanggal_edit']);
        $tgl = $tgl_explode[0].'-'.Helper::month($tgl_explode[1]).'-'.$tgl_explode[2];
        $tanggal = date('Y-m-d', strtotime($tgl));
        $dariakun = explode("_", $data['dariakun_edit'])[0];
        $flagakun = explode("_", $data['dariakun_edit'])[1];
        $id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
            $dariakun = Crypt::decrypt($dariakun);
            $kategori = Crypt::decrypt($data['kategori_edit']);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

            Activity::log(Auth::user()->id, 'Update', 'memperbarui bank keluar', 'Diperbarui menjadi ' . $tgl . ' ' . $data['kep_edit'] . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.explode("_", $data['dariakun_edit'])[2].'"', 'Transaksi sebelumnya ' . $cash->c_tanggal . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['kep_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal,
                'c_kategori'    => $kategori,
                'c_akun'        => $dariakun,
                'c_flagakun'    => $flagakun,
                'updated_at'    => Carbon::now('Asia/Jakarta')
            ]);

            DB::commit();

            return redirect('/bank/bank-keluar')->with('flash_message_success', 'Berhasil mengubah bank keluar!');
        }catch (\Exception $e){
            DB::rollback();
            return redirect('/bank/bank-keluar')->with('flash_message_error', 'Gagal mengubah bank keluar!');
        }

    }

    public function akumulasiTotalCreditBank($parameter = null)
    {
        $param           = explode("_", $parameter);
        $tampil          = $param[0];
        $tanggal         = $param[1];
        $result          = array();
        $row             = array();
        $last_month      = date('m-Y', strtotime('-1 months'));
        $last_month_year = explode("-", $last_month)[1];
        $last_month_month = explode("-", $last_month)[0];
        $last_year       = date('Y', strtotime('-1 year'));

        if ($tampil == "BulanLalu") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "TahunLalu") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->where('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Perbulan") {
            $ex_params = explode("-", $tanggal);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];

            $total_credit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'K')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            return Response::json($result_array);
        }
    }

    public function akumulasiTotalDebitBank($parameter = null)
    {
        $param           = explode("_", $parameter);
        $tampil          = $param[0];
        $tanggal         = $param[1];
        $result          = array();
        $row             = array();
        $last_month      = date('m-Y', strtotime('-1 months'));
        $last_month_year = explode("-", $last_month)[1];
        $last_month_month = explode("-", $last_month)[0];
        $last_year       = date('Y', strtotime('-1 year'));

        if ($tampil == "BulanLalu") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_month_year)
                            ->whereMonth('c_tanggal', '=', $last_month_month)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "TahunLalu") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $last_year)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->where('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Perbulan") {
            $ex_params = explode("-", $tanggal);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];

            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tahun)
                            ->whereMonth('c_tanggal', '=', $bulan)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_debit = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->sum('c_jumlah');

            $data        = Cash::where('c_iduser', Auth::user()->id)
                            ->where('c_flagakun', 'Bank')
                            ->where('c_jenis', 'D')
                            ->whereYear('c_tanggal', '=', $tanggal)
                            ->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            return Response::json($result_array);
        }
    }

    public function grafikDebitBank()
    {
        $data = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'D')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        return Response::json($data);
    }

    public function grafikCreditBank()
    {
        $data = Credit::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'K')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        return Response::json($data);
    }
    // End Bank
}
