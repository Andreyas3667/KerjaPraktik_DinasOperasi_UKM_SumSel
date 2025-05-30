@extends('umkm.layout')

@section('title', 'Stok Barang')

@section('content')
<h2 class="mb-4"><i class="fas fa-box"></i> Stok Barang</h2>
<a href="{{ route('umkm.produk.create') }}" class="btn btn-success mb-3"><i class="fas fa-plus"></i> Tambah Produk</a>
<table class="table table-hover table-bordered shadow-sm rounded" id="produkTable">
    <thead class="thead-dark">
        <tr>
            <th>Produk</th>
            <th>Nama Produk</th>
            <th>Deskripsi</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Gambar</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produks as $produk)
        <tr data-id="{{ $produk->id_produk }}">
            <td class="text-center align-middle">
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" width="60" class="rounded">
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td class="align-middle"><b>{{ $produk->nama_produk }}</b></td>
            <td class="align-middle">{{ $produk->deskripsi }}</td>
            <td class="align-middle">{{ $produk->stok }}</td>
            <td class="align-middle">Rp {{ number_format($produk->harga) }}</td>
            <td class="align-middle text-center">
                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#showFotoModal{{ $produk->id_produk }}">
                    <i class="fas fa-image"></i> Show
                </button>
                <!-- Modal Show Foto -->
                <div class="modal fade" id="showFotoModal{{ $produk->id_produk }}" tabindex="-1" role="dialog" aria-labelledby="showFotoLabel{{ $produk->id_produk }}" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="showFotoLabel{{ $produk->id_produk }}">Foto Produk</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <div class="modal-body text-center">
                        @if($produk->gambar)
                            <img src="{{ asset('storage/'.$produk->gambar) }}" class="img-fluid" style="max-width:300px;">
                        @else
                            <span class="text-muted">Tidak ada foto</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Tombol aksi edit/hapus (dropdown), default: hidden, muncul saat baris aktif -->
                <div class="aksi-dropdown d-none mt-2">
                    <div class="btn-group">
                        <a href="{{ route('umkm.produk.edit', $produk->id_produk) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i> Edit</a>
                        <form action="{{ route('umkm.produk.destroy', $produk->id_produk) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus produk ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center text-muted">Belum ada produk</td>
        </tr>
        @endforelse
    </tbody>
</table>
@endsection

@push('js')
<script>
    // Saat baris diklik, tampilkan tombol aksi edit/hapus hanya di baris itu
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('#produkTable tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                // Reset semua baris
                rows.forEach(r => {
                    r.classList.remove('table-active');
                    let aksi = r.querySelector('.aksi-dropdown');
                    if (aksi) aksi.classList.add('d-none');
                });
                // Aktifkan baris ini
                this.classList.add('table-active');
                let aksi = this.querySelector('.aksi-dropdown');
                if (aksi) aksi.classList.remove('d-none');
            });
        });
        // Klik di luar baris, sembunyikan semua aksi
        document.addEventListener('click', function(e) {
            if (!e.target.closest('#produkTable tbody tr')) {
                rows.forEach(r => {
                    r.classList.remove('table-active');
                    let aksi = r.querySelector('.aksi-dropdown');
                    if (aksi) aksi.classList.add('d-none');
                });
            }
        });
    });
</script>
@endpush