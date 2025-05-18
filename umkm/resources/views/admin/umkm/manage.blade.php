@extends('adminlte::page')

@section('title', 'UMKM')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        {{-- Search Bar --}}
        <form method="GET" action="{{ route('admin.umkm.manage') }}" class="input-group mb-2">
            <input type="text" name="search" class="form-control" placeholder="Cari nama UMKM, alamat, kontak, atau wilayah..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-right">
        {{-- Grouping Wilayah --}}
        <form method="GET" action="{{ route('admin.umkm.manage') }}" class="d-inline">
            <select name="wilayah" class="form-control d-inline w-auto" onchange="this.form.submit()">
                <option value="">Semua Wilayah</option>
                <option value="1" {{ request('wilayah') == 1 ? 'selected' : '' }}>Pagaralam</option>
                <option value="2" {{ request('wilayah') == 2 ? 'selected' : '' }}>Lahat</option>
                <option value="3" {{ request('wilayah') == 3 ? 'selected' : '' }}>Muara Enim</option>
                <option value="4" {{ request('wilayah') == 4 ? 'selected' : '' }}>OKU Selatan</option>
                <option value="5" {{ request('wilayah') == 5 ? 'selected' : '' }}>Empat Lawang</option>
            </select>
        </form>
        {{-- Tombol Daftar --}}
        <button class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalTambahUMKM">
            <i class="fas fa-plus"></i> Daftar
        </button>
    </div>
</div>

{{-- Tabel UMKM --}}
<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Usaha</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Wilayah</th>
                    <th>Menu Kopi/Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($umkms as $umkm)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $umkm->nama_usaha }}</td>
                        <td>{{ $umkm->alamat }}</td>
                        <td>{{ $umkm->kontak }}</td>
                        <td>{{ $umkm->wilayah->nama_wilayah ?? '-' }}</td>
                        <td>
                            @if($umkm->produk && $umkm->produk->count())
                                <ul class="mb-0 pl-3">
                                    @foreach($umkm->produk as $produk)
                                        <li>{{ $produk->nama_produk }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">Belum ada produk</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editUMKM('{{ $umkm->id_umkm }}')">Edit</button>
                            <form action="{{ route('admin.umkm.destroy', $umkm->id_umkm) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data UMKM</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah/Edit UMKM --}}
<div class="modal fade" id="modalTambahUMKM" tabindex="-1" role="dialog" aria-labelledby="modalTambahUMKMLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="formUmkm" action="{{ route('admin.umkm.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="modalTambahUMKMLabel">Tambah UMKM</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_user" value="{{ auth()->check() ? auth()->user()->id_users : 1 }}">
                    <div class="form-group">
                        <label for="nama_usaha">Nama Usaha</label>
                        <input type="text" name="nama_usaha" id="nama_usaha" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <input type="text" name="kontak" id="kontak" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="id_wilayah">Wilayah</label>
                        <select name="id_wilayah" id="id_wilayah" class="form-control select2" required>
                            <option value="">Pilih Wilayah</option>
                            <option value="1">Pagaralam</option>
                            <option value="2">Lahat</option>
                            <option value="3">Muara Enim</option>
                            <option value="4">OKU Selatan</option>
                            <option value="5">Empat Lawang</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            dropdownParent: $('#modalTambahUMKM'),
            placeholder: "Pilih Wilayah",
            allowClear: true
        });
    });

    // Filter wilayah otomatis submit
    $('select[name="wilayah"]').change(function() {
        $(this).closest('form').submit();
    });

    // Edit UMKM (AJAX/Modal, contoh sederhana)
    window.editUMKM = function(id) {
        // TODO: Implementasi AJAX untuk ambil data UMKM dan tampilkan di modal
        alert('Fitur edit UMKM via modal belum diimplementasikan.');
    }
</script>
@endpush