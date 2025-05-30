@extends('umkm.layout')
@section('title', 'Edit Produk')
@section('content')
<div class="card">
    <div class="card-header">Edit Produk</div>
    <div class="card-body">
        <form action="{{ route('umkm.produk.update', $produk->id_produk) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" value="{{ $produk->nama_produk }}" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" value="{{ $produk->stok }}" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" value="{{ $produk->harga }}" required>
            </div>
            <div class="form-group">
                <label>Gambar Produk</label><br>
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" width="80" class="mb-2"><br>
                @endif
                <input type="file" name="gambar" class="form-control-file">
            </div>
            <button class="btn btn-primary">Update</button>
            <a href="{{ route('umkm.produk') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection