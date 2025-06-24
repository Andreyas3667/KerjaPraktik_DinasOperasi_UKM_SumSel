<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMKM;

class MapsController extends Controller
{
    public function index()
    {
        $umkms = \App\Models\UMKM::with('produk')->whereNotNull('latitude')->whereNotNull('longitude')->get();
        return view('maps', compact('umkms'));
    }

    public function getUmkm()
    {
        $umkms = \App\Models\UMKM::with('produk')
            ->select('id_umkm', 'nama_usaha', 'alamat', 'latitude', 'longitude')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        // Jika ingin kirim produk juga:
        $umkms = $umkms->map(function($umkm) {
            return [
                'id_umkm' => $umkm->id_umkm,
                'nama_usaha' => $umkm->nama_usaha,
                'alamat' => $umkm->alamat,
                'latitude' => $umkm->latitude,
                'longitude' => $umkm->longitude,
                'produk' => $umkm->produk->map(function($p) {
                    return [
                        'id' => $p->id_produk,
                        'nama_produk' => $p->nama_produk,
                        'harga' => $p->harga,
                    ];
                }),
            ];
        });

        return response()->json($umkms);
    }
}
