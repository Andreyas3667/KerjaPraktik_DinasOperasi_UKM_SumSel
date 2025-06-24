@foreach ($umkms as $umkm)
<tr
    data-id="{{ $umkm->id_umkm }}"
    data-nama="{{ $umkm->nama_usaha }}"
    data-deskripsi="{{ $umkm->deskripsi }}"
    data-alamat="{{ $umkm->alamat }}"
    data-kontak="{{ $umkm->kontak }}"
    data-wilayah="{{ $umkm->id_wilayah }}"
    data-longitude="{{ $umkm->longitude }}"
    data-latitude="{{ $umkm->latitude }}"
    data-namapj="{{ $umkm->user->nama ?? '' }}"
    data-email="{{ $umkm->user->email ?? '' }}"
>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $umkm->nama_usaha }}</td>
    <td>{{ $umkm->alamat }}</td>
    <td>{{ $umkm->kontak }}</td>
    <td class="text-center">{{ $umkm->wilayah->nama_wilayah ?? '-' }}</td>
    <td>{{ $umkm->deskripsi }}</td>
    <td>
        <button class="btn btn-info btn-sm show-produk-btn" data-produk='@json($umkm->produk->map(function($p) {
    return [
        "nama_produk" => $p->nama_produk,
        "deskripsi" => $p->deskripsi,
        "gambar" => $p->gambar // GANTI dari "foto" ke "gambar"
    ];
}))'>
            Show
        </button>
    </td>
</tr>
@endforeach
