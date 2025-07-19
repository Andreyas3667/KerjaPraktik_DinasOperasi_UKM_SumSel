<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 4px; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    @if($umkmNama)
        <p><strong>UMKM:</strong> {{ $umkmNama }}</p>
    @endif
    @if($wilayahNama)
        <p><strong>Wilayah:</strong> {{ $wilayahNama }}</p>
    @endif
    @if($tanggalMin && $tanggalMax)
        <p>
            <strong>Periode:</strong>
            {{ \Carbon\Carbon::parse($tanggalMin)->format('d-m-Y') }}
            s/d
            {{ \Carbon\Carbon::parse($tanggalMax)->format('d-m-Y') }}
            <br>
            <strong>Bulan:</strong>
            {{ $bulanMin }} - {{ $bulanMax }}
        </p>
    @endif
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>UMKM</th>
                <th>Wilayah</th>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
                <th>Pembeli</th>
                <th>Alamat Pembeli</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @forelse($transaksis as $trx)
                @foreach($trx->detail as $detail)
                    <tr>
                        <td>{{ $no }}</td>
                        <td>{{ $trx->umkm->nama_usaha ?? '-' }}</td>
                        <td>{{ $trx->umkm->wilayah->nama_wilayah ?? '-' }}</td>
                        <td>{{ $trx->tanggal_transaksi }}</td>
                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                        <td>{{ number_format($detail->harga_satuan) }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>{{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
                        <td>{{ $trx->user->nama ?? '-' }}</td>
                        <td>{{ $trx->user->alamat ?? '-' }}</td>
                        <td>
                            @if($trx->status_pembayaran == 'selesai')
                                Berhasil
                            @elseif($trx->status_pembayaran == 'batal')
                                Batal
                            @else
                                Proses
                            @endif
                        </td>
                    </tr>
                    @php $no++; @endphp
                @endforeach
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data penjualan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
