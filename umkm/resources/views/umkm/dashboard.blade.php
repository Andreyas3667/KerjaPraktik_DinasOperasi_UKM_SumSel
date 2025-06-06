@extends('umkm.layout')

@section('title', 'Dashboard UMKM')

@section('content')
<h3>Selamat Datang di Dashboard UMKM</h3>
<p>Kelola produk, laporan penjualan, dan profil UMKM Anda di sini.</p>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6>Total Produk</h6>
                <h2>{{ $totalProduk }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6>Total Penjualan</h6>
                <h2>Rp {{ number_format($totalPenjualan) }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h6>Total Transaksi</h6>
                <h2>{{ $totalTransaksi }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header">Grafik Penjualan Bulanan</div>
    <div class="card-body">
        <canvas id="grafikPenjualan"></canvas>
    </div>
</div>

<div class="card">
    <div class="card-header">Produk Terlaris</div>
    <ul class="list-group list-group-flush">
        @forelse($produkTerlaris as $produk)
            <li class="list-group-item d-flex align-items-center">
                @if($produk->gambar)
                    <img src="{{ asset('storage/'.$produk->gambar) }}" width="40" class="mr-2 rounded">
                @endif
                {{ $produk->nama_produk }} <span class="ml-auto badge badge-success">{{ $produk->jumlah_terjual }} terjual</span>
            </li>
        @empty
            <li class="list-group-item text-muted">Belum ada data</li>
        @endforelse
    </ul>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('grafikPenjualan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($penjualanBulanan->keys()) !!},
            datasets: [{
                label: 'Penjualan',
                data: {!! json_encode($penjualanBulanan->values()) !!},
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });
</script>
@endpush