<form action="/produk/{{ $produk->id }}" method="POST">
    @csrf
    @method('PUT')
    <label>Nama Produk:</label>
    <input type="text" name="nama" value="{{ $produk->nama }}" required><br>
    <label>Harga:</label>
    <input type="number" name="harga" value="{{ $produk->harga }}" required><br>
    <label>Stok:</label>
    <input type="number" name="stok" value="{{ $produk->stok }}" required><br>
    <button type="submit">Update</button>
</form>
