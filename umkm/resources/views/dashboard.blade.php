@extends('layout.main')

@section('title', 'Dashboard')

@section('content')
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center">
    <div class="container">
        <h1>Selamat Datang di UMKM Kopi</h1>
        <h2>Platform yang menghubungkan UMKM Kopi di Sumatera Selatan</h2>
        <div id="map" style="width: 100%; height: 500px;"></div>
        <!-- Leaflet.js -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <link rel="stylesheet" href="{{ asset('css/main.css') }}">
        <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
        <script>
            var map = L.map('map').setView([-3.319437, 103.914399], 7);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            fetch('/umkm')
                .then(response => response.json())
                .then(data => {
                    data.forEach(umkm => {
                        var lat = parseFloat(umkm.latitude);
                        var lng = parseFloat(umkm.longitude);
                        if (!isNaN(lat) && !isNaN(lng)) {
                            let popupContent = `<b>${umkm.nama_usaha}</b><br>
                                <small>${umkm.deskripsi ?? ''}</small><br>
                                <a href='/umkm/${umkm.id_umkm}'>Lihat Detail</a>`;
                            L.marker([lat, lng]).addTo(map)
                                .bindPopup(popupContent);
                        }
                    });
                });
        </script>
    </div>
</section>

<!-- ======= About Section ======= -->

@endsection
