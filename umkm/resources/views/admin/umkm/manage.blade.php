@extends('adminlte::page')

@section('title', 'UMKM')

@section('content')
<div class="row mb-3">
    <div class="col-md-9">
        {{-- Search Bar --}}
        <form method="GET" action="{{ route('admin.umkm.index') }}" class="input-group">
            <input type="text" id="searchUmkm" name="search" class="form-control" placeholder="Cari nama UMKM, alamat, kontak, atau wilayah..." autocomplete="off" value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>
    </div>
    <div class="col-md-3 d-flex justify-content-end align-items-start">
        {{-- Grouping Wilayah --}}
        <form method="GET" action="{{ route('admin.umkm.index') }}" class="mr-2" style="min-width:150px;">
            <select name="wilayah" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Wilayah</option>
                @foreach($wilayahs as $wilayah)
                    <option value="{{ $wilayah->id_wilayah }}" {{ request('wilayah') == $wilayah->id_wilayah ? 'selected' : '' }}>
                        {{ $wilayah->nama_wilayah }}
                    </option>
                @endforeach
            </select>
        </form>
        {{-- Tombol Daftar --}}
        <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#modalTambahUMKM">
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
                    <th>Deskripsi</th>
                    <th>Menu Kopi/Produk</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($umkms as $umkm)
                <tr
                    data-namapj="{{ $umkm->user->nama ?? '' }}"
                    data-email="{{ $umkm->user->email ?? '' }}"
                    data-longitude="{{ $umkm->longitude }}"
                    data-latitude="{{ $umkm->latitude }}"
                >
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $umkm->nama_usaha }}</td>
                    <td>{{ $umkm->alamat }}</td>
                    <td>{{ $umkm->kontak }}</td>
                    <td>{{ $umkm->wilayah->nama_wilayah ?? '-' }}</td>
                    <td>{{ $umkm->deskripsi }}</td>
                    <td>
                        <button class="btn btn-info btn-sm show-produk-btn" data-produk='@json($umkm->produk->map(function($p) {
                            return [
                                "nama_produk" => $p->nama_produk,
                                "deskripsi" => $p->deskripsi,
                                "gambar" => $p->gambar
                            ];
                        }))'>
                            Show
                        </button>
                    </td>
                    <td class="text-right align-middle">
                        <div class="d-inline-flex align-items-center">
                            <button class="btn btn-warning btn-sm" title="Edit" onclick="editUmkm({{ $umkm->id_umkm }})">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.umkm.destroy', $umkm->id_umkm) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus UMKM ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm ml-2" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
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
                        <label for="nama_usaha">Nama UMKM</label>
                        <input type="text" name="nama_usaha" id="nama_usaha" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="nama">Nama Penanggung Jawab</label>
                        <input type="text" name="nama" id="nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="kontak">Kontak</label>
                        <input type="text" name="kontak" id="kontak" class="form-control" required pattern="[0-9]+">
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
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
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
                            @foreach($wilayahs as $wilayah)
                                <option value="{{ $wilayah->id_wilayah }}">{{ $wilayah->nama_wilayah }}</option>
                            @endforeach
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

@push('css')
<style>
.select2-container {
    width: 100% !important;
}
.select2-dropdown {
    z-index: 9999 !important;
}
.select2-container .select2-selection--single .select2-selection__rendered {
    padding-top: 5px !important;   /* Tambah padding atas */
    padding-bottom: 5px !important;
    line-height: 1.4 !important;   /* Bisa sesuaikan lagi jadi 1.5 atau 1.2 */
    font-size: 14px !important;
}
.select2-container .select2-selection--single {
    height: auto !important;
    min-height: 38px !important; /* Sesuaikan tinggi agar proporsional */
    display: flex;
    align-items: center; /* Ini membantu vertikal centering */
}
</style>
@endpush

@push('js')
<script>
    function initSelect2Wilayah() {
        $('#id_wilayah').select2({
            dropdownParent: $('#modalTambahUMKM .modal-content'),
            width: '100%',
            placeholder: "Pilih Wilayah",
            allowClear: true
        });
    }

    $(document).ready(function() {
        initSelect2Wilayah();
        // Filter wilayah otomatis submit
        $('select[name="wilayah"]').change(function() {
            $(this).closest('form').submit();
        });

        let selectedUmkmId = null;

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
            let namapj = row.data('namapj');
            let email = row.data('email');
            let deskripsi = row.data('deskripsi');
            let longitude = row.data('longitude');
            let latitude = row.data('latitude');

            // Isi form modal
            $('#modalTambahUMKMLabel').text('Edit UMKM');
            // Edit action
            $('#formUmkm').attr('action', '{{ url("admin/umkm") }}/' + selectedUmkmId);
            if (!$('#formUmkm input[name="_method"]').length) {
                $('#formUmkm').append('<input type="hidden" name="_method" value="PUT" id="methodEdit">');
            }
            $('#nama').val(namapj);
            $('#email').val(email);
            $('#deskripsi').val(deskripsi);
            $('#nama_usaha').val(nama);
            $('#alamat').val(alamat);
            $('#kontak').val(kontak);
            $('#id_wilayah').val(wilayah).trigger('change');
            $('#longitude').val(longitude);
            $('#latitude').val(latitude);

            // Tampilkan modal
            $('#modalTambahUMKM').modal('show');
        }
    });

    // Tambahkan fungsi ini:
    function editUmkm(id) {
    // Temukan baris UMKM berdasarkan tombol edit yang diklik
    let row = $('#umkmTable tbody tr').filter(function() {
        return $(this).find('button[onclick="editUmkm(' + id + ')"]').length > 0;
    });

    // Ambil data dari atribut data-
    let namapj = row.data('namapj') ?? '';
    let email = row.data('email') ?? '';
    let longitude = row.data('longitude') ?? '';
    let latitude = row.data('latitude') ?? '';

    // Ambil data dari kolom tabel
    let nama = row.find('td').eq(1).text().trim();
    let alamat = row.find('td').eq(2).text().trim();
    let kontak = row.find('td').eq(3).text().trim();
    let wilayah = row.find('td').eq(4).text().trim();
    let deskripsi = row.find('td').eq(5).text().trim();

    // Set data ke modal/form
    $('#modalTambahUMKMLabel').text('Edit UMKM');
    $('#formUmkm').attr('action', '{{ url("admin/umkm") }}/' + id);
    if (!$('#formUmkm input[name="_method"]').length) {
        $('#formUmkm').append('<input type="hidden" name="_method" value="PUT" id="methodEdit">');
    }
    $('#nama_usaha').val(nama);
    $('#nama').val(namapj); // <-- ini harus terisi
    $('#email').val(email); // <-- ini harus terisi
    $('#alamat').val(alamat);
    $('#kontak').val(kontak);
    $('#deskripsi').val(deskripsi);
    $('#longitude').val(longitude);
    $('#latitude').val(latitude);
    // Untuk wilayah, mapping value option sesuai nama
    $('#id_wilayah option').filter(function() {
        return $(this).text().trim() === wilayah;
    }).prop('selected', true);
    $('#id_wilayah').trigger('change');

    $('#modalTambahUMKM').modal('show');
}

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
        let storagePath = '{{ asset("storage") }}';
        if (produk.length) {
            produk.forEach(function(item) {
                list += '<li>';
                if (item.gambar) {
                    list += '<img src="' + storagePath + '/' + item.gambar + '" alt="Foto Produk" width="80"><br>';
                }
                list += '<strong>' + (item.nama_produk ?? '-') + '</strong><br>';
                list += (item.deskripsi ?? '-') + '<br>';
                list += '</li>';
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
