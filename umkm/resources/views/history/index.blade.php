@extends('layout.main')
@section('title', 'History Pemesanan')
@section('content')
<div class="container my-5">
    <h2>Riwayat Pemesanan</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama UMKM</th>
                <th>Nama Produk</th>
                <th>Harga Satuan</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksis as $trx)
                @foreach($trx->detail as $detail)
                <tr>
                    <td>{{ $trx->tanggal_transaksi }}</td>
                    <td>{{ $trx->umkm->nama_usaha ?? '-' }}</td>
                    <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                    <td>Rp {{ number_format($detail->harga_satuan) }}</td>
                    <td>{{ $detail->jumlah }}</td>
                    <td>Rp {{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada riwayat pemesanan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
