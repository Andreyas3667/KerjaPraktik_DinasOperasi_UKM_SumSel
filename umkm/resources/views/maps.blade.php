@extends('layouts.app')

@section('title', 'Peta UMKM')

@section('content')
<section class="container my-5">
    <h2 class="text-center">Peta UMKM Kopi</h2>
    <div id="map" style="width: 100%; height: 500px;"></div>
</section>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([-3.319437, 103.914399], 7);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    fetch('/api/umkm')
        .then(response => response.json())
        .then(data => {
            data.forEach(umkm => {
                if (umkm.latitude && umkm.longitude) {
                    L.marker([umkm.latitude, umkm.longitude])
                        .addTo(map)
                        .bindPopup("<b>" + umkm.nama_usaha + "</b><br><a href='/umkm/" + umkm.id_umkm + "'>Lihat Detail</a>");
                }
            });
        });
</script>
@endpush
