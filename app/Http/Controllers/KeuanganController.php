<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DataTables;
use DB;
use Response;

class KeuanganController extends Controller
{

	function formatPrice($data)
    {
        $explode_rp =  implode("", explode("Rp", $data));
        return implode("", explode(".", $explode_rp));
    }

    public function debit(Request $request)
    {
    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
    		// print_r($data); die;
            $tanggal = date('Y-m-d', strtotime($data['tanggal']));
            // echo($tanggal); die;
    		$jumlah = $this->formatPrice($data['jumlah']);

    		DB::beginTransaction();
    		try{
                $debit = new Cash;
                $debit->c_transaksi 	= $data['ket'];
                $debit->c_jumlah		= $jumlah;
                $debit->c_jenis         = "D";
                $debit->c_tanggal       = $tanggal;
                $debit->save();

                DB::commit();
                return redirect('/kas/masuk')->with('flash_message_success', 'Kas masuk berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/kas/masuk')->with('flash_message_error', 'Kas masuk gagal disimpan!');
            }
    	}

        $total_debit  = Cash::where('c_jenis', 'D')->sum('c_jumlah');
    	return view('admin.debit.index')->with(compact('total_debit'));
    }

    public function getDebit()
    {
        $data = Cash::where('c_jenis', 'D')->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/kas/masuk/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'aksi'])

            ->make(true);
    }

    public function deleteDebit($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
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
            return view('errors/404');
        }

        $data = Cash::where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keterangan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal
        );

        echo json_encode($results);

    }

    public function updateDebit(Request $request)
    {
    	$data = $request->all();
    	// print_r($data); die;
        $tanggal = date('Y-m-d', strtotime($data['tanggal_edit']));
    	$jumlah = $this->formatPrice($data['jumlah_edit']);

    	$id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

    	DB::beginTransaction();
    	try{
            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['ket_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal
            ]);
            DB::commit();
            return redirect('/kas/masuk')->with('flash_message_success', 'Berhasil mengubah kas masuk!');
        }catch (\Exception $e){
    	    DB::rollback();
            return redirect('/kas/masuk')->with('flash_message_error', 'Gagal mengubah kas masuk!');
        }

    }

    public function credit(Request $request)
    {
    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
    		// print_r($data); die;
    		$jumlah = $this->formatPrice($data['jumlah']);
            $tanggal = date('Y-m-d', strtotime($data['tanggal']));

            DB::beginTransaction();
            try{
                $credit = new Cash;
                $credit->c_transaksi 	= $data['kep'];
                $credit->c_jumlah		= $jumlah;
                $credit->c_jenis		= "K";
                $credit->c_tanggal      = $tanggal;
                $credit->save();

                DB::commit();
                return redirect('/kas/keluar')->with('flash_message_success', 'Kas keluar berhasil disimpan!');
            }catch (\Exception $e){
                DB::rollback();
                return redirect('/kas/keluar')->with('flash_message_error', 'Kas keluar gagal disimpan!');
            }

    	}
    	$data_credit = Cash::where('c_jenis', 'K')->orderBy('created_at', 'desc')->get();
        $total_credit = Cash::where('c_jenis', 'K')->sum('c_jumlah');
    	return view('admin.credit.index')->with(compact('data_credit', 'total_credit'));
    }

    public function getCredit()
    {
        $data = Cash::where('c_jenis', 'K')->orderBy('created_at', 'desc');

        return DataTables::of($data)

            ->addColumn('tanggal', function ($data) {

                return date('d-m-Y', strtotime($data->c_tanggal));

            })

            ->addColumn('jumlah', function ($data) {

                return '<p class="text-right">'.number_format($data->c_jumlah, '2', ',', '.').'</p>';

            })

            ->addColumn('aksi', function ($data) {

                return '<p class="text-center"><a href="'.url('/kas/keluar/hapus/'.Crypt::encrypt($data->c_id)).'" onclick="return confirm(\''. 'Apakah anda yakin akan menghapus data ini?'.'\nJika Anda menghapus data ini, berarti Anda telah kehilangan satu kenangan...:(' .'\')" class="text-danger" style="padding: 4px; font-size: 14px;"><i class="fa fa-trash"></i></a>&nbsp;<a href="javascript:void(0)" onclick="edit(\''. Crypt::encrypt($data->c_id) . '\'  )" class="text-blue" style="padding: 4px; font-size: 14px;"><i class="fa fa-pencil"></i></a></p>';

            })

            ->rawColumns(['tanggal', 'jumlah', 'aksi'])

            ->make(true);
    }

    public function deleteCredit($id = null)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
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
            return view('errors/404');
        }

        $data = Cash::where('c_id', '=', $id)->first();
        $results = array(
            'id' => Crypt::encrypt($data->c_id),
            'keperluan' => $data->c_transaksi,
            'jumlah' => $data->c_jumlah,
            'tanggal' => $data->c_tanggal
        );
        echo json_encode($results);
    }

    public function updateCredit(Request $request)
    {
    	$data = $request->all();
    	// print_r($data); die;
    	$jumlah = $this->formatPrice($data['jumlah_edit']);
        $tanggal = date('Y-m-d', strtotime($data['tanggal_edit']));

        $id = $data['id'];

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return view('errors/404');
        }

        DB::beginTransaction();
        try{
            Cash::where(['c_id'=>$id])->update([
                'c_transaksi'   => $data['kep_edit'],
                'c_jumlah'      => $jumlah,
                'c_tanggal'     => $tanggal
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
        $param      = explode("_", $parameter);
        $tampil     = $param[0];
        $tanggal    = $param[1];
        $result     = array();
        $row        = array();

        if ($tampil == "Keseluruhan") {
            // echo json_encode($tampil);
            $total_credit   = Cash::where('c_jenis', 'K')->sum('c_jumlah');
            $data           = Cash::where('c_jenis', 'K')->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_credit = Cash::where('c_jenis', 'K')->where(['c_tanggal'=>$tanggal])->sum('c_jumlah');
            $data         = Cash::where('c_jenis', 'K')->where('c_tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Perbulan") {
            $params = date('m-Y', strtotime($tanggal));
            $ex_params = explode("-", $params);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];
            $total_credit = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', $tahun)->whereMonth('c_tanggal', '=', $bulan)->sum('c_jumlah');
            $data         = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', $tahun)->whereMonth('c_tanggal', '=', $bulan)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_credit = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', $tanggal)->sum('c_jumlah');
            $data         = Cash::where('c_jenis', 'K')->whereYear('c_tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keperluan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        }
    }

    public function akumulasiTotalDebit($parameter = null)
    {
        $param      = explode("_", $parameter);
        $tampil     = $param[0];
        $tanggal    = $param[1];
        $result     = array();
        $row        = array();

        if ($tampil == "Keseluruhan") {
            // echo json_encode($tampil);
            $total_debit = Cash::where('c_jenis', 'D')->sum('c_jumlah');
            $data           = Cash::where('c_jenis', 'D')->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_debit = Cash::where('c_jenis', 'D')->where(['c_tanggal'=>$tanggal])->sum('c_jumlah');
            $data        = Cash::where('c_jenis', 'D')->where('c_tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Perbulan") {
            $params = date('m-Y', strtotime($tanggal));
            $ex_params = explode("-", $params);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];
            $total_debit = Cash::where('c_jenis', 'D')->whereYear('c_tanggal', '=', $tahun)->whereMonth('c_tanggal', '=', $bulan)->sum('c_jumlah');
            $data        = Cash::where('c_jenis', 'D')->whereYear('c_tanggal', '=', $tahun)->whereMonth('c_tanggal', '=', $bulan)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_debit = Cash::where('c_jenis', 'D')->whereYear('c_tanggal', '=', $tanggal)->sum('c_jumlah');
            $data        = Cash::where('c_jenis', 'D')->whereYear('c_tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->c_tanggal,
                    'keterangan' => $value->c_transaksi,
                    'jumlah' => $value->c_jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_debit, 'result'=>$result);

            echo json_encode($result_array);
        }
    }

    public function grafikDebit()
    {
        $data = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_jenis', 'D')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        echo json_encode($data);
    }

    public function grafikCredit()
    {
        $data = Credit::select(\DB::raw('SUM(c_jumlah) as jumlah'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"))
                ->where('c_jenis', 'K')
                ->whereYear('c_tanggal', Carbon::now('Asia/Jakarta')->format('Y'))
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get();
        echo json_encode($data);
    }

    public function grafik()
    {
        $data_debit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_debit'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
                ->where('c_jenis', 'D')
                ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
                ->groupBy(['month', 'c_jenis'])
                ->orderBy('month', 'desc')
                ->get();

        $data_credit = Cash::select(\DB::raw('SUM(c_jumlah) as jumlah_kredit'),
                \DB::raw("DATE_FORMAT(c_tanggal, '%M %Y') as month"), 'c_jenis')
                ->where('c_jenis', 'K')
                ->whereYear('c_tanggal', date('Y', strtotime("-1 year")))
                ->groupBy(['month', 'c_jenis'])
                ->orderBy('month', 'desc')
                ->get();

        foreach ($data_debit as $key => $debit) {
            $row[] = array('month' => $debit->month, 'debit' => $debit->jumlah_debit, 'kredit' => $data_credit[$key]->jumlah_kredit);
        }

        echo json_encode($row);
    }
}
