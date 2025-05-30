<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\UMKM;
use Illuminate\Support\Facades\Auth;

class ProdukController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $umkm = $user ? $user->umkm : null;
        if (!$umkm) {
            // Tampilkan view produk dengan pesan error
            return view('umkm.produk', [
                'produks' => collect(),
                'error' => 'Anda belum memiliki data UMKM. Silakan lengkapi profil terlebih dahulu.'
            ]);
        }
        $produks = $umkm->produk ?? collect();
        return view('umkm.produk', compact('produks'));
    }

    public function create()
    {
        return view('umkm.produk_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $user = auth()->user();
        $umkm = $user ? $user->umkm : null;
        $umkm_id = $umkm ? $umkm->id_umkm : 1;

        $data = $request->only(['nama_produk', 'deskripsi', 'stok', 'harga']);
        $data['id_umkm'] = $umkm_id;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        \App\Models\Produk::create($data);
        return redirect()->route('umkm.produk')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::where('id_produk', $id)->firstOrFail();
        return view('umkm.produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = \App\Models\Produk::findOrFail($id);
        $request->validate([
            'nama_produk' => 'required',
            'deskripsi' => 'nullable',
            'stok' => 'required|integer',
            'harga' => 'required|integer',
            'gambar' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['nama_produk', 'deskripsi', 'stok', 'harga']);
        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);
        return redirect()->route('umkm.produk')->with('success', 'Produk berhasil diupdate!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect()->route('umkm.produk')->with('success', 'Produk berhasil dihapus!');
    }
}
