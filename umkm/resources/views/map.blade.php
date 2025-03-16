<!DOCTYPE html>
<html>
<head>
    <title>UMKM Kopi Map</title>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}"></script>
    <script>
        function initMap() {
            var sumsel = { lat: -3.3194, lng: 104.9176 };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 7,
                center: sumsel
            });

            var locations = @json($umkm);
            locations.forEach(function(location) {
                var marker = new google.maps.Marker({
                    position: { lat: parseFloat(location.latitude), lng: parseFloat(location.longitude) },
                    map: map,
                    title: location.nama_usaha
                });

                var infowindow = new google.maps.InfoWindow({
                    content: '<strong>' + location.nama_usaha + '</strong><br>' + location.alamat
                });

                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            });
        }
    </script>
</head>
<body onload="initMap()">
    <div id="map" style="width:100%;height:500px;"></div>
</body>
</html>
