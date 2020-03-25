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
                    <th>Akun</th>
                    <th>Transaksi</th>
                    <th>Jumlah</th>
                </tr>
                <tr>
                    <td colspan="4"><strong>Bank Masuk</strong></td>
                </tr>
                @php
                    $tot_debit_bank     = 0;
                    $tot_kredit_bank    = 0;
                    $tot_debit_kas      = 0;
                    $tot_kredit_kas     = 0;
                @endphp

                @if(sizeof($bank_debit) != 0)
                    @foreach($bank_debit as $debit_bank)
                        <tr>
                            <td>{{ $debit_bank->c_tanggal }}</td>
                            <td>{{ '('.$debit_bank->kode_akun.') '.$debit_bank->nama_akun }}</td>
                            <td>{{ $debit_bank->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($debit_bank->c_jumlah) }}</td>
                        </tr>
                        @php
                            $tot_debit_bank += $debit_bank->c_jumlah;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Tidak ada transaksi</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-center text-bold"><i>TOTAL BANK MASUK</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_debit_bank) }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Bank Keluar</strong></td>
                </tr>
                @if(sizeof($bank_kredit) != 0)
                    @foreach($bank_kredit as $kredit_bank)
                        <tr>
                            <td>{{ $kredit_bank->c_tanggal }}</td>
                            <td>{{ '('.$kredit_bank->kode_akun.') '.$kredit_bank->nama_akun }}</td>
                            <td>{{ $kredit_bank->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($kredit_bank->c_jumlah) }}</td>
                        </tr>
                        @php
                            $tot_kredit_bank += $kredit_bank->c_jumlah;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Tidak ada transaksi</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-center text-bold"><i>TOTAL BANK KELUAR</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_kredit_bank) }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Kas Masuk</strong></td>
                </tr>

                @if(sizeof($kas_debit) != 0)
                    @foreach($kas_debit as $debit_kas)
                        <tr>
                            <td>{{ $debit_kas->c_tanggal }}</td>
                            <td>{{ '('.$debit_kas->kode_akun.') '.$debit_kas->nama_akun }}</td>
                            <td>{{ $debit_kas->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($debit_kas->c_jumlah) }}</td>
                        </tr>
                        @php
                            $tot_debit_kas += $debit_kas->c_jumlah;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Tidak ada transaksi</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-center text-bold"><i>TOTAL KAS MASUK</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_debit_kas) }}</td>
                </tr>
                <tr>
                    <td colspan="4"><strong>Kas Keluar</strong></td>
                </tr>
                @if(sizeof($kas_kredit) != 0)
                    @foreach($kas_kredit as $kredit_kas)
                        <tr>
                            <td>{{ $kredit_kas->c_tanggal }}</td>
                            <td>{{ '('.$kredit_kas->kode_akun.') '.$kredit_kas->nama_akun }}</td>
                            <td>{{ $kredit_kas->c_transaksi }}</td>
                            <td class="text-right">{{ rupiah($kredit_kas->c_jumlah) }}</td>
                        </tr>
                        @php
                            $tot_kredit_kas += $kredit_kas->c_jumlah;
                        @endphp
                    @endforeach
                @else
                    <tr>
                        <td colspan="4">Tidak ada transaksi</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-center text-bold"><i>TOTAL KAS KELUAR</i></td>
                    <td class="text-right text-bold">{{ rupiah($tot_kredit_kas) }}</td>
                </tr>
                <tr>
                    <td colspan="3" class="text-center text-bold"><i>NILAI ARUS KAS BERSIH (NET CASH INFLOW)</i></td>
                    <td class="text-right text-bold">{{ rupiah(($tot_debit_bank - $tot_kredit_bank)+($tot_debit_kas - $tot_kredit_kas)) }}</td>
                </tr>
            </table>
        </div>
    </body>
</html>