<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;

class UMKMApiController extends Controller
{
    public function index()
    {
        return UMKM::with('produk')
            ->select('id_umkm', 'nama_usaha', 'alamat', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();
    }

    public function show($id)
    {
        return UMKM::with('produk')->findOrFail($id);
    }
}
