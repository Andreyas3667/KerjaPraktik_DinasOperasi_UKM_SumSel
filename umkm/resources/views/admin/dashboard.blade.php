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
                    <td>{{ $item->total }}</td>
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
