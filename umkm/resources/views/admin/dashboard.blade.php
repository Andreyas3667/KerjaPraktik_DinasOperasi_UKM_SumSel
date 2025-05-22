@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Penjualan Per Bulan</div>
            <div class="card-body">
                <canvas id="penjualanChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Total UMKM per Wilayah</div>
            <div class="card-body">
                <canvas id="umkmWilayahChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header">Produk Paling Laris</div>
            <div class="card-body">
                <canvas id="produkLarisChart"></canvas>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Penjualan per bulan
    var penjualanChart = new Chart(document.getElementById('penjualanChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_map(fn($b)=>date('F', mktime(0,0,0,$b,1)), array_keys($penjualan->toArray()))) !!},
            datasets: [{
                label: 'Total Penjualan',
                data: {!! json_encode(array_values($penjualan->toArray())) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });

    // Total UMKM per wilayah
    var umkmWilayahChart = new Chart(document.getElementById('umkmWilayahChart').getContext('2d'), {
        type: 'bar',
        data: {
            labels: ['Pagaralam', 'Lahat', 'Muara Enim', 'OKU Selatan', 'Empat Lawang'],
            datasets: [{
                label: 'Jumlah UMKM',
                data: [
                    {{ $umkmWilayah[1] ?? 0 }},
                    {{ $umkmWilayah[2] ?? 0 }},
                    {{ $umkmWilayah[3] ?? 0 }},
                    {{ $umkmWilayah[4] ?? 0 }},
                    {{ $umkmWilayah[5] ?? 0 }}
                ],
                backgroundColor: 'rgba(255, 206, 86, 0.7)'
            }]
        }
    });

    // Produk paling laris
    var produkLarisChart = new Chart(document.getElementById('produkLarisChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($produkLaris->toArray())) !!},
            datasets: [{
                label: 'Produk Terjual',
                data: {!! json_encode(array_values($produkLaris->toArray())) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)'
                ]
            }]
        }
    });
</script>
@stop