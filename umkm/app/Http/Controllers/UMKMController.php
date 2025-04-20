<?php

namespace App\Http\Controllers;
use App\Models\UMKM;
use Illuminate\Http\Request;

class UMKMController extends Controller
{
    public function index()
    {
        $umkms = UMKM::with('produk', 'wilayah', 'user')->get();
        return view('umkm.index', compact('umkms'));
    }

    public function show($id)
    {
        $umkm = UMKM::with('produk')->findOrFail($id);
        return view('umkm.show', compact('umkm'));
    }

}
