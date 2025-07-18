@extends('adminlte::page')

@section('title', 'Penjualan')

@section('content_header')
    <h1>History Penjualan</h1>
@stop

@section('content')
<div class="mb-3">
    <form method="GET" action="{{ route('penjualan.index') }}" class="form-inline">
        <label for="wilayah" class="mr-2">Filter Wilayah:</label>
        <select name="wilayah" id="wilayah" class="form-control mr-2" onchange="this.form.submit()">
            <option value="">Semua Wilayah</option>
            @foreach($wilayahs as $w)
                <option value="{{ $w->id_wilayah }}" {{ $wilayah == $w->id_wilayah ? 'selected' : '' }}>{{ $w->nama_wilayah }}</option>
            @endforeach
        </select>
        <label for="tanggal_dari" class="mr-2 ml-2">Dari:</label>
        <input type="date" name="tanggal_dari" id="tanggal_dari" class="form-control mr-2" value="{{ request('tanggal_dari') }}">
        <label for="tanggal_sampai" class="mr-2">Sampai:</label>
        <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="form-control mr-2" value="{{ request('tanggal_sampai') }}">
        <input type="text" name="search" class="form-control mr-2" placeholder="Cari UMKM/Produk/Pembeli/Alamat Pembeli/Wilayah/Tanggal" value="{{ request('search') }}">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('penjualan.exportPdf', [
            'wilayah' => $wilayah,
            'tanggal_dari' => request('tanggal_dari'),
            'tanggal_sampai' => request('tanggal_sampai'),
            'search' => request('search')
        ]) }}" class="btn btn-danger ml-2" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        <a href="{{ route('penjualan.exportExcel', [
            'wilayah' => $wilayah,
            'tanggal_dari' => request('tanggal_dari'),
            'tanggal_sampai' => request('tanggal_sampai'),
            'search' => request('search')
        ]) }}" class="btn btn-success ml-2" target="_blank">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
    </form>
</div>

@php
    $detailData = [];
    foreach ($transaksis as $trx) {
        $detailData[$trx->id_transaksi] = $trx->detail->map(function($d) {
            return [
                "produk" => $d->produk->nama_produk ?? "-",
                "jumlah" => $d->jumlah,
                "harga" => $d->harga_satuan,
            ];
        });
    }
@endphp

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>UMKM</th>
                    <th>Wilayah</th>
                    <th>Tanggal</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Pembeli</th>
                    <th>Alamat Pembeli</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
@forelse($transaksis as $trx)
    @foreach($trx->detail as $i => $detail)
        <tr>
            @if($i == 0)
                <td rowspan="{{ $trx->detail->count() }}">{{ $loop->parent->iteration }}</td>
                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->umkm->nama_usaha ?? '-' }}</td>
                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->umkm->wilayah->nama_wilayah ?? '-' }}</td>
                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->tanggal_transaksi }}</td>
            @endif
            <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
            <td>{{ number_format($detail->harga_satuan) }}</td>
            <td>
                {{ $detail->jumlah }}
                @if($trx->status_pembayaran == 'pending')
                    <button class="btn btn-sm btn-warning ml-2" data-toggle="modal" data-target="#editJumlahModal{{ $detail->id_detail }}" title="Edit Jumlah">
                        <i class="fas fa-pencil-alt"></i>
                    </button>
                    <!-- Modal Edit Jumlah -->
                    <div class="modal fade" id="editJumlahModal{{ $detail->id_detail }}" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                            <form action="{{ route('penjualan.editJumlah', $detail->id_detail) }}" method="POST">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Jumlah Pesanan</h5>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="number" name="jumlah" class="form-control" value="{{ $detail->jumlah }}" min="1" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </td>
            <td>{{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
            @if($i == 0)
                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->user->nama ?? '-' }}</td>
                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->user->alamat ?? '-' }}</td>
                <td rowspan="{{ $trx->detail->count() }}">
                    {{-- Aksi --}}
                    @if($trx->status_pembayaran == 'pending')
                        <button class="btn btn-success btn-sm btn-konfirmasi-pesanan"
                            data-id="{{ $trx->id_transaksi }}"
                            data-pembeli="{{ $trx->user->nama }}"
                            data-umkm="{{ $trx->umkm->nama_usaha }}"
                            data-wilayah="{{ $trx->umkm->wilayah->nama_wilayah }}"
                            data-alamat="{{ $trx->user->alamat }}"
                            data-detail='@json($detailData[$trx->id_transaksi])'
                            title="Konfirmasi">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btn-batalkan-pesanan"
                            data-id="{{ $trx->id_transaksi }}"
                            data-pembeli="{{ $trx->user->nama }}"
                            data-umkm="{{ $trx->umkm->nama_usaha }}"
                            data-wilayah="{{ $trx->umkm->wilayah->nama_wilayah }}"
                            data-alamat="{{ $trx->user->alamat }}"
                            data-detail='@json($detailData[$trx->id_transaksi])'
                            title="Batalkan">
                            <i class="fas fa-times"></i>
                        </button>
                    @elseif($trx->status_pembayaran == 'selesai')
                        <span class="badge badge-success">Sukses</span>
                    @elseif($trx->status_pembayaran == 'batal')
                        <span class="badge badge-danger">Dibatalkan</span>
                    @endif
                </td>
            @endif
        </tr>
    @endforeach
