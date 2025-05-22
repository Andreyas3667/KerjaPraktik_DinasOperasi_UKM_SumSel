@extends('umkm.layout')

@section('title', 'Profile UMKM')

@section('content')
<h3>Profile UMKM</h3>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<form action="{{ route('umkm.profile.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <label>Foto Profil</label><br>
        @if($umkm && $umkm->foto)
            <img src="{{ asset('storage/'.$umkm->foto) }}" width="120" class="mb-2"><br>
        @endif
        <input type="file" name="foto" class="form-control-file">
    </div>
    <div class="form-group">
        <label>Nama UMKM</label>
        <input type="text" name="nama_usaha" class="form-control" value="{{ $umkm->nama_usaha ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Alamat</label>
        <input type="text" name="alamat" class="form-control" value="{{ $umkm->alamat ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Kontak</label>
        <input type="text" name="kontak" class="form-control" value="{{ $umkm->kontak ?? '' }}" required>
    </div>
    <div class="form-group">
        <label>Keterangan</label>
        <textarea name="deskripsi" class="form-control">{{ $umkm->deskripsi ?? '' }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Simpan</button>
</form>
@endsection