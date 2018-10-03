<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Deposito;
use App\Credit;

class KeuanganController extends Controller
{

	function formatPrice($data)
    {
        $explode_rp =  implode("", explode("Rp", $data));
        return implode("", explode(".", $explode_rp));
    }

    public function deposito(Request $request)
    {
    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
    		// print_r($data); die;
            $tanggal = date('Y-m-d', strtotime($data['tanggal']));
            // echo($tanggal); die;
    		$jumlah = $this->formatPrice($data['jumlah']);

    		$deposito = new Deposito;
    		$deposito->keterangan 	= $data['ket'];
    		$deposito->jumlah		= $jumlah;
            $deposito->tanggal      = $tanggal;
    		$deposito->save();

    		return redirect('/deposito')->with('flash_message_success', 'Deposito has been added successfully!');
    	}

    	$data_deposito = Deposito::orderBy('tanggal', 'desc')->get();
        $total_deposito  = Deposito::sum('jumlah');
    	return view('admin.deposito.index')->with(compact('data_deposito', 'total_deposito'));
    }

    public function delete_deposito($id = null)
    {
    	Deposito::where(['deposito_id'=> $id])->delete();
    	return redirect('/deposito')->with('flash_message_success', 'Deposito has been deleted successfully!');
    }

    public function get_current_deposito($id = null)
    {
        $data = Deposito::where('deposito_id', '=', $id)->first();
        echo json_encode($data);
    }

    public function update_deposito(Request $request)
    {
    	$data = $request->all();
    	// print_r($data); die;
        $tanggal = date('Y-m-d', strtotime($data['tanggal_edit']));
    	$jumlah = $this->formatPrice($data['jumlah_edit']);
    	Deposito::where(['deposito_id'=>$data['id']])->update([
    		'keterangan'=>$data['ket_edit'],
    		'jumlah'=>$jumlah,
            'tanggal'=>$tanggal
    	]);
    	return redirect('/deposito')->with('flash_message_success', 'Deposito has been updated successfully!');
    }

    public function credit(Request $request)
    {
    	if ($request->isMethod('post')) {
    		# code...
    		$data = $request->all();
    		// print_r($data); die;
    		$jumlah = $this->formatPrice($data['jumlah']);
            $tanggal = date('Y-m-d', strtotime($data['tanggal']));

    		$credit = new Credit;
    		$credit->keperluan 	= $data['kep'];
    		$credit->jumlah		= $jumlah;
            $credit->tanggal    = $tanggal;
    		$credit->save();

    		return redirect('/credit')->with('flash_message_success', 'Credit has been added successfully!');
    	}
    	$data_credit = Credit::orderBy('tanggal', 'desc')->get();
        $total_credit = Credit::sum('jumlah');
    	return view('admin.credit.index')->with(compact('data_credit', 'total_credit'));
    }

    public function delete_credit($id = null)
    {
    	Credit::where(['credit_id'=> $id])->delete();
    	return redirect('/credit')->with('flash_message_success', 'Credit has been deleted successfully!');
    }

    public function get_current_credit($id = null)
    {
        $data = Credit::where('credit_id', '=', $id)->first();
        echo json_encode($data);
    }

    public function update_credit(Request $request)
    {
    	$data = $request->all();
    	// print_r($data); die;
    	$jumlah = $this->formatPrice($data['jumlah_edit']);
        $tanggal = date('Y-m-d', strtotime($data['tanggal_edit']));
    	Credit::where(['credit_id'=>$data['id']])->update([
    		'keperluan'=>$data['kep_edit'],
    		'jumlah'=>$jumlah,
            'tanggal'=>$tanggal
    	]);
    	return redirect('/credit')->with('flash_message_success', 'Credit has been updated successfully!');
    }

    public function akumulasi_total_credit($parameter = null)
    {
        $param      = explode("_", $parameter);
        $tampil     = $param[0];
        $tanggal    = $param[1];
        $result     = array();
        $row        = array();

        if ($tampil == "Keseluruhan") {
            // echo json_encode($tampil);
            $total_credit   = Credit::sum('jumlah');
            $data           = Credit::get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keperluan' => $value->keperluan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_credit = Credit::where(['tanggal'=>$tanggal])->sum('jumlah');
            $data         = Credit::where('tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keperluan' => $value->keperluan,
                    'jumlah' => $value->jumlah
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
            $total_credit = Credit::whereYear('tanggal', '=', $tahun)->whereMonth('tanggal', '=', $bulan)->sum('jumlah');
            $data         = Credit::whereYear('tanggal', '=', $tahun)->whereMonth('tanggal', '=', $bulan)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keperluan' => $value->keperluan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_credit = Credit::whereYear('tanggal', '=', $tanggal)->sum('jumlah');
            $data         = Credit::whereYear('tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keperluan' => $value->keperluan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_credit, 'result'=>$result);

            echo json_encode($result_array);
        }
    }

    public function akumulasi_total_deposito($parameter = null)
    {
        $param      = explode("_", $parameter);
        $tampil     = $param[0];
        $tanggal    = $param[1];
        $result     = array();
        $row        = array();

        if ($tampil == "Keseluruhan") {
            // echo json_encode($tampil);
            $total_deposito = Deposito::sum('jumlah');
            $data           = Deposito::get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keterangan' => $value->keterangan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_deposito, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertanggal") {
            $total_deposito = Deposito::where(['tanggal'=>$tanggal])->sum('jumlah');
            $data           = Deposito::where('tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keterangan' => $value->keterangan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_deposito, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Perbulan") {
            $params = date('m-Y', strtotime($tanggal));
            $ex_params = explode("-", $params);
            $bulan = $ex_params[0];
            $tahun = $ex_params[1];
            $total_deposito = Deposito::whereYear('tanggal', '=', $tahun)->whereMonth('tanggal', '=', $bulan)->sum('jumlah');
            $data         = Deposito::whereYear('tanggal', '=', $tahun)->whereMonth('tanggal', '=', $bulan)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keterangan' => $value->keterangan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_deposito, 'result'=>$result);

            echo json_encode($result_array);
        } elseif ($tampil == "Pertahun") {
            $total_deposito = Deposito::whereYear('tanggal', '=', $tanggal)->sum('jumlah');
            $data         = Deposito::whereYear('tanggal', '=', $tanggal)->get();

            foreach ($data as $value) {
                $row = array(
                    'tanggal' => $value->tanggal,
                    'keterangan' => $value->keterangan,
                    'jumlah' => $value->jumlah
                );

                $result[] = $row;
            }

            $result_array = array('total'=>$total_deposito, 'result'=>$result);

            echo json_encode($result_array);
        }
    }

    public function riwayat($parameter = null)
    {
        $result_deposito = array();
        $result_credit = array();

        $tanggal        = date('Y-m-d', strtotime($parameter));
        $data_deposito  = Deposito::where('tanggal', '=', $tanggal)->get();
        $data_credit    = Credit::where('tanggal', '=', $tanggal)->get();

        foreach ($data_deposito as $value) {
            $row = array(
                'tanggal' => $value->tanggal,
                'keterangan' => $value->keterangan,
                'jumlah' => $value->jumlah
            );

            $result_deposito[] = $row;
        }

        foreach ($data_credit as $value) {
            $row = array(
                'tanggal' => $value->tanggal,
                'keperluan' => $value->keperluan,
                'jumlah' => $value->jumlah
            );

            $result_credit[] = $row;
        }

        $result_array = array('result_credit'=>$result_credit, 'result_deposito'=>$result_deposito);

        echo json_encode($result_array);
    }
}
