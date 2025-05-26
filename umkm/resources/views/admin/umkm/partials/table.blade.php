@foreach ($umkms as $umkm)
<tr data-id="{{ $umkm->id_umkm }}" data-nama="{{ $umkm->nama_usaha }}" data-alamat="{{ $umkm->alamat }}" data-kontak="{{ $umkm->kontak }}" data-wilayah="{{ $umkm->id_wilayah }}">
    <td>{{ $loop->iteration }}</td>
    <td>{{ $umkm->nama_usaha }}</td>
    <td>{{ $umkm->alamat }}</td>
    <td>{{ $umkm->kontak }}</td>
    <td>{{ $umkm->wilayah->nama_wilayah ?? '-' }}</td>
    <td>
        @if($umkm->produk && $umkm->produk->count())
            <ul class="mb-0 pl-3">
                @foreach($umkm->produk as $produk)
                    <li>{{ $produk->nama_produk }}</li>
                @endforeach
            </ul>
            <button class="btn btn-info btn-sm mt-2 show-produk-btn" data-produk='@json($umkm->produk->pluck("nama_produk"))'>
                Show
            </button>
        @else
            <span class="text-muted">Belum ada produk</span>
        @endif
    </td>
</tr>
@endforeach