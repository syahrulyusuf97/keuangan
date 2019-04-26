<?php
namespace App\Exports;
use App\Cash;
use DB;
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

            $cash =  Cash::query()
                ->whereMonth('c_tanggal', date('m', strtotime($month[0])))
                ->whereYear('c_tanggal', $month[1])
                ->select(DB::raw("DATE_FORMAT(c_tanggal, '%d-%m-%Y') as c_tanggal"),
                    'c_transaksi', 'c_jumlah', 'c_jenis')
                ->get();
            $periode = $this->bulan;
        } else if ($this->bulan == "null") {
            $year = $this->tahun;

            $cash =  Cash::query()
                ->whereYear('c_tanggal', $year)
                ->select(DB::raw("DATE_FORMAT(c_tanggal, '%d-%m-%Y') as c_tanggal"),
                    'c_transaksi', 'c_jumlah', 'c_jenis')
                ->get();
            $periode = $this->tahun;
        }
        return view('admin.laporan.excel', [
            'data' => $cash,
            'periode' => $periode
        ]);
    }
}