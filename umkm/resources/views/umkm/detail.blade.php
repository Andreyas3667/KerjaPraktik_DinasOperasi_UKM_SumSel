@extends('layout.main')
@section('title', $umkm->nama_usaha)
@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-body">
            <h2 class="mb-1">{{ $umkm->nama_usaha }}</h2>
            <p class="text-muted mb-3"><i class="fas fa-map-marker-alt"></i> {{ $umkm->alamat }}</p>
            <hr>
            <h4 class="mb-3">Katalog Produk</h4>
            <form id="formKeranjang">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Produk</th>
                                <th>Deskripsi</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($umkm->produk as $produk)
                            <tr>
                                <td>
                                    <b>{{ $produk->nama_produk }}</b>
                                    @if($produk->gambar)
                                        <br>
                                        <img src="{{ asset('storage/'.$produk->gambar) }}" width="60" class="rounded mt-1">
                                    @endif
                                </td>
                                <td>{{ $produk->deskripsi }}</td>
                                <td>Rp {{ number_format($produk->harga) }}</td>
                                <td>{{ $produk->stok }}</td>
                                <td style="width:120px;">
                                    <input type="number" min="0" max="{{ $produk->stok }}" name="qty[{{ $produk->id_produk }}]" class="form-control" value="0">
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-success mt-3" onclick="checkoutWA()">
                    <i class="fab fa-whatsapp"></i> Beli via WhatsApp
                </button>
            </form>
        </div>
    </div>
</div>
<script>
function checkoutWA() {
    let form = document.getElementById('formKeranjang');
    let data = new FormData(form);
    let pesan = "Halo, saya ingin membeli produk berikut:%0A";
    let total = 0;
    let ada = false;

    let produkList = [
        @foreach($umkm->produk as $produk)
        {
            id: "{{ $produk->id_produk }}",
            nama: "{{ $produk->nama_produk }}",
            harga: {{ $produk->harga }}
        }@if(!$loop->last),@endif
        @endforeach
    ];

    let qtyData = {};
    produkList.forEach(function(prod) {
        let qty = data.get('qty[' + prod.id + ']');
        if (qty && parseInt(qty) > 0) {
            let subtotal = prod.harga * parseInt(qty);
            pesan += "- " + prod.nama + " x " + qty + " @ Rp" + prod.harga.toLocaleString('id-ID') + " = Rp" + subtotal.toLocaleString('id-ID') + "%0A";
            total += subtotal;
            qtyData[prod.id] = parseInt(qty);
            ada = true;
        }
    });

    if (!ada) {
        alert('Pilih minimal 1 produk!');
        return;
    }
    pesan += "Total: Rp" + total.toLocaleString('id-ID');

    let userNama = "{{ auth()->user()->nama ?? '' }}";
    let userTelp = "{{ auth()->user()->telepon ?? '' }}";
    let userAlamat = "{{ auth()->user()->alamat ?? '' }}";
    pesan = `Nama: ${userNama}%0ATelepon: ${userTelp}%0AAlamat: ${userAlamat}%0A` + pesan;

    // Kirim transaksi ke backend
    fetch("{{ route('umkm.transaksi', $umkm->id_umkm) }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        },
        body: new URLSearchParams({
            @foreach($umkm->produk as $produk)
            @php $id = $produk->id_produk; @endphp
            @if(!$loop->first),@endif
            "qty[{{$id}}]": data.get('qty[{{$id}}]')
            @endforeach
        })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            let wa = "{{ preg_replace('/[^0-9]/', '', $umkm->kontak) }}";
            window.open(`https://wa.me/${wa}?text=${pesan}`, '_blank');
            // Redirect ke halaman history penjualan admin jika perlu
            // window.location.href = '/admin/penjualan'; // opsional
        } else {
            alert('Gagal menyimpan transaksi!');
        }
    })
    .catch(() => alert('Terjadi kesalahan!'));
}
</script>
@endsection