@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <h1>Selamat Datang di UMKM Kopi</h1>
        <h2>Platform yang menghubungkan UMKM Kopi di Sumatera Selatan</h2>
        <a href="{{ url('/maps') }}" class="btn-get-started scrollto">Lihat Peta UMKM</a>
    </div>
</section>

<!-- ======= About Section ======= -->
<section id="about" class="about">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 order-1 order-lg-2">
                <img src="{{ asset('template/assets/img/about.jpg') }}" class="img-fluid" alt="">
            </div>
            <div class="col-lg-6 pt-4 pt-lg-0 order-2 order-lg-1">
                <h3>Tentang Kami</h3>
                <p>
                    UMKM Kopi adalah platform yang membantu petani dan pelaku usaha kopi di Sumatera Selatan untuk terhubung dengan pasar yang lebih luas.
                </p>
                <ul>
                    <li><i class="bi bi-check-circle"></i> Peta interaktif untuk menemukan UMKM.</li>
                    <li><i class="bi bi-check-circle"></i> Informasi lengkap tentang setiap UMKM.</li>
                    <li><i class="bi bi-check-circle"></i> Dukungan penjualan melalui WhatsApp.</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
