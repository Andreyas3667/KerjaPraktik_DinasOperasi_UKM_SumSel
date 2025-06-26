@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="mb-4">Dashboard</h1>
@stop

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" class="input-group">
            <input type="text" name="search" class="form-control form-control-lg rounded-pill" placeholder="Cari UMKM, Produk, atau Wilayah..." value="{{ request('search') }}">
            <div class="input-group-append">
                <button class="btn btn-primary rounded-pill px-4" type="submit"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-right">
        <form method="GET" class="d-inline">
            <select name="tahun" class="form-control d-inline w-auto rounded-pill" onchange="this.form.submit()">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="mb-2"><i class="fas fa-store fa-2x text-success"></i></div>
                <h6 class="text-muted">Total UMKM</h6>
                <h3>{{ $umkmWilayah->sum() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center shadow-sm">
            <div class="card-body">
                <div class="mb-2"><i class="fas fa-box fa-2x text-info"></i></div>
                <h6 class="text-muted">Produk Terjual</h6>
                <h3>{{ $produkLaris->sum() }}</h3>
            </div>
        </div>
    </div>
    <!-- Tambahkan card lain sesuai kebutuhan -->
</div>

<div class="card shadow mb-4">
    <div class="card-header bg-primary text-white">
        <b>UMKM dengan Penjualan Terbanyak ({{ $tahun }})</b>
    </div>
    <div class="card-body">
        @if(count($umkmPenjualan) > 0)
            <canvas id="umkmPenjualanChart"></canvas>
        @else
            <div class="alert alert-info">Belum ada data penjualan UMKM.</div>
        @endif
    </div>
</div>

<div class="card shadow">
    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Total Penjualan</th>
                    <!-- dst -->
                </tr>
            </thead>
            <tbody>
                @if(isset($penjualan) && count($penjualan) > 0)
                    @foreach($penjualan as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->total }}</td>
                            <!-- dst -->
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="2">Tidak ada data penjualan.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin-sidebar.css') }}">
<style>
    .card {
        border-radius: 16px !important;
    }
    .form-control-lg, .form-control, .btn {
        border-radius: 2rem !important;
    }
    .table thead th {
        background: #343a40;
        color: #fff;
        border-top: none;
    }
    .table {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
    }
    .card-header {
        border-radius: 16px 16px 0 0 !important;
    }
</style>
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
            backgroundColor: 'rgba(54, 162, 235, 0.7)'
        }]
    }
});
</script>
@endif
@stop
