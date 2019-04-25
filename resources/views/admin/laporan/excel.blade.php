<table>
    <thead>
    <tr>
        <th colspan="3" style="text-align: center">Laporan Arus Kas/Cashflow</th>
    </tr>
    <tr>
        <th colspan="3" style="text-align: center">Periode {{ $periode }}</th>
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