@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Dashboard</h1>
        @auth
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-outline-danger btn-sm" type="submit">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        @endauth
    </div>
@stop

@section('content')
<form method="GET" class="mb-3">
    <label for="tahun">Tahun:</label>
    <select name="tahun" id="tahun" onchange="this.form.submit()">
        @foreach($tahunList as $t)
            <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
        @endforeach
    </select>
</form>

<div class="card mb-4">
    <div class="card-header">UMKM dengan Penjualan Terbanyak ({{ $tahun }})</div>
    <div class="card-body">
        @if(count($umkmPenjualan) > 0)
            <canvas id="umkmPenjualanChart"></canvas>
        @else
            <div class="alert alert-info">Belum ada data penjualan UMKM.</div>
        @endif
    </div>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Nama UMKM</th>
            <th>Tanggal</th>
            <th>Total Penjualan</th>
            <th>Produk Terjual</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($penjualan) && count($penjualan) > 0)
            @foreach($penjualan as $item)
                <tr>
                    <td>{{ $item->umkm->nama_usaha ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m-d') }}</td>
                    <td>Rp {{ number_format($item->total, 0, ',', '.') }}</td>
                    <td>
                        @if($item->detail && count($item->detail) > 0)
                            <ul class="mb-0 pl-3">
                                @foreach($item->detail as $d)
                                    <li>
                                        {{ $d->produk->nama_produk ?? '-' }} ({{ $d->jumlah }})
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="4">Tidak ada data penjualan.</td>
            </tr>
        @endif
    </tbody>
</table>

{{-- filepath: resources/views/admin/dashboard.blade.php --}}
<div class="card mb-4">
    <div class="card-header font-weight-bold">
        Produk Terlaris (Top 3)
    </div>
    <ul class="list-group list-group-flush">
        @forelse($topProdukTerlaris as $produk)
            <li class="list-group-item d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    @if($produk->gambar)
                        <img src="{{ asset('storage/' . $produk->gambar) }}" alt="{{ $produk->nama_produk }}" style="width:40px;height:40px;object-fit:cover;border-radius:4px;margin-right:12px;">
                    @else
                        <span class="mr-2" style="width:40px;height:40px;display:inline-block;background:#eee;border-radius:4px;"></span>
                    @endif
                    <div>
                        <div class="font-weight-bold">{{ $produk->nama_produk }}</div>
                        <small class="text-muted">
                            {{ $produk->nama_usaha }} &mdash; {{ $produk->nama_wilayah }}
                        </small>
                    </div>
                </div>
                <span class="badge badge-success badge-pill" style="font-size:1em;">
                    {{ $produk->jumlah_terjual }} terjual
                </span>
            </li>
        @empty
            <li class="list-group-item text-muted">Belum ada data produk terlaris.</li>
        @endforelse
    </ul>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@if(count($umkmPenjualan) > 0)
<script>
var umkmPenjualanChart = new Chart(document.getElementById('umkmPenjualanChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: {!! json_encode(array_keys($umkmPenjualan->toArray())) !!},
        datasets: [{
            label: 'Total Penjualan',
            data: {!! json_encode(array_values($umkmPenjualan->toArray())) !!},
            backgroundColor: 'rgba(255, 99, 132, 0.7)'
        }]
    }
});
</script>
@endif
@stop
