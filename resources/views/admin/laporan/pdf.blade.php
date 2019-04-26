@php
    function bulan_periode($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = bulan
        // variabel pecahkan 1 = tahun

        return $bulan[ (int)$pecahkan[0] ] . ' ' . $pecahkan[1];
    }

    function rupiah($angka){

        $hasil_rupiah = number_format($angka,0,',','.');
        return $hasil_rupiah;

    }
@endphp
<!DOCTYPE html>
<html lang="en">
    <head>
        <style>
            body * {
                font-family: Arial, Helvetica, sans-serif;
            }
            .text-center {
                text-align: center;
            }

            .text-left {
                text-align: left;
            }

            .text-right {
                text-align: right;
            }

            .text-bold {
                font-weight: bold;
            }

            .f16 {
                font-size: 16px;
            }

            .f14 {
                font-size: 14px;
            }

            .box-body{border-top-left-radius:0;border-top-right-radius:0;border-bottom-right-radius:3px;border-bottom-left-radius:3px;padding:10px}

            table, td, th {
                border: 1px solid #ddd;
                text-align: left;
                font-size: 12px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            th, td {
                padding: 5px;
            }
        </style>
    </head>
    <body>
        <h1 class="text-center text-bold f16">Laporan Arus Kas/<i>Cashflow</i></h1>
        <h1 class="text-center text-bold f14">Periode @if($param == "bulan"){{ bulan_periode(date('m-Y', strtotime($periode))) }} @elseif ($param == "tahun") {{ $periode }} @endif
            </h1>
        <div class="box-body">
            <table>
                <tr>
                    <th>Tanggal</th>
                    <th>Transaksi</th>
                    <th>Jumlah</th>
                </tr>
                <tr>
                    <td colspan="3"><strong>Arus Kas Masuk</strong></td>
                </tr>
                @php
                    $tot_debit = 0;
                    $tot_kredit = 0;
                @endphp
                @foreach($data as $cash)
                    @if($cash->c_jenis == "D")
                        <tr>
                            <td>{{ $cash->c_tanggal }}</td>
                            <td>{{ $cash->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($cash->c_jumlah) }}</td>
                        </tr>
                        @php $tot_debit += $cash->c_jumlah; @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="2" class="text-center text-bold"><i>TOTAL KAS MASUK</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_debit) }}</td>
                </tr>
                <tr>
                    <td colspan="3">Arus Kas Keluar</td>
                </tr>
                @foreach($data as $cash)
                    @if($cash->c_jenis == "K")
                        <tr>
                            <td>{{ $cash->c_tanggal }}</td>
                            <td>{{ $cash->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($cash->c_jumlah) }}</td>
                        </tr>
                        @php $tot_kredit += $cash->c_jumlah; @endphp
                    @endif
                @endforeach
                <tr>
                    <td colspan="2" class="text-center text-bold"><i>TOTAL KAS KELUAR</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_kredit) }}</td>
                </tr>
                <tr>
                    <td colspan="2" class="text-center text-bold"><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_debit - $tot_kredit) }}</td>
                </tr>
            </table>
        </div>
    </body>
</html>