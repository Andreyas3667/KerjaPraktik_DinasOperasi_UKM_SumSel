@foreach ($umkms as $umkm)
<tr data-id="{{ $umkm->id_umkm }}" data-nama="{{ $umkm->nama_usaha }}" data-deskripsi="{{ $umkm->deskripsi }}" data-alamat="{{ $umkm->alamat }}" data-kontak="{{ $umkm->kontak }}" data-wilayah="{{ $umkm->id_wilayah }}" data-longitude="{{ $umkm->longitude }}" data-latitude="{{ $umkm->latitude }}">
    <td>{{ $loop->iteration }}</td>
    <td>{{ $umkm->nama_usaha }}</td>
    <td>{{ $umkm->alamat }}</td>
    <td>{{ $umkm->kontak }}</td>
    <td>{{ $umkm->wilayah->nama_wilayah ?? '-' }}</td>
    <td>
        <button class="btn btn-info btn-sm show-produk-btn" data-produk='@json($umkm->produk->pluck("nama_produk"))'>
            Show
        </button>
    </td>
</tr>
@endforeach