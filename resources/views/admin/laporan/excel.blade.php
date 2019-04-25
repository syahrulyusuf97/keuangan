<table>
    <thead>
    <tr>
        <th>Tanggal</th>
        <th>Keterangan</th>
        <th>Jumlah</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $cash)
        <tr>
            <td>{{ $cash->c_tanggal }}</td>
            <td>{{ $cash->c_transaksi }}</td>
            <td>{{ $cash->c_jumlah }}</td>
        </tr>
    @endforeach
    </tbody>
</table>