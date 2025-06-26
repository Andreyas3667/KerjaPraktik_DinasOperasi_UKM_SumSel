<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UMKM;
use Illuminate\Http\Request;

class UMKMApiController extends Controller
{
    public function index(Request $request)
    {
        $query = UMKM::with('produk')
            ->select('id_umkm', 'nama_usaha', 'alamat', 'latitude', 'longitude', 'deskripsi', 'kontak');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_usaha', 'like', "%$search%")
                  ->orWhere('alamat', 'like', "%$search%")
                  ->orWhere('deskripsi', 'like', "%$search%")
                  ->orWhereHas('produk', function($q2) use ($search) {
                      $q2->where('nama_produk', 'like', "%$search%");
                  })
                  ->orWhereHas('wilayah', function($q2) use ($search) {
                      $q2->where('nama_wilayah', 'like', "%$search%");
                  });
            });
        }

        $umkms = $query->whereNotNull('latitude')->whereNotNull('longitude')->get();
        return response()->json($umkms);
    }

    public function show($id)
    {
        return UMKM::with('produk')->findOrFail($id);
    }
}
