<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $umkm->nama }}</title>
</head>
<body>
    <h1>{{ $umkm->nama }}</h1>
    <p>{{ $umkm->deskripsi }}</p>
    <p>Luas Tanah: {{ $umkm->luas_tanah }} Ha</p>
    <p><a href="https://wa.me/{{ $umkm->whatsapp }}">Hubungi via WhatsApp</a></p>

    <h2>Produk yang Dijual:</h2>
    <ul>
        @foreach($umkm->produks as $produk)
            <li>{{ $produk->nama }} - Rp{{ number_format($produk->harga, 0, ',', '.') }} (Stok: {{ $produk->stok }})</li>
        @endforeach
    </ul>
    <h2>Tambah Produk</h2>
<form action="/produk" method="POST">
    @csrf
    <input type="hidden" name="umkm_id" value="{{ $umkm->id }}">
    <label>Nama Produk:</label>
    <input type="text" name="nama" required><br>
    <label>Harga:</label>
    <input type="number" name="harga" required><br>
    <label>Stok:</label>
    <input type="number" name="stok" required><br>
    <button type="submit">Tambah</button>
</form>
@foreach($umkm->produks as $produk)
    <li>
        {{ $produk->nama }} - Rp{{ number_format($produk->harga, 0, ',', '.') }} (Stok: {{ $produk->stok }})
        <a href="/produk/{{ $produk->id }}/edit">Edit</a>
        <form action="/produk/{{ $produk->id }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" onclick="return confirm('Hapus produk ini?')">Hapus</button>
        </form>
    </li>
@endforeach

</body>
</html>
