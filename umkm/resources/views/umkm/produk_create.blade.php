@extends('umkm.layout')
@section('title', 'Tambah Produk')
@section('content')
<div class="card">
    <div class="card-header">Tambah Produk</div>
    <div class="card-body">
        <form action="{{ route('umkm.produk.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Nama Produk</label>
                <input type="text" name="nama_produk" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $produk->deskripsi ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label>Stok</label>
                <input type="number" name="stok" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Gambar Produk</label>
                <input type="file" name="gambar" class="form-control-file">
            </div>
            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('umkm.produk') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection