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
        <th>Keterangan</th>
        <th>Jumlah</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td colspan="3">Arus Kas Masuk</td>
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
            <td>{{ $cash->c_jumlah }}</td>
        </tr>
            @php $tot_debit += $cash->c_jumlah; @endphp
        @endif
    @endforeach
    <tr>
        <td colspan="2">TOTAL KAS MASUK</td>
        <td>{{ $tot_debit }}</td>
    </tr>
    <tr>
        <td colspan="3">Arus Kas Keluar</td>
    </tr>
    @foreach($data as $cash)
        @if($cash->c_jenis == "K")
            <tr>
                <td>{{ $cash->c_tanggal }}</td>
                <td>{{ $cash->c_transaksi }}</td>
                <td>{{ $cash->c_jumlah }}</td>
            </tr>
            @php $tot_kredit += $cash->c_jumlah; @endphp
        @endif
    @endforeach
    <tr>
        <td colspan="2">TOTAL KAS KELUAR</td>
        <td>{{ $tot_kredit }}</td>
    </tr>
    <tr>
        <td colspan="2">NILAI ARUS KAS BERSIH (NET CASH INFLOW)</td>
        <td>{{ $tot_debit - $tot_kredit }}</td>
    </tr>
    </tbody>
</table>