@extends('layouts.app')

@section('title', 'Peta UMKM')

@section('content')
<div id="map" style="height: 500px;"></div>
<script>
let map = L.map('map').setView([-2.5, 104], 7);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

fetch('/api/umkm')
    .then(res => res.json())
    .then(data => {
        data.forEach(umkm => {
            if (umkm.latitude && umkm.longitude) {
                let produkList = '';
                umkm.produk.forEach(p => {
                    produkList += `
                        <div>
                            <b>${p.nama_produk}</b> - Rp${p.harga}<br>
                            <input type="number" id="qty_${p.id}" value="1" min="1" style="width:60px;">
                            <a href="#" onclick="orderWA('${umkm.kontak}', '${p.nama_produk}', ${p.id}); return false;" class="btn btn-success btn-sm mt-1">Beli via WhatsApp</a>
                        </div>
                        <hr>
                    `;
                });
                let popupContent = `
                    <b>${umkm.nama_umkm}</b><br>
                    ${umkm.alamat}<br>
                    <hr>
                    <b>Produk:</b><br>
                    ${produkList}
                `;
                L.marker([umkm.latitude, umkm.longitude]).addTo(map)
                    .bindPopup(popupContent);
            }
        });
    });

function orderWA(kontak, produk, id) {
    let qty = document.getElementById('qty_' + id).value;
    let pesan = encodeURIComponent(`Saya membeli produk ${produk} sebanyak ${qty}`);
    window.open(`https://wa.me/${kontak}?text=${pesan}`, '_blank');
}
</script>
@endsection

@push('js')
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
@endpush
