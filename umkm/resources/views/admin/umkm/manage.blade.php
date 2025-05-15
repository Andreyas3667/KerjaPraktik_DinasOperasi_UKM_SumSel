@extends('adminlte::page')

@section('title', 'Kelola UMKM')

@section('content_header')
    <h1>Kelola UMKM</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <h3 class="mb-3">Daftar UMKM</h3>
            <table class="table table-bordered table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Nama Usaha</th>
                        <th>Alamat</th>
                        <th>Kontak</th>
                        <th>Wilayah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($umkms as $umkm)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $umkm->nama_usaha }}</td>
                            <td>{{ $umkm->alamat }}</td>
                            <td>{{ $umkm->kontak }}</td>
                            <td>{{ $umkm->wilayah->nama_wilayah ?? 'Wilayah tidak ditemukan' }}</td>
                            <td>
                                <a href="{{ route('admin.umkm.manage', ['edit' => $umkm->id_umkm]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('admin.umkm.destroy', $umkm->id_umkm) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">{{ $editUMKM ? 'Edit UMKM' : 'Tambah UMKM' }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ $editUMKM ? route('admin.umkm.update', $editUMKM->id_umkm) : route('admin.umkm.store') }}" method="POST">
                        @csrf
                        @if ($editUMKM)
                            @method('PUT')
                        @endif
                        <input type="hidden" name="id_user" value="{{ auth()->check() ? auth()->user()->id_users : 1 }}">
                        <div class="form-group">
                            <label for="nama_usaha">Nama Usaha</label>
                            <input type="text" name="nama_usaha" id="nama_usaha" class="form-control" value="{{ old('nama_usaha', $editUMKM->nama_usaha ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <input type="text" name="alamat" id="alamat" class="form-control" value="{{ old('alamat', $editUMKM->alamat ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="kontak">Kontak</label>
                            <input type="text" name="kontak" id="kontak" class="form-control" value="{{ old('kontak', $editUMKM->kontak ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="id_wilayah">Wilayah</label>
                            <select name="id_wilayah" id="id_wilayah" class="form-control select2" required>
                                <option value="">Pilih Wilayah</option>
                                <option value="1" {{ (isset($editUMKM) && $editUMKM->id_wilayah == 1) ? 'selected' : '' }}>Pagaralam</option>
                                <option value="2" {{ (isset($editUMKM) && $editUMKM->id_wilayah == 2) ? 'selected' : '' }}>Lahat</option>
                                <option value="3" {{ (isset($editUMKM) && $editUMKM->id_wilayah == 3) ? 'selected' : '' }}>Muara Enim</option>
                                <option value="4" {{ (isset($editUMKM) && $editUMKM->id_wilayah == 4) ? 'selected' : '' }}>OKU Selatan</option>
                                <option value="5" {{ (isset($editUMKM) && $editUMKM->id_wilayah == 5) ? 'selected' : '' }}>Empat Lawang</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">{{ $editUMKM ? 'Update' : 'Tambah' }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Pilih Wilayah",
            allowClear: true
        });
    });
</script>
@endpush