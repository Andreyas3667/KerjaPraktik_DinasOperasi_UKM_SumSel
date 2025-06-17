<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;

class UMKMApiController extends Controller
{
    public function index()
    {
        return UMKM::with('produk')->get();
    }

    public function show($id)
    {
        return UMKM::with('produk')->findOrFail($id);
    }
}
