<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maps</title>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <script>
        function initMap() {
            var sumateraSelatan = { lat: -3.319437, lng: 103.914399 };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: sumateraSelatan
            });

            fetch('/api/umkm')
                .then(response => response.json())
                .then(data => {
                    data.forEach(umkm => {
                        var marker = new google.maps.Marker({
                            position: { lat: parseFloat(umkm.latitude), lng: parseFloat(umkm.longitude) },
                            map: map,
                            title: umkm.nama_usaha
                        });

                        marker.addListener('click', function() {
                            window.location.href = '/umkm/' + umkm.id_umkm;
                        });
                    });
                });
        }
    </script>
</head>
<body onload="initMap()">
    <div id="map" style="width: 100%; height: 500px;"></div>
</body>
</html>
