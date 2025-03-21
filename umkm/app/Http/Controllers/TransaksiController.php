<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\UMKM;

class TransaksiController extends Controller
{
    public function show($id)
    {
        $produk = Produk::findOrFail($id);
        $umkm = UMKM::where('id_umkm', $produk->id_umkm)->firstOrFail();

        return view('transaksi', compact('produk', 'umkm'));
    }
}
