@extends('umkm.layout')

@section('title', 'Laporan Penjualan')

@section('content')
<h3>Laporan Penjualan</h3>
<a href="{{ route('umkm.laporan.export') }}" class="btn btn-danger mb-3">
    <i class="fas fa-file-pdf"></i> Export PDF
</a>

@if($transaksis->count() > 0)
    <canvas id="grafikPenjualan" height="100"></canvas>
    <table class="table table-bordered mt-4">
        <thead class="table-light">
            <tr>
                <th>Tanggal</th>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksis as $trx)
                @foreach($trx->detail as $detail)
                    <tr>
                        <td>{{ $trx->tanggal_transaksi }}</td>
                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                        <td>Rp {{ number_format($detail->harga_satuan) }}</td>
                        <td>{{ $detail->jumlah }}</td>
                        <td>Rp {{ number_format($detail->jumlah * $detail->harga_satuan) }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-info">Belum ada transaksi penjualan yang terkonfirmasi.</div>
@endif
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik penjualan per bulan
    const data = @json($dataPenjualanBulanan);
    const ctx = document.getElementById('grafikPenjualan').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: Object.keys(data),
            datasets: [{
                label: 'Jumlah Penjualan',
                data: Object.values(data),
                backgroundColor: 'rgba(54, 162, 235, 0.7)'
            }]
        }
    });
</script>
@endpush

@php
    if (is_array($transaksis)) {
        $transaksis = collect($transaksis);
    }
@endphp

{{-- @if($transaksis->count() > 0)
    @foreach($transaksis->filter(fn($item) => $item->tanggal_transaksi)->groupBy(function($item) {
        return \Carbon\Carbon::parse($item->tanggal_transaksi)->format('Y-m');
    }) as $bulan => $transaksiBulan)
        <h5 class="mt-4">Bulan: {{ $bulan }}</h5>
        <ul>
            @foreach($transaksiBulan as $trx)
                <li>
                    {{ $trx->tanggal_transaksi }} -
                    @foreach($trx->detail as $detail)
                        {{ $detail->produk->nama_produk ?? '-' }} ({{ $detail->jumlah }})@if(!$loop->last), @endif
                    @endforeach
                </li>
            @endforeach
        </ul>
    @endforeach
@endif --}}
