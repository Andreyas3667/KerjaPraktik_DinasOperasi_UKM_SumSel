<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Produk Kopi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        .product-info {
            margin-bottom: 20px;
        }
        .whatsapp-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #25D366;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .whatsapp-button:hover {
            background-color: #1EBE5D;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Produk</h2>

        <div class="product-info">
            <strong>Nama Produk:</strong> {{ $produk->nama_produk }} <br>
            <strong>Deskripsi:</strong> {{ $produk->deskripsi }} <br>
            <strong>Harga:</strong> Rp {{ number_format($produk->harga, 0, ',', '.') }} <br>
            <strong>Stok:</strong> {{ $produk->stok }} <br>
            <strong>UMKM:</strong> {{ $umkm->nama_usaha }} <br>
            <strong>Kontak UMKM:</strong> {{ $umkm->kontak }} <br>
        </div>

        <a href="https://wa.me/{{ $umkm->kontak }}?text=Saya%20ingin%20membeli%20{{ urlencode($produk->nama_produk) }}%20dengan%20harga%20Rp%20{{ number_format($produk->harga, 0, ',', '.') }}" target="_blank" class="whatsapp-button">
            Hubungi via WhatsApp
        </a>
    </div>
</body>
</html>
