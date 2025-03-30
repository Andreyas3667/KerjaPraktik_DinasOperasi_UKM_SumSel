@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar UMKM Kopi</h1>
    <div class="row">
        @foreach ($umkms as $umkm)
            <div class="col-md-4">
                <div class="card">
                    <img src="/img/default.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ $umkm->nama_usaha }}</h5>
                        <p class="card-text">{{ $umkm->deskripsi }}</p>
                        <a href="/umkm/{{ $umkm->id_umkm }}" class="btn btn-primary">Detail</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
