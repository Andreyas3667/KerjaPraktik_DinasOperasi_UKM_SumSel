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
        <a href="{{ route('penjualan.export', ['wilayah' => $wilayah]) }}" class="btn btn-danger ml-2" target="_blank">
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
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="8" class="text-center">Tidak ada data penjualan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@stop