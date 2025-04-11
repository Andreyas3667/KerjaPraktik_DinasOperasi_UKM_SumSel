@extends('layout.main')

@section('title', 'Peta UMKM')

@section('content')
<section class="container my-5">
    <h2 class="text-center">Peta UMKM Kopi</h2>
    <div id="map" style="width: 100%; height: 500px;"></div>
</section>

<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-3.319437, 103.914399], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    fetch('/api/umkm')
        .then(response => response.json())
        .then(data => {
            data.forEach(umkm => {
                var marker = L.marker([umkm.latitude, umkm.longitude]).addTo(map)
                    .bindPopup("<b>" + umkm.nama_usaha + "</b><br><a href='/umkm/" + umkm.id_umkm + "'>Lihat Detail</a>");
            });
        });
</script>
@endsection
