<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UMKM;

class MapsController extends Controller
{
    public function index()
    {
        return view('maps');
    }

    public function getUmkm()
    {
        $umkms = UMKM::select('nama_usaha', 'alamat', 'latitude', 'longitude')->get();
        return response()->json($umkms);
    }
}
