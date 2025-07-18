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
    <h2>
        Laporan Penjualan
        @if($wilayah)
            @if($transaksis->count() > 0 && $transaksis->first()->umkm && $transaksis->first()->umkm->wilayah)
                Wilayah: {{ $transaksis->first()->umkm->wilayah->nama_wilayah }}
            @else
                Wilayah: (Tidak ada data)
            @endif
        @else
            Semua Wilayah
        @endif
    </h2>
    @if($tanggal_dari || $tanggal_sampai)
        <p>
            <strong>Periode:</strong>
            @if($tanggal_dari && $tanggal_sampai)
                {{ \Carbon\Carbon::parse($tanggal_dari)->format('d-m-Y') }}
                s/d
                {{ \Carbon\Carbon::parse($tanggal_sampai)->format('d-m-Y') }}
                <br>
                <strong>Bulan:</strong>
                {{ \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('F Y') }}
                -
                {{ \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('F Y') }}
            @elseif($tanggal_dari)
                Mulai {{ \Carbon\Carbon::parse($tanggal_dari)->format('d-m-Y') }}
                <br>
                <strong>Bulan:</strong>
                {{ \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('F Y') }}
            @elseif($tanggal_sampai)
                Sampai {{ \Carbon\Carbon::parse($tanggal_sampai)->format('d-m-Y') }}
                <br>
                <strong>Bulan:</strong>
                {{ \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('F Y') }}
            @endif
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
            @forelse($transaksis as $trx)
                @foreach($trx->detail as $i => $detail)
                    <tr>
                        @if($i == 0)
                            <td rowspan="{{ $trx->detail->count() }}">{{ $loop->parent->iteration }}</td>
                            <td rowspan="{{ $trx->detail->count() }}">{{ $trx->umkm->nama_usaha ?? '-' }}</td>
                            <td rowspan="{{ $trx->detail->count() }}">{{ $trx->umkm->wilayah->nama_wilayah ?? '-' }}</td>
                            <td rowspan="{{ $trx->detail->count() }}">{{ $trx->tanggal_transaksi }}</td>
                        @endif
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
