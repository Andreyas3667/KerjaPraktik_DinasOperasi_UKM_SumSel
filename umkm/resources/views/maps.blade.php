<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta UMKM Kopi</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
    <style>
        #map {
            height: 500px;
            width: 100%;
        }
    </style>
</head>
<body>
    <h2>Lokasi UMKM Kopi di Sumatera Selatan</h2>
    <div id="map"></div>

    <script>
        function initMap() {
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: { lat: -3.319437, lng: 103.914399 } // Koordinat Sumatera Selatan
            });

            var markers = @json($umkms);
            markers.forEach(umkm => {
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(umkm.latitude), lng: parseFloat(umkm.longitude) },
                    map: map,
                    title: umkm.nama_usaha
                });

                var infoWindow = new google.maps.InfoWindow({
                    content: `<h4>${umkm.nama_usaha}</h4>
                              <p>${umkm.deskripsi}</p>
                              <p><strong>Kontak:</strong> ${umkm.kontak}</p>
                              <a href="/umkm/${umkm.id_umkm}">Lihat Detail</a>`
                });

                marker.addListener("click", function() {
                    infoWindow.open(map, marker);
                });
            });
        }
    </script>
</body>
</html>
