<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;

class UMKMController extends Controller
{
    public function index()
{
    $umkm = UMKM::select('nama_usaha', 'alamat', 'latitude', 'longitude')->get();
    return view('maps', compact('umkm'));
}



}
