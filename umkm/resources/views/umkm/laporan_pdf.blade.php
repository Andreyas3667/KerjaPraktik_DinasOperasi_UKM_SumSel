<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan UMKM</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 4px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan UMKM</h2>
    @if(!empty($transaksis) && count($transaksis) > 0)
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksis as $trx)
                    @foreach($trx->detail as $detail)
                        <tr>
                            <td>{{ $trx->tanggal_transaksi }}</td>
                            <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan) }}</td>
                            <td>{{ $detail->jumlah }}</td>
                            <td>Rp {{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @else
        <p style="margin-top:20px;">Belum ada transaksi penjualan yang terkonfirmasi.</p>
    @endif
</body>
</html>
