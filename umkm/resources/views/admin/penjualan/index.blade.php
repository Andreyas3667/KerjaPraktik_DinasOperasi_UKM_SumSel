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
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('penjualan.export', ['wilayah' => $wilayah, 'tanggal_dari' => request('tanggal_dari'), 'tanggal_sampai' => request('tanggal_sampai')]) }}" class="btn btn-danger ml-2" target="_blank">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
    </form>
</div>
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
                            <td>{{ $detail->jumlah }}</td>
                            <td>{{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
                            @if($i == 0)
                                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->user->nama ?? '-' }}</td>
                                <td rowspan="{{ $trx->detail->count() }}">{{ $trx->user->alamat ?? '-' }}</td>
                                <td rowspan="{{ $trx->detail->count() }}">
                                    {{-- Aksi --}}
                                    @if($trx->status_pembayaran == 'pending')
                                        <form action="{{ route('penjualan.verifikasi', $trx->id_transaksi) }}" method="POST" style="display:inline;" onsubmit="return confirm('Konfirmasi pesanan?');">
                                            @csrf
                                            <input type="hidden" name="status" value="selesai">
                                            <button class="btn btn-success btn-sm" title="Konfirmasi"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('penjualan.batal', $trx->id_transaksi) }}" method="POST" style="display:inline;" onsubmit="return confirm('Batalkan pesanan?');">
                                            @csrf
                                            <input type="hidden" name="status" value="batal">
                                            <button class="btn btn-danger btn-sm" title="Batalkan"><i class="fas fa-times"></i></button>
                                        </form>
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
@stop
