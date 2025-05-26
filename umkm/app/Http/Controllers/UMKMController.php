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
        $query = UMKM::with('produk', 'wilayah', 'user');

        // Filter search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%")
                  ->orWhere('kontak', 'like', "%$search%");
            });
        }

        // Filter wilayah
        if ($request->filled('wilayah')) {
            $query->where('id_wilayah', $request->wilayah);
        }

        $umkms = $query->get();

        return view('admin.umkm.manage', compact('umkms'));
    }

    /**
     * Simpan UMKM baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_usaha' => 'required|big|max:255',
            'alamat' => 'required|string',
            'kontak' => 'required|string',
            'longitude' => 'required|numeric',
            'latitude' => 'required|numeric',
            'id_wilayah' => 'required|exists:wilayah,id_wilayah',
            'id_user' => 'required|exists:users,id_users',
        ]);

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

    public function ajaxSearch(Request $request)
    {
        $query = UMKM::with('produk', 'wilayah', 'user');
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%")
                  ->orWhere('kontak', 'like', "%$search%");
            });
        }
        $umkms = $query->get();
        return view('admin.umkm.partials.table', compact('umkms'))->render();
    }
}
