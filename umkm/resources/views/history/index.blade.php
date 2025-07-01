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
                <th>Status</th>
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
                    <td>
                        @if($trx->status_pembayaran == 'selesai')
                            <span class="badge badge-success">Sukses</span>
                        @elseif($trx->status_pembayaran == 'pending')
                            <span class="badge badge-warning">Pending</span>
                        @elseif($trx->status_pembayaran == 'batal')
                            <span class="badge badge-danger">Batal</span>
                        @else
                            <span class="badge badge-secondary">{{ $trx->status_pembayaran }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
