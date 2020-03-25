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
@endphp
<table>
    <thead>
    <tr>
        <th colspan="3"><center>Laporan Arus Kas/Cashflow</center></th>
    </tr>
    <tr>
        <th colspan="3"><center>Periode {{ bulan_periode(date('m-Y', strtotime($periode))) }}</center></th>
    </tr>
    <tr>
        <th>Tanggal</th>
        <th>Akun</th>
        <th>Keterangan</th>
        <th>Jumlah</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="4">Bank Masuk</td>
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
                <td>{{ $debit_bank->c_jumlah }}</td>
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
        <td colspan="3">TOTAL BANK MASUK</td>
        <td>{{ $tot_debit_bank }}</td>
    </tr>
    <tr>
        <td colspan="4">Bank Keluar</td>
    </tr>
    @if(sizeof($bank_kredit) != 0)
        @foreach($bank_kredit as $kredit_bank)
            <tr>
                <td>{{ $kredit_bank->c_tanggal }}</td>
                <td>{{ '('.$kredit_bank->kode_akun.') '.$kredit_bank->nama_akun }}</td>
                <td>{{ $kredit_bank->c_transaksi }}</td>
                <td>{{ $kredit_bank->c_jumlah }}</td>
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
        <td colspan="3">TOTAL BANK KELUAR</td>
        <td>{{ $tot_kredit_bank }}</td>
    </tr>
    <tr>
        <td colspan="4">Kas Masuk</td>
    </tr>

    @if(sizeof($kas_debit) != 0)
        @foreach($kas_debit as $debit_kas)
            <tr>
                <td>{{ $debit_kas->c_tanggal }}</td>
                <td>{{ '('.$debit_kas->kode_akun.') '.$debit_kas->nama_akun }}</td>
                <td>{{ $debit_kas->c_transaksi }}</td>
                <td>{{ $debit_kas->c_jumlah }}</td>
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
        <td colspan="3">TOTAL KAS MASUK</td>
        <td>{{ $tot_debit_kas }}</td>
    </tr>
    <tr>
        <td colspan="4">Kas Keluar</td>
    </tr>
    @if(sizeof($kas_kredit) != 0)
        @foreach($kas_kredit as $kredit_kas)
            <tr>
                <td>{{ $kredit_kas->c_tanggal }}</td>
                <td>{{ '('.$kredit_kas->kode_akun.') '.$kredit_kas->nama_akun }}</td>
                <td>{{ $kredit_kas->c_transaksi }}</td>
                <td>{{ $kredit_kas->c_jumlah }}</td>
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
        <td colspan="3">TOTAL KAS KELUAR</td>
        <td>{{ $tot_kredit_kas }}</td>
    </tr>
    <tr>
        <td colspan="3">NILAI ARUS KAS BERSIH (NET CASH INFLOW)</td>
        <td>{{ ($tot_debit_bank - $tot_kredit_bank) + ($tot_debit_kas - $tot_kredit_kas) }}</td>
    </tr>
    </tbody>
</table>