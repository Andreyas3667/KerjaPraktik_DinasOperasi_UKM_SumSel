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
