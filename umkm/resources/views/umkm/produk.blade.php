@extends('umkm.layout')

@section('title', 'Stok Barang')

@section('content')
<h2 class="mb-4"><i class="fas fa-box"></i> Stok Barang</h2>
<a href="{{ route('umkm.produk.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Produk</a>
<table class="table table-hover table-bordered shadow-sm rounded">
    <thead class="thead-dark">
        <tr>
            <th>Nama Produk</th>
            <th>Keterangan</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produks as $produk)
        <tr>
            <td>
                @if($produk->foto)
                    <img src="{{ asset('storage/'.$produk->foto) }}" width="40" class="rounded mr-2">
                @endif
                <b>{{ $produk->nama_produk }}</b>
            </td>
            <td>{{ $produk->keterangan }}</td>
            <td>{{ $produk->stok }}</td>
            <td>Rp {{ number_format($produk->harga) }}</td>
            <td>
                <a href="{{ route('umkm.produk.edit', $produk->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                <form action="{{ route('umkm.produk.destroy', $produk->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus produk ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center text-muted">Belum ada produk</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection