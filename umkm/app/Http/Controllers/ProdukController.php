<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'umkm_id' => 'required|exists:umkms,id',
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        Produk::create($request->all());

        return back()->with('success', 'Produk berhasil ditambahkan.');
    }
    public function show($id)
    {
        $umkm = \App\Models\Umkm::with('produks')->findOrFail($id);
        return view('umkm.detail', compact('umkm'));
    }
    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk.edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());

        return redirect()->back()->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Produk::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Produk berhasil dihapus.');
    }
}
