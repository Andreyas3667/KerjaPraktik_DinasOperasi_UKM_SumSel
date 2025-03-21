<?php

namespace App\Http\Controllers;
use App\Models\produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function tambahStok(Request $request, $id)
{
    $produk = Produk::find($id);
    $produk->stok += $request->stok;
    $produk->save();

    return back()->with('success', 'Stok berhasil ditambahkan');
}
}