@empty
    <tr>
        <td colspan="11" class="text-center">Tidak ada data penjualan</td>
    </tr>
@endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Konfirmasi Pesanan --}}
<div class="modal fade" id="modalKonfirmasiPesanan" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title">Konfirmasi Pemesanan Produk</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="bodyKonfirmasiPesanan">
        <!-- Isi detail pesanan akan diisi via JS -->
      </div>
      <div class="modal-footer">
        <form id="formKonfirmasiPesanan" method="POST">
          @csrf
          <input type="hidden" name="status" value="selesai">
          <button type="submit" class="btn btn-success">Konfirmasi</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
      </div>
    </div>
  </div>
</div>

{{-- Modal Batalkan Pesanan --}}
<div class="modal fade" id="modalBatalkanPesanan" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">Batalkan Pemesanan Produk</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="bodyBatalkanPesanan">
        <!-- Isi detail pesanan akan diisi via JS -->
      </div>
      <div class="modal-footer">
        <form id="formBatalkanPesanan" method="POST">
          @csrf
          <input type="hidden" name="status" value="batal">
          <button type="submit" class="btn btn-danger">Batalkan</button>
        </form>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@stop

@section('css')
<style>
    /* Tambahkan CSS khusus jika diperlukan */
</style>
@stop

@section('js')
<script>
$(document).on('click', '.btn-konfirmasi-pesanan', function() {
    let id = $(this).data('id');
    let pembeli = $(this).data('pembeli');
    let umkm = $(this).data('umkm');
    let wilayah = $(this).data('wilayah');
    let alamat = $(this).data('alamat');
    let detail = $(this).data('detail');

    let html = `<div class="card">
        <div class="card-body">
            <p><b>Pembeli:</b> ${pembeli}</p>
            <p><b>UMKM:</b> ${umkm} (${wilayah})</p>
            <p><b>Alamat Pembeli:</b> ${alamat}</p>
            <hr>
            <b>Pesanan:</b>
            <ul>`;
    detail.forEach(function(item) {
        html += `<li>
            Produk: <b>${item.produk}</b><br>
            Jumlah: <b>${item.jumlah}</b><br>
            Harga Satuan: <b>Rp ${parseInt(item.harga).toLocaleString('id-ID')}</b><br>
            Total: <b>Rp ${(item.jumlah * item.harga).toLocaleString('id-ID')}</b>
        </li>`;
    });
    html += `</ul></div></div>`;

    $('#bodyKonfirmasiPesanan').html(html);
    $('#formKonfirmasiPesanan').attr('action', '/admin/penjualan/verifikasi/' + id);
    $('#modalKonfirmasiPesanan').modal('show');
});

$(document).on('click', '.btn-batalkan-pesanan', function() {
    let id = $(this).data('id');
    let pembeli = $(this).data('pembeli');
    let umkm = $(this).data('umkm');
    let wilayah = $(this).data('wilayah');
    let alamat = $(this).data('alamat');
    let detail = $(this).data('detail');

    let html = `<div class="card">
        <div class="card-body">
            <p><b>Pembeli:</b> ${pembeli}</p>
            <p><b>UMKM:</b> ${umkm} (${wilayah})</p>
            <p><b>Alamat Pembeli:</b> ${alamat}</p>
            <hr>
            <b>Pesanan:</b>
            <ul>`;
    detail.forEach(function(item) {
        html += `<li>
            Produk: <b>${item.produk}</b><br>
            Jumlah: <b>${item.jumlah}</b><br>
            Harga Satuan: <b>Rp ${parseInt(item.harga).toLocaleString('id-ID')}</b><br>
            Total: <b>Rp ${(item.jumlah * item.harga).toLocaleString('id-ID')}</b>
        </li>`;
    });
    html += `</ul></div></div>`;

    $('#bodyBatalkanPesanan').html(html);
    $('#formBatalkanPesanan').attr('action', '/admin/penjualan/batal/' + id);
    $('#modalBatalkanPesanan').modal('show');
});
</script>
@stop
