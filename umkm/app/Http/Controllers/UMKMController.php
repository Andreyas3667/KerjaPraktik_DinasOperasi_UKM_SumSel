<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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

    public function create()
    {
        return view('umkm.create');
    }

    /**
     * Tampilkan halaman kelola UMKM.
     */
    public function manage(Request $request)
    {
        $umkms = UMKM::with('wilayah')->get();
        $wilayahs = \App\Models\Wilayah::all(); // Ambil semua wilayah

        $editUMKM = null;
        if ($request->has('edit')) {
            $editUMKM = UMKM::findOrFail($request->edit);
        }

        return view('admin.umkm.manage', compact('umkms', 'editUMKM', 'wilayahs'));
    }

    /**
     * Simpan UMKM baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'id_wilayah' => 'required|exists:wilayah,id_wilayah',
            'id_user' => 'required|exists:users,id_users',
        ]);

        // Jika id_umkm UUID/string
        $validated['id_umkm'] = (string) Str::uuid();

        // Debugging: Log data yang divalidasi
        Log::info('Data UMKM yang divalidasi:', $validated);

        UMKM::create($validated);
        return redirect()->route('admin.umkm.manage')->with('success', 'UMKM berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $umkm = UMKM::findOrFail($id);
        return view('umkm.edit', compact('umkm'));
    }

    /**
     * Perbarui data UMKM.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
            'id_wilayah' => 'required|exists:wilayah,id_wilayah',
            'id_user' => 'required|exists:users,id_users',
        ]);

        $umkm = UMKM::findOrFail($id);
        $umkm->update($validated);
        return redirect()->route('admin.umkm.manage')->with('success', 'UMKM berhasil diperbarui.');
    }

    /**
     * Hapus UMKM.
     */
    public function destroy($id)
    {
        $umkm = UMKM::findOrFail($id);
        $umkm->delete();
        return redirect()->route('admin.umkm.manage')->with('success', 'UMKM berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $umkms = UMKM::where('nama_usaha', 'like', "%$keyword%")
            ->orWhere('deskripsi', 'like', "%$keyword%")
            ->get();

        return view('umkm.index', compact('umkms'));
    }

}
