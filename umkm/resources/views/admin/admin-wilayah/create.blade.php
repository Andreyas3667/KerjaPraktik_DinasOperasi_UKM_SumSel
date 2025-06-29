{{-- filepath: resources/views/admin/admin-wilayah/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Tambah Admin Wilayah')

@section('content_header')
    <h1>Tambah Admin Wilayah</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin-wilayah.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Wilayah</label>
                    <select name="id_wilayah" class="form-control" required>
                        @foreach($wilayahs as $wilayah)
                            <option value="{{ $wilayah->id_wilayah }}">{{ $wilayah->nama_wilayah }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin-wilayah.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
@stop
