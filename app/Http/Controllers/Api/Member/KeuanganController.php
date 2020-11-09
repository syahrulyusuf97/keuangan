<?php

namespace App\Http\Controllers\Api\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cash;
use App\Akun;
use App\Kategori;
use App\Http\Controllers\ActivityController as Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use DB;
use Response;
use Auth;
use Helper;

class KeuanganController extends Controller
{    
    public function getKategori(Request $request, $jenis_transaksi)
    {
        if ($request->isMethod('get')) {
            try{
                $kategori = Kategori::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_transaksi', $jenis_transaksi);
                $option = [];
                if ($kategori->count() > 0) {
                    foreach ($kategori->get() as $key => $value) {
                        $option[] = [
                            'id'            => Crypt::encrypt($value->id),
                            'nama_kategori' => $value->nama
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $option
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

    public function getAkun(Request $request, $jenis_akun)
    {
        if ($request->isMethod('get')) {
            try{
                $akun = Akun::where('iduser', Auth::user()->id)->where('enabled', 1)->where('jenis_akun', $jenis_akun);
                $option = [];
                if ($akun->count() > 0) {
                    foreach ($akun->get() as $key => $value) {
                        $option[] = [
                            'id'         => Crypt::encrypt($value->id),
                            'jenis_akun' => $value->jenis_akun,
                            'kode_akun'  => $value->kode_akun,
                            'nama_akun'  => $value->nama_akun
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $option
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

    // Start Kas
    public function saveKasDebet(Request $request)
    {
        if ($request->isMethod('post')) {
            try {
                $ke_akun   = Crypt::decrypt($request->ke_akun);
                $kategori = Crypt::decrypt($request->kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            $keterangan = $request->keterangan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));

            DB::beginTransaction();
            try{
                $debit = new Cash;
                $debit->c_transaksi     = $keterangan;
                $debit->c_jumlah        = $jumlah;
                $debit->c_jenis         = "D";
                $debit->c_tanggal       = $tanggal;
                $debit->c_kategori      = $kategori;
                $debit->c_akun          = $ke_akun;
                $debit->c_flag          = "Pemasukan";
                $debit->c_flagakun      = "Kas";
                $debit->c_iduser        = Auth::user()->id;
                $debit->created_at      = Carbon::now('Asia/Jakarta');
                $debit->updated_at      = Carbon::now('Asia/Jakarta');
                $debit->save();

                $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                Activity::log(Auth::user()->id, 'Create', 'membuat kas masuk', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keterangan . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . '('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                DB::commit();
                $response = [
                    'status'    => "success",
                    'message'   => 'Kas masuk berhasil disimpan'
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => 'Kas masuk gagal disimpan'
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

    public function getKasDebet(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $kas_debet = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'D')
                ->orderBy('c_tanggal', 'desc');

                $data = [];

                if ($kas_debet->count() > 0) {
                    foreach ($kas_debet->get() as $key => $value) {
                        $data[] = [
                            "id"      => Crypt::encrypt($value->c_id),
                            "tanggal" => $value->c_tanggal,
                            "keterangan"=> $value->c_transaksi,
                            "jumlah"  => $value->c_jumlah,
                            "ke_akun" => "(".$value->akun->kode_akun.") ".$value->akun->nama_akun
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $data
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function deleteKasDebet(Request $request, $id=null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus kas masuk', date('d-m-Y', strtotime($cash->c_tanggal)) .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
                Cash::where(['c_id'=> $id])->delete();
                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil menghapus kas masuk"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal menghapus kas masuk"
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

    public function getCurrentKasDebet(Request $request, $id=null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            try{
                $cash = Cash::with(['akun'])->where('c_id', '=', $id);

                $results = [];
                if ($cash->count() > 0) {
                    $results = array(
                        'id'            => Crypt::encrypt($cash->first()->c_id),
                        'tanggal'       => $cash->first()->c_tanggal,
                        'keterangan'    => $cash->first()->c_transaksi,
                        'jumlah'        => $cash->first()->c_jumlah,
                        'kategori'      => $cash->first()->c_kategori,
                        'ke_akun'        => $cash->first()->akun->kode_akun
                    );
                }

                $response = [
                    'status' => "success",
                    'data'   => $results
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

    public function updateKasDebet(Request $request)
    {
        if ($request->isMethod('put')) {
            try {
                $id         = Crypt::decrypt($request->id);
                $ke_akun    = Crypt::decrypt($request->ke_akun);
                $kategori   = Crypt::decrypt($request->kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $jumlah     = $request->jumlah;
            $keterangan = $request->keterangan;

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                Activity::log(Auth::user()->id, 'Update', 'memperbarui kas masuk', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keterangan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=>$id])->update([
                    'c_transaksi'   => $keterangan,
                    'c_jumlah'      => $jumlah,
                    'c_tanggal'     => $tanggal,
                    'c_kategori'    => $kategori,
                    'c_akun'        => $ke_akun,
                    'c_flagakun'    => 'Kas',
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil memperbarui kas masuk"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal memperbarui kas masuk"
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

    public function saveKasKredit(Request $request)
    {
        if ($request->isMethod('post')) {
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $kategori   = $request->kategori;
            $keperluan  = $request->keperluan;

            try {
                $dari_akun  = Crypt::decrypt($dari_akun);
                $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $credit = new Cash;
                $credit->c_transaksi = $keperluan;
                $credit->c_jumlah    = $jumlah;
                $credit->c_jenis     = "K";
                $credit->c_tanggal   = $tanggal;
                $credit->c_kategori  = $kategori;
                $credit->c_akun      = $dari_akun;
                $credit->c_flag      = "Pengeluaran";
                $credit->c_flagakun  = 'Kas';
                $credit->c_iduser    = Auth::user()->id;
                $credit->created_at  = Carbon::now('Asia/Jakarta');
                $credit->updated_at  = Carbon::now('Asia/Jakarta');
                $credit->save();

                $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                Activity::log(Auth::user()->id, 'Create', 'membuat kas keluar', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keperluan . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . '('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Kas keluar berhasil disimpan"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Kas keluar gagal disimpan"
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

    public function getKasKredit(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $cash = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Kas')
                ->where('c_jenis', 'K')
                ->orderBy('c_tanggal', 'desc');

                $data_response = [];

                if ($cash->count() > 0) {
                    foreach ($cash->get() as $key => $value) {
                        $data_response[] = [
                            'id'        => Crypt::encrypt($value->c_id),
                            'tanggal'   => $value->c_tanggal,
                            'keperluan'   => $value->c_transaksi,
                            'jumlah'    => $value->c_jumlah,
                            'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function deleteKasKredit(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus kas keluar', date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
                Cash::where(['c_id'=> $id])->delete();
                DB::commit();
                $response = [
                    'status'    => "success",
                    'message'   => "Kas keluar berhasil dihapus"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Kas keluar gagal dihapus"
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

    public function getCurrentKasKredit(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            try{
                $data = Cash::where('c_id', '=', $id);
                $results = [];

                if ($data->count() > 0) {
                    $results = array(
                        'id'            => Crypt::encrypt($data->first()->c_id),
                        'tanggal'       => $data->first()->c_tanggal,
                        'keperluan'     => $data->first()->c_transaksi,
                        'jumlah'        => $data->first()->c_jumlah,
                        'kategori'      => $data->first()->c_kategori,
                        'dari_akun'      => $data->first()->akun->kode_akun
                    );
                }

                $response = [
                    'status' => "success",
                    'data'   => $results
                ];

                return response()->json($response);
                    
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function updateKasKredit(Request $request)
    {
        if ($request->isMethod('put')) {
            $keperluan  = $request->keperluan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $kategori   = $request->kategori;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                $dari_akun  = Crypt::decrypt($dari_akun);
                $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Update', 'memperbarui kas keluar', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keperluan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=>$id])->update([
                    'c_transaksi'   => $keperluan,
                    'c_jumlah'      => $jumlah,
                    'c_tanggal'     => $tanggal,
                    'c_kategori'    => $kategori,
                    'c_akun'        => $dari_akun,
                    'c_flagakun'    => 'Kas',
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil memperbarui kas keluar"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal memperbarui kas keluar"
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
    // End Kas

    // Start Bank
    public function saveBankDebet(Request $request)
    {
        if ($request->isMethod('post')) {
            $keterangan = $request->keterangan;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $jumlah     = $request->jumlah;
            $kategori   = $request->kategori;
            $ke_akun    = $request->ke_akun;

            try {
                $ke_akun     = Crypt::decrypt($ke_akun);
                $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $debit = new Cash;
                $debit->c_transaksi     = $keterangan;
                $debit->c_jumlah        = $jumlah;
                $debit->c_jenis         = "D";
                $debit->c_tanggal       = $tanggal;
                $debit->c_kategori      = $kategori;
                $debit->c_akun          = $ke_akun;
                $debit->c_flag          = "Pemasukan";
                $debit->c_flagakun      = "Bank";
                $debit->c_iduser        = Auth::user()->id;
                $debit->created_at      = Carbon::now('Asia/Jakarta');
                $debit->updated_at      = Carbon::now('Asia/Jakarta');
                $debit->save();

                $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                Activity::log(Auth::user()->id, 'Create', 'membuat bank masuk', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keterangan . ' "' .number_format($jumlah, 0, ',', '.') . '" ke akun ' . '('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Bank masuk berhasil disimpan"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Bank masuk gagal disimpan"
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

    public function getBankDebet(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $data = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'D')
                ->orderBy('c_tanggal', 'desc');

                $data_response = [];

                if ($data->count() > 0) {
                    foreach ($data->get() as $key => $value) {
                        $data_response[] = [
                            'id'        => Crypt::encrypt($value->c_id),
                            'tanggal'   => $value->c_tanggal,
                            'keterangan'   => $value->c_transaksi,
                            'jumlah'    => $value->c_jumlah,
                            'ke_akun'   => "(".$value->akun->kode_akun.") ".$value->akun->nama_akun
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function deleteBankDebet(Request $request, $id = null)
    {
        if ($request->isMethod('delete()')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus bank masuk', date('d-m-Y', strtotime($cash->c_tanggal)) .' '. $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
                Cash::where(['c_id'=> $id])->delete();
                DB::commit();
                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil menghapus bank masuk"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal menghapus bank masuk"
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

    public function getCurrentBankDebet(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            try{
                $data = Cash::with(['akun'])->where('c_id', '=', $id);
        
                $results = [];

                if ($data->count() > 0) {
                    $results = array(
                        'id'            => Crypt::encrypt($data->first()->c_id),
                        'tanggal'       => $data->first()->c_tanggal,
                        'keterangan'    => $data->first()->c_transaksi,
                        'jumlah'        => $data->first()->c_jumlah,
                        'kategori'      => $data->first()->c_kategori,
                        'keakun'        => $data->first()->akun->kode_akun
                    );
                }

                $response = [
                    'status' => "success",
                    'data'   => $results
                ];

                return response()->json($response);
                    
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function updateBankDebet(Request $request)
    {
        if ($request->isMethod('put')) {
            $keterangan = $request->keterangan;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $jumlah     = $request->jumlah;
            $ke_akun    = $request->ke_akun;
            $kategori   = $request->kategori;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                $ke_akun    = Crypt::decrypt($ke_akun);
                $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                $nama_keakun = Akun::where('iduser', Auth::user()->id)->where('id', $ke_akun)->first();

                Activity::log(Auth::user()->id, 'Update', 'memperbarui bank masuk', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keterangan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_keakun->kode_akun.') '.$nama_keakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=>$id])->update([
                    'c_transaksi'   => $keterangan,
                    'c_jumlah'      => $jumlah,
                    'c_tanggal'     => $tanggal,
                    'c_kategori'    => $kategori,
                    'c_akun'        => $ke_akun,
                    'c_flagakun'    => 'Bank',
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil memperbarui bank masuk"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "success",
                    'message'   => "Gagal memperbarui bank masuk"
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

    public function saveBankKredit(Request $request)
    {
        if ($request->isMethod('post')) {
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $kategori   = $request->kategori;
            $keperluan  = $request->keperluan;

            try {
                $dari_akun = Crypt::decrypt($dari_akun);
                $kategori = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $credit = new Cash;
                $credit->c_transaksi = $keperluan;
                $credit->c_jumlah    = $jumlah;
                $credit->c_jenis     = "K";
                $credit->c_tanggal   = $tanggal;
                $credit->c_kategori  = $kategori;
                $credit->c_akun      = $dari_akun;
                $credit->c_flag      = "Pengeluaran";
                $credit->c_flagakun  = 'Bank';
                $credit->c_iduser    = Auth::user()->id;
                $credit->created_at  = Carbon::now('Asia/Jakarta');
                $credit->updated_at  = Carbon::now('Asia/Jakarta');
                $credit->save();

                $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                Activity::log(Auth::user()->id, 'Create', 'membuat bank keluar', date('d-m-Y', strtotime($request->tanggal)) . ' ' .$keperluan . ' "' .number_format($jumlah, 0, ',', '.') . '" dari akun ' . '('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun, null, Carbon::now('Asia/Jakarta'));

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Bank keluar berhasil disimpan"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Bank keluar gagal disimpan"
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

    public function getBankKredit(Request $request)
    {
        if ($request->isMethod('get')) {
            try{
                $cash = Cash::with(['akun'])
                ->where('c_iduser', Auth::user()->id)
                ->where('c_flagakun', 'Bank')
                ->where('c_jenis', 'K')
                ->orderBy('c_tanggal', 'desc');

                $data_response = [];

                if ($cash->count() > 0) {
                    foreach ($cash->get() as $key => $value) {
                        $data_response[] = [
                            'id'        => Crypt::encrypt($value->c_id),
                            'tanggal'   => $value->c_tanggal,
                            'keperluan' => $value->c_transaksi,
                            'jumlah'    => $value->c_jumlah,
                            'dari_akun' => '('.$value->akun->kode_akun.') '.$value->akun->nama_akun
                        ];
                    }
                }

                $response = [
                    'status' => "success",
                    'data'   => $data_response
                ];
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function deleteBankKredit(Request $request, $id = null)
    {
        if ($request->isMethod('delete')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();
                Activity::log(Auth::user()->id, 'Delete', 'menghapus bank keluar', date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' .$cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', null, Carbon::now('Asia/Jakarta'));
                Cash::where(['c_id'=> $id])->delete();
                DB::commit();
                $response = [
                    'status'    => "success",
                    'message'   => "Bank keluar berhasil dihapus"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Bank keluar gagal dihapus"
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

    public function getCurrentBankKredit(Request $request, $id = null)
    {
        if ($request->isMethod('get')) {
            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            try{
                $data = Cash::with(['akun'])->where('c_id', '=', $id);
                $results = [];

                if ($data->count() > 0) {
                    $results = array(
                        'id' => Crypt::encrypt($data->first()->c_id),
                        'tanggal' => $data->first()->c_tanggal,
                        'keperluan' => $data->first()->c_transaksi,
                        'jumlah' => $data->first()->c_jumlah,
                        'kategori' => $data->first()->c_kategori,
                        'dariakun' => $data->first()->akun->kode_akun
                    );
                }

                $response = [
                    'status' => "success",
                    'data'   => $results
                ];

                return response()->json($response);
                    
            }catch(\Exception $e){
                $response = [
                    'status'    => "failed",
                    'message'   => 'A network error occurred. Please try again!'
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

    public function updateBankKredit(Request $request)
    {
        if ($request->isMethod('put')) {
            $keperluan  = $request->keperluan;
            $jumlah     = $request->jumlah;
            $tanggal    = date('Y-m-d', strtotime($request->tanggal));
            $dari_akun  = $request->dari_akun;
            $kategori   = $request->kategori;
            $id         = $request->id;

            try {
                $id         = Crypt::decrypt($id);
                $dari_akun  = Crypt::decrypt($dari_akun);
                $kategori   = Crypt::decrypt($kategori);
            } catch (DecryptException $e) {
                $response = [
                    'status'    => "failed",
                    'message'   => "A network error occurred. Please try again!"
                ];
                return response()->json($response);
            }

            DB::beginTransaction();
            try{
                $nama_dariakun = Akun::where('iduser', Auth::user()->id)->where('id', $dari_akun)->first();

                $cash = Cash::with(['akun'])->where(['c_id'=> $id])->first();

                Activity::log(Auth::user()->id, 'Update', 'memperbarui bank keluar', 'Diperbarui menjadi ' . date('d-m-Y', strtotime($request->tanggal)) . ' ' . $keperluan . ' "' .number_format($jumlah, 0, ',', '.') .' akun "'.'('.$nama_dariakun->kode_akun.') '.$nama_dariakun->nama_akun.'"', 'Transaksi sebelumnya ' . date('d-m-Y', strtotime($cash->c_tanggal)) . ' ' . $cash->c_transaksi . ' "' .number_format($cash->c_jumlah, 0, ',', '.') . '" akun "('.$cash->akun->kode_akun.') '.$cash->akun->nama_akun.'"', Carbon::now('Asia/Jakarta'));

                Cash::where(['c_id'=>$id])->update([
                    'c_transaksi'   => $keperluan,
                    'c_jumlah'      => $jumlah,
                    'c_tanggal'     => $tanggal,
                    'c_kategori'    => $kategori,
                    'c_akun'        => $dari_akun,
                    'c_flagakun'    => 'Bank',
                    'updated_at'    => Carbon::now('Asia/Jakarta')
                ]);

                DB::commit();

                $response = [
                    'status'    => "success",
                    'message'   => "Berhasil memperbarui bank keluar"
                ];
            }catch (\Exception $e){
                DB::rollback();
                $response = [
                    'status'    => "failed",
                    'message'   => "Gagal memperbarui bank keluar"
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
    // End Bank
}
