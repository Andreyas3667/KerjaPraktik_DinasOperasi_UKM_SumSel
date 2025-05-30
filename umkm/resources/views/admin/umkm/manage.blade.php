@extends('adminlte::page')

@section('title', 'UMKM')

@section('content')
<div class="row mb-3">
    <div class="col-md-8">
        {{-- Search Bar --}}
        <form method="GET" action="{{ route('admin.umkm.manage') }}" class="input-group mb-2">
            <input type="text" id="searchUmkm" name="search" class="form-control" placeholder="Cari nama UMKM, alamat, kontak, atau wilayah..." autocomplete="off">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
        <!-- {{-- Tombol Edit Dropdown --}} -->
        <div id="editDropdownWrapper" class="mt-2 d-none">
            <div class="btn-group">
                <button type="button" class="btn btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="editDropdownBtn">
                    Edit
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" id="editAction">Edit</a>
                    <a class="dropdown-item text-danger" href="#" id="deleteAction">Hapus</a>
                </div>
            </div>
        </div>
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
                <option value="6" {{ request('wilayah') == 6 ? 'selected' : '' }}>Palembang</option>
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
        <table class="table table-bordered table-striped" id="umkmTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Nama Usaha</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Wilayah</th>
                    <th>Menu Kopi/Produk</th>
                </tr>
            </thead>
            <tbody>
                @include('admin.umkm.partials.table', ['umkms' => $umkms])
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah/Edit UMKM --}}
<div class="modal fade" id="modalTambahUMKM" tabindex="-1" role="dialog" aria-labelledby="modalTambahUMKMLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="formUmkm" action="{{ route('admin.umkm.store') }}" method="POST">
                @csrf
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
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" id="alamat" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <input type="text" name="kontak" id="kontak" class="form-control" required pattern="[0-9]+">
                    </div>
                    <div class="form-group">
                        <label for="longitude">Longitude</label>
                        <input type="text" name="longitude" id="longitude" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="latitude">Latitude</label>
                        <input type="text" name="latitude" id="latitude" class="form-control" required>
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
                            <option value="6">Palembang</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Show Produk --}}
<div class="modal fade" id="modalShowProduk" tabindex="-1" role="dialog" aria-labelledby="modalShowProdukLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalShowProdukLabel">Daftar Produk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul id="listProduk"></ul>
      </div>
    </div>
  </div>
</div>

@if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
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

    let selectedUmkmId = null;

    // Reset highlight & hide edit button
    function resetSelection() {
        $('#umkmTable tbody tr').removeClass('table-active');
        $('#editDropdownWrapper').addClass('d-none');
        selectedUmkmId = null;
    }

    // Klik baris tabel
    $('#umkmTable tbody').on('click', 'tr', function() {
        resetSelection();
        $(this).addClass('table-active');
        selectedUmkmId = $(this).data('id');
        $('#editDropdownWrapper').removeClass('d-none');
    });

    // Klik di luar tabel, sembunyikan tombol edit
    $(document).on('click', function(e) {
        if (!$(e.target).closest('#umkmTable, #editDropdownWrapper').length) {
            resetSelection();
        }
    });

    // Aksi Edit
    $('#editAction').on('click', function(e) {
        e.preventDefault();
        if (selectedUmkmId) {
            // Ambil data dari baris terpilih
            let row = $('#umkmTable tbody tr.table-active');
            let nama = row.data('nama');
            let alamat = row.data('alamat');
            let kontak = row.data('kontak');
            let wilayah = row.data('wilayah');

            // Isi form modal
            $('#modalTambahUMKMLabel').text('Edit UMKM');
            // Edit action
            $('#formUmkm').attr('action', '{{ url("admin/umkm") }}/' + selectedUmkmId);
            if (!$('#formUmkm input[name="_method"]').length) {
                $('#formUmkm').append('<input type="hidden" name="_method" value="PUT" id="methodEdit">');
            }
            $('#nama_usaha').val(nama);
            $('#alamat').val(alamat);
            $('#kontak').val(kontak);
            $('#id_wilayah').val(wilayah).trigger('change');
            $('#deskripsi').val(row.data('deskripsi'));
            $('#longitude').val(row.data('longitude'));
            $('#latitude').val(row.data('latitude'));

            // Tampilkan modal
            $('#modalTambahUMKM').modal('show');
        }
    });

    // Reset modal saat ditutup
    $('#modalTambahUMKM').on('hidden.bs.modal', function () {
        $('#modalTambahUMKMLabel').text('Tambah UMKM');
        $('#formUmkm').attr('action', '{{ route("admin.umkm.store") }}');
        $('#methodEdit').remove();
        $('#formUmkm')[0].reset();
        $('#id_wilayah').val('').trigger('change');
    });

    // Aksi Hapus
    $('#deleteAction').on('click', function(e) {
        e.preventDefault();
        if (selectedUmkmId && confirm('Yakin ingin menghapus UMKM ini?')) {
            // Buat form hapus dinamis
            let form = $('<form>', {
                'method': 'POST',
                'action': '{{ url("admin/umkm") }}/' + selectedUmkmId
            });
            form.append('@csrf');
            form.append('<input type="hidden" name="_method" value="DELETE">');
            $('body').append(form);
            form.submit();
        }
    });

    $(document).on('click', '.show-produk-btn', function() {
        let produk = $(this).data('produk');
        let list = '';
        if (produk.length) {
            produk.forEach(function(nama) {
                list += '<li>' + nama + '</li>';
            });
        } else {
            list = '<li>Tidak ada produk</li>';
        }
        $('#listProduk').html(list);
        $('#modalShowProduk').modal('show');
    });

    $('#searchUmkm').on('keyup', function() {
        let query = $(this).val();
        $.ajax({
            url: '{{ route("admin.umkm.search") }}',
            type: 'GET',
            data: { search: query },
            success: function(data) {
                $('#umkmTable tbody').html(data);
            }
        });
    });

    $('#kontak').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush