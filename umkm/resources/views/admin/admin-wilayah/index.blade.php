@extends('adminlte::page')

@section('title', 'Manajemen Admin Wilayah')

@section('content_header')
    <h1>Manajemen Admin Wilayah</h1>
@stop

@section('content')
    <a href="{{ route('admin-wilayah.create') }}" class="btn btn-primary mb-3">Tambah Admin Wilayah</a>
    <a href="{{ route('admin-wilayah.create') }}" class="btn btn-primary mb-3">Tambah Admin Wilayah</a>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Wilayah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $admin->nama }}</td>
                            <td>{{ $admin->email }}</td>
                            <td>{{ $admin->wilayah->nama_wilayah ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada admin wilayah</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@stop