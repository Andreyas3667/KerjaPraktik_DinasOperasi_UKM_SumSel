@extends('umkm.layout')

@section('title', 'Stok Barang')

@section('content')
<h3>Stok Barang</h3>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
            <th>Harga</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produks as $produk)
            <tr>
                <td>{{ $produk->nama_produk }}</td>
                <td>{{ $produk->deskripsi }}</td>
                <td>{{ $produk->stok }}</td>
                <td>Rp {{ number_format($produk->harga) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">Belum ada produk</td>
            </tr>
        @endforelse
    </tbody>
</table>
@endsection