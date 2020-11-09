<?php
namespace App\Exports;
use App\Cash;
use DB;
use Auth;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CashflowExcel implements FromView, ShouldAutoSize
{
    use Exportable;

    public function __construct(string $bulan, string $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Cash::all();
    }

    public function query()
    {
        if ($this->bulan != "" || $this->bulan != null) {
            $month = explode(" ", $this->bulan);

            return Cash::query()
                ->whereMonth('c_tanggal', date('m', strtotime($month[0])))
                ->whereYear('c_tanggal', $month[1])
                ->select(DB::raw("DATE_FORMAT(c_tanggal, '%d-%m-%Y') as c_tanggal"), 'c_transaksi', 'c_jumlah', 'c_jenis');
        } else if ($this->tahun != "" || $this->tahun != null) {
            $year = $this->tahun;

            return Cash::query()
                ->whereYear('c_tanggal', $year)
                ->select(DB::raw("DATE_FORMAT(c_tanggal, '%d-%m-%Y') as c_tanggal"), 'c_transaksi', 'c_jumlah', 'c_jenis');
        }

//        return Cash::query()->where('name', 'like', '%'. $this->name);
    }

    public function view(): View
    {
        if ($this->tahun == "null") {
            $month = explode(" ", $this->bulan);

            $bank_debit     = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'D')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($month[0])))
                                ->whereYear('cash.c_tanggal', $month[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $bank_kredit    = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'K')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($month[0])))
                                ->whereYear('cash.c_tanggal', $month[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_debit      = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'D')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($month[0])))
                                ->whereYear('cash.c_tanggal', $month[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_kredit     = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'K')
                                ->whereMonth('cash.c_tanggal', date('m', strtotime($month[0])))
                                ->whereYear('cash.c_tanggal', $month[1])
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $periode = $this->bulan;
        } else if ($this->bulan == "null") {
            $year = $this->tahun;

            $bank_debit     = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'D')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $bank_kredit    = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Bank')
                                ->where('cash.c_jenis', 'K')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_debit      = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'D')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $kas_kredit     = DB::table('cash')
                                ->join('ms_akun', 'ms_akun.id', '=', 'cash.c_akun')
                                ->where('cash.c_iduser', Auth::user()->id)
                                ->where('cash.c_flagakun', 'Kas')
                                ->where('cash.c_jenis', 'K')
                                ->whereYear('c_tanggal', $year)
                                ->select(DB::raw("DATE_FORMAT(cash.c_tanggal, '%d-%m-%Y') as c_tanggal"), 'ms_akun.kode_akun', 'ms_akun.nama_akun',
                                    'cash.c_transaksi', 'cash.c_jumlah', 'cash.c_jenis')
                                ->get();

            $periode = $this->tahun;
        }
        
        return view('member.laporan.excel', [
            'bank_debit'    => $bank_debit,
            'bank_kredit'   => $bank_kredit,
            'kas_debit'     => $kas_debit,
            'kas_kredit'    => $kas_kredit,
            'periode'       => $periode
        ]);
    }
}