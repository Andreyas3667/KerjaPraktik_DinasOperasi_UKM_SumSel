<?php

namespace App\Http\Controllers;
use App\Models\UMKM;
use Illuminate\Http\Request;

class MapsController extends Controller
{
    public function index()
    {
        return view('maps');
    }

    public function getUMKM()
    {
        return response()->json(UMKM::select('id_umkm', 'nama_usaha', 'alamat', 'latitude', 'longitude')->get());
    }
}
