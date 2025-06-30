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
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($produks as $produk)
        <tr>
            <td class="text-center align-middle">
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" width="60" class="rounded img-detail-produk" data-img="{{ asset('storage/'.$produk->gambar) }}" style="cursor:pointer;">
                @else
                    <span class="text-muted">-</span>
                @endif
            </td>
            <td class="align-middle"><b>{{ $produk->nama_produk }}</b></td>
            <td class="align-middle">{{ $produk->deskripsi }}</td>
            <td class="align-middle">{{ $produk->stok }}</td>
            <td class="align-middle">Rp {{ number_format($produk->harga) }}</td>
            <td class="align-middle">
                <div class="d-inline-flex align-items-center">
                    <a href="{{ route('umkm.produk.edit', $produk->id_produk) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('umkm.produk.destroy', $produk->id_produk) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ml-2" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
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

<!-- Modal untuk detail gambar -->
<div class="modal fade" id="modalFotoProduk" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="" id="imgModalProduk" class="img-fluid" style="max-width:400px;">
      </div>
    </div>
  </div>
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.img-detail-produk').forEach(function(img) {
            img.addEventListener('click', function() {
                document.getElementById('imgModalProduk').src = this.dataset.img;
                $('#modalFotoProduk').modal('show');
            });
        });
    });
</script>
@endpush
